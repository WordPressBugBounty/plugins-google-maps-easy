<?php
class gmapGmp extends moduleGmp
{
  private $_stylizations = [];
  private $_markersLists = [];
  private $_mapsInPosts = [];
  public $_mapsInPostsParams = [];
  private $_elementorWidgetRegistered = false;

  public function init()
  {
    dispatcherGmp::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    $this->registerGutenbergBlock();
    add_action('wp_head', [$this, 'addMapStyles']);
    add_action('template_redirect', [$this, 'getMapsInPosts']);
    add_action('wp_footer', [$this, 'addMapDataToJs'], 5);
    if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '3.5.0', '<')) {
      add_action('elementor/widgets/widgets_registered', [$this, 'registerElementorWidget']);
    } else {
      add_action('elementor/widgets/register', [$this, 'registerElementorWidget']);
    }
    add_shortcode(GMP_SHORTCODE, [$this, 'drawMapFromShortcode']);
    // Add to admin bar new item
    add_action('admin_bar_menu', [$this, 'addAdminBarNewItem'], 300);
  }
  public function registerElementorWidget($widgetsManager = null)
  {
    if ($this->_elementorWidgetRegistered) {
      return;
    }
    if (!did_action('elementor/loaded') || !class_exists('\Elementor\Widget_Base')) {
      return;
    }

    $widgetFile = $this->getModDir() . 'elementor' . DS . 'google-maps-easy-widget.php';
    if (!class_exists('\Elementor\Widget_Google_Maps_Easy_Gmp') && is_file($widgetFile)) {
      require_once $widgetFile;
    }

    if (!class_exists('\Elementor\Widget_Google_Maps_Easy_Gmp')) {
      return;
    }

    $widget = new \Elementor\Widget_Google_Maps_Easy_Gmp();
    $this->_elementorWidgetRegistered = true;
    if ($widgetsManager && method_exists($widgetsManager, 'register')) {
      $widgetsManager->register($widget);
    } elseif ($widgetsManager && method_exists($widgetsManager, 'register_widget_type')) {
      $widgetsManager->register_widget_type($widget);
    } elseif (isset(\Elementor\Plugin::$instance->widgets_manager)) {
      $manager = \Elementor\Plugin::$instance->widgets_manager;
      method_exists($manager, 'register') ? $manager->register($widget) : $manager->register_widget_type($widget);
    }
  }
  public function registerGutenbergBlock()
  {
    if (!function_exists('register_block_type') || !function_exists('wp_register_script')) {
      return;
    }

    $scriptHandle = 'gmp-gutenberg-maps-block';
    $scriptFile = $this->getModDir() . 'gutenberg' . DS . 'google-maps-easy-block.js';
    if (!is_file($scriptFile)) {
      return;
    }

    wp_register_script(
      $scriptHandle,
      $this->getModPath() . 'gutenberg/google-maps-easy-block.js',
      ['wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-server-side-render'],
      GMP_VERSION_PLUGIN,
      true
    );
    wp_localize_script($scriptHandle, 'gmpGutenbergMaps', [
      'maps' => $this->getMapsOptionsForEditor(),
      'defaultMapId' => $this->getDefaultMapId(),
    ]);

    register_block_type('supsystic/google-maps-easy', [
      'editor_script' => $scriptHandle,
      'render_callback' => [$this, 'renderGutenbergBlock'],
      'attributes' => [
        'map_id' => [
          'type' => 'string',
          'default' => '',
        ],
        'width' => [
          'type' => 'string',
          'default' => '',
        ],
        'height' => [
          'type' => 'number',
        ],
        'align' => [
          'type' => 'string',
          'default' => '',
        ],
      ],
    ]);
  }
  public function renderGutenbergBlock($attributes)
  {
    $mapId = !empty($attributes['map_id']) ? (int) $attributes['map_id'] : 0;
    if (!$mapId) {
      return '';
    }

    $params = ['id' => $mapId];
    if (!empty($attributes['width'])) {
      $params['width'] = sanitize_text_field($attributes['width']);
    }
    if (isset($attributes['height']) && $attributes['height'] !== '') {
      $params['height'] = absint($attributes['height']);
    }
    if (!empty($attributes['align']) && in_array($attributes['align'], ['left', 'right', 'none'], true)) {
      $params['align'] = $attributes['align'];
    }

    return $this->drawMapFromShortcode($params);
  }
  public function getMapsOptionsForSelect()
  {
    $options = ['' => __('Select a map', GMP_LANG_CODE)];
    $maps = $this->getModel()->getAllMaps(['simple' => true]);

    if (!empty($maps)) {
      foreach ($maps as $map) {
        if (empty($map['id'])) {
          continue;
        }
        $id = (int) $map['id'];
        $title = !empty($map['title']) ? wp_strip_all_tags($map['title']) : sprintf(__('Map #%d', GMP_LANG_CODE), $id);
        $options[(string) $id] = sprintf('%s (ID: %d)', $title, $id);
      }
    }

    return $options;
  }
  public function getMapsOptionsForEditor()
  {
    $options = [];
    foreach ($this->getMapsOptionsForSelect() as $value => $label) {
      $options[] = [
        'label' => $label,
        'value' => (string) $value,
      ];
    }
    return $options;
  }
  public function getDefaultMapId()
  {
    foreach ($this->getMapsOptionsForSelect() as $id => $label) {
      if ($id !== '') {
        return $id;
      }
    }
    return '';
  }
  public function addAdminTab($tabs)
  {
    $tabs[$this->getCode() . '_add_new'] = [
      'label' => __('Add Map', GMP_LANG_CODE),
      'callback' => [$this, 'getAddNewTabContent'],
      'fa_icon' => 'fa-plus-circle',
      'sort_order' => 10,
      'add_bread' => $this->getCode(),
    ];
    $tabs[$this->getCode() . '_edit'] = [
      'label' => __('Edit', GMP_LANG_CODE),
      'callback' => [$this, 'getEditTabContent'],
      'sort_order' => 20,
      'child_of' => $this->getCode(),
      'hidden' => 1,
      'add_bread' => $this->getCode(),
    ];
    $tabs[$this->getCode()] = [
      'label' => __('All Maps', GMP_LANG_CODE),
      'callback' => [$this, 'getTabContent'],
      'fa_icon' => 'fa-list',
      'sort_order' => 20, //'is_main' => true,
    ];
    return $tabs;
  }
  public function getAddNewTabContent()
  {
    return $this->getView()->getEditMap();
  }
  public function getEditTabContent()
  {
    $id = (int) reqGmp::getVar('id', 'get');
    if (!$id) {
      return __('No Map Found', GMP_LANG_CODE);
    }
    return $this->getView()->getEditMap($id);
  }
  public function getTabContent()
  {
    return $this->getView()->getTabContent();
  }
  public function getMapsInPosts()
  {
    if (empty($this->_mapsInPosts)) {
      global $wp_query;

      $havePostsListing = $wp_query && is_object($wp_query) && isset($wp_query->posts) && is_array($wp_query->posts) && !empty($wp_query->posts);

      if ($havePostsListing) {
        foreach ($wp_query->posts as $post) {
          if (is_object($post) && isset($post->post_content)) {
            if (preg_match_all('/\[\s*' . GMP_SHORTCODE . '\s+.*id\s*\=\s*"(?P<MAP_ID>\d+)".*\]/iUs', $post->post_content, $matches)) {
              if (!is_array($matches['MAP_ID'])) {
                $matches['MAP_ID'] = [$matches['MAP_ID']];
              }
              $matches['MAP_ID'] = array_map('intval', $matches['MAP_ID']);
              $this->_mapsInPosts = array_merge($this->_mapsInPosts, $matches['MAP_ID']);

              if (!empty($matches[0])) {
                foreach ($matches[0] as $data) {
                  preg_match_all('/(?P<KEYS>\w+)=["|\'](?P<VALUES>.*)["|\']/iU', $data, $params);
                  if (!is_array($params['KEYS'])) {
                    $params['KEYS'] = [$params['KEYS']];
                  }
                  if (!is_array($params['VALUES'])) {
                    $params['VALUES'] = [$params['VALUES']];
                  }
                  $map_params = [];
                  foreach ($params['KEYS'] as $key => $val) {
                    $map_params[$val] = $params['VALUES'][$key];
                  }
                  $this->_mapsInPostsParams = array_merge($this->_mapsInPostsParams, [$map_params]);
                }
              }
            }
          }
        }
      }
    }
    return $this->_mapsInPosts;
  }
  public function addMapStyles()
  {
    if (!empty($this->_mapsInPosts)) {
      $mapsOnPage = $this->getView()->getMapsObj();
      $iter = 0;

      foreach ($mapsOnPage as $map) {
        if (!empty($this->_mapsInPostsParams[$iter])) {
          $this->_mapsInPostsParams[$map['view_id']] = $this->_mapsInPostsParams[$iter];
        }
        $iter++;
        echo $this->getView()->addMapStyles($map['view_id']);
      }
    }
  }
  public function drawMapFromShortcode($params = null)
  {
    frameGmp::_()->getModule('templates')->loadCoreJs();

    if (!isset($params['id']) || empty($params['id'])) {
      return __('Empty or Invalid Map ID', GMP_LANG_CODE) . '. ' . __('Please, check your Map Shortcode.', GMP_LANG_CODE);
    }

    return $this->getView()->drawMap($params);
  }
  public function addMapDataToJs()
  {
    $this->getView()->addMapDataToJs();
  }
  public function generateShortcode($map)
  {
    $shortcodeParams = [];
    $shortcodeParams['id'] = $map['id'];
    // For PRO version
    $shortcodeParamsArr = [];
    foreach ($shortcodeParams as $k => $v) {
      $shortcodeParamsArr[] = $k . "='" . $v . "'";
    }
    return '[' . GMP_SHORTCODE . ' ' . implode(' ', $shortcodeParamsArr) . ']';
  }
  /**
   * Texts of the editor layout (js/admin.gmap.settings-ui.js): the English text is the key, the value its translation.
   */
  public function getSettingsUiTexts()
  {
    $texts = [
      'Settings groups' => __('Settings groups', GMP_LANG_CODE),
      'Search settings' => __('Search settings', GMP_LANG_CODE),
      'No settings match your search.' => __('No settings match your search.', GMP_LANG_CODE),
      'Basics' => __('Basics', GMP_LANG_CODE),
      'Center & Zoom' => __('Center & Zoom', GMP_LANG_CODE),
      'Controls' => __('Controls', GMP_LANG_CODE),
      'Look' => __('Look', GMP_LANG_CODE),
      'Markers' => __('Markers', GMP_LANG_CODE),
      'Layers' => __('Layers', GMP_LANG_CODE),
      'Info window' => __('Info window', GMP_LANG_CODE),
      'More' => __('More', GMP_LANG_CODE),
      'Map' => __('Map', GMP_LANG_CODE),
      'Name, size and the Google Cloud Map ID.' => __('Name, size and the Google Cloud Map ID.', GMP_LANG_CODE),
      'Map center' => __('Map center', GMP_LANG_CODE),
      'Type an address or coordinates, or drag the map in the preview.' => __('Type an address or coordinates, or drag the map in the preview.', GMP_LANG_CODE),
      'Zoom' => __('Zoom', GMP_LANG_CODE),
      'The first zoom of the map, on desktops and phones.' => __('The first zoom of the map, on desktops and phones.', GMP_LANG_CODE),
      'Interaction' => __('Interaction', GMP_LANG_CODE),
      'How visitors can move and zoom the map.' => __('How visitors can move and zoom the map.', GMP_LANG_CODE),
      'Map controls' => __('Map controls', GMP_LANG_CODE),
      'The buttons Google draws on the map and where they sit.' => __('The buttons Google draws on the map and where they sit.', GMP_LANG_CODE),
      'Custom controls' => __('Custom controls', GMP_LANG_CODE),
      'Your own set of buttons over the map: zoom, reset, search, filters.' => __('Your own set of buttons over the map: zoom, reset, search, filters.', GMP_LANG_CODE),
      'Theme & style' => __('Theme & style', GMP_LANG_CODE),
      'Map type, color stylization and what the map hides.' => __('Map type, color stylization and what the map hides.', GMP_LANG_CODE),
      'Clusters, tooltips and how the map follows the markers.' => __('Clusters, tooltips and how the map follows the markers.', GMP_LANG_CODE),
      'Markers list' => __('Markers list', GMP_LANG_CODE),
      'A list or slider of the markers next to the map.' => __('A list or slider of the markers next to the map.', GMP_LANG_CODE),
      'Category filter' => __('Category filter', GMP_LANG_CODE),
      'The filter of marker categories over the map.' => __('The filter of marker categories over the map.', GMP_LANG_CODE),
      'Visitors' => __('Visitors', GMP_LANG_CODE),
      'Markers added by visitors and the search of places.' => __('Markers added by visitors and the search of places.', GMP_LANG_CODE),
      'Google layers' => __('Google layers', GMP_LANG_CODE),
      'Live traffic, public transport and bicycle routes.' => __('Live traffic, public transport and bicycle routes.', GMP_LANG_CODE),
      'KML layers' => __('KML layers', GMP_LANG_CODE),
      'Zones, routes and points from your KML files.' => __('Zones, routes and points from your KML files.', GMP_LANG_CODE),
      'The window that opens on a marker: its look, size and fonts.' => __('The window that opens on a marker: its look, size and fonts.', GMP_LANG_CODE),
      'Info window buttons' => __('Info window buttons', GMP_LANG_CODE),
      'Directions and printing from the window of a marker.' => __('Directions and printing from the window of a marker.', GMP_LANG_CODE),
      'More options' => __('More options', GMP_LANG_CODE),
      'Marker' => __('Marker', GMP_LANG_CODE),
      'Name, place, icon and what opens on a click.' => __('Name, place, icon and what opens on a click.', GMP_LANG_CODE),
      'Figure' => __('Figure', GMP_LANG_CODE),
      'A polygon, polyline or circle drawn on the map.' => __('A polygon, polyline or circle drawn on the map.', GMP_LANG_CODE),
      'Heatmap layer' => __('Heatmap layer', GMP_LANG_CODE),
      'Points that show where things happen most.' => __('Points that show where things happen most.', GMP_LANG_CODE),
      'Path router' => __('Path router', GMP_LANG_CODE),
      'A route between the points of the map.' => __('A route between the points of the map.', GMP_LANG_CODE),
    ];
    // Only the texts that differ are sent to the page
    return array_filter(
      $texts,
      function ($value, $key) {
        return $value !== $key;
      },
      ARRAY_FILTER_USE_BOTH,
    );
  }
  public function getControlsPositions()
  {
    return [
      'TOP_CENTER' => __('Top Center', GMP_LANG_CODE),
      'TOP_LEFT' => __('Top Left', GMP_LANG_CODE),
      'TOP_RIGHT' => __('Top Right', GMP_LANG_CODE),
      'LEFT_TOP' => __('Left Top', GMP_LANG_CODE),
      'RIGHT_TOP' => __('Right Top', GMP_LANG_CODE),
      'LEFT_CENTER' => __('Left Center', GMP_LANG_CODE),
      'RIGHT_CENTER' => __('Right Center', GMP_LANG_CODE),
      'LEFT_BOTTOM' => __('Left Bottom', GMP_LANG_CODE),
      'RIGHT_BOTTOM' => __('Right Bottom', GMP_LANG_CODE),
      'BOTTOM_CENTER' => __('Bottom Center', GMP_LANG_CODE),
      'BOTTOM_LEFT' => __('Bottom Left', GMP_LANG_CODE),
      'BOTTOM_RIGHT' => __('Bottom Right', GMP_LANG_CODE),
    ];
  }
  public function getCustomControlsUnit()
  {
    return [
      'meter' => __('meters', GMP_LANG_CODE),
      'mile' => __('miles', GMP_LANG_CODE),
    ];
  }
  public function getEditMapLink($id)
  {
    return frameGmp::_()->getModule('options')->getTabUrl('gmap_edit') . '&id=' . $id;
  }
  public function getCountriesList()
  {
    return require_once $this->getModDir() . 'countries.php';
  }
  public function getStylizationsList()
  {
    if (empty($this->_stylizations)) {
      $this->_stylizations = dispatcherGmp::applyFilters('stylizationsList', require_once $this->getModDir() . 'stylezations.php');
      foreach ($this->_stylizations as $k => $v) {
        $decoded = utilsGmp::jsonDecode($v);
        if ($decoded === null && is_string($v)) {
          $decoded = utilsGmp::jsonDecode($this->_repairStyleJson($v));
        }
        $this->_stylizations[$k] = $decoded;
      }
    }
    return $this->_stylizations;
  }
  /**
   * Some bundled styles were pasted from JS snippets: "// --- comment ---" blocks,
   * unquoted keys and trailing commas. Normalize them to strict JSON.
   */
  private function _repairStyleJson($json)
  {
    $json = preg_replace('~//\s*-{2,}.*?-{2,}~', '', $json);
    $json = preg_replace('~([{,]\s*)([A-Za-z_][A-Za-z0-9_]*)\s*:~', '$1"$2":', $json);
    return preg_replace('~,\s*([\]}])~', '$1', $json);
  }
  public function getStylizationByName($name)
  {
    $this->getStylizationsList();
    // Saved names go through wp_kses_post() on save, so "Red & Green" is stored as "Red &amp; Green".
    $name = html_entity_decode((string) $name, ENT_QUOTES, 'UTF-8');
    return isset($this->_stylizations[$name]) ? $this->_stylizations[$name] : false;
  }
  public function getMarkerLists()
  {
    if (empty($this->_markersLists)) {
      // or == orientation (horizontal, vertical), d == display (title, image, description), eng == slider engine (jssor)
      $this->_markersLists = [
        'slider_simple' => ['label' => __('Slider', GMP_LANG_CODE), 'or' => 'h', 'd' => ['title', 'img', 'desc'], 'eng' => 'jssor'],
        'slider_simple_title_img' => ['label' => __('Slider - Title and Img', GMP_LANG_CODE), 'or' => 'h', 'd' => ['title', 'img'], 'eng' => 'jssor'],
        'slider_simple_vertical' => ['label' => __('Slider Vertical', GMP_LANG_CODE), 'or' => 'v', 'd' => ['title', 'img', 'desc'], 'eng' => 'jssor'],
        'slider_simple_vertical_title_img' => ['label' => __('Slider Vertical - Title and Img', GMP_LANG_CODE), 'or' => 'v', 'd' => ['title', 'img'], 'eng' => 'jssor'],
        'slider_simple_vertical_title_desc' => ['label' => __('Slider Vertical - Title and Description', GMP_LANG_CODE), 'or' => 'v', 'd' => ['title', 'desc'], 'eng' => 'jssor'],
        'slider_simple_vertical_img_2cols' => ['label' => __('Slider Vertical - Title and Img', GMP_LANG_CODE), 'or' => 'v', 'd' => ['img'], 'eng' => 'jssor', 'two_cols' => true],
        'slider_simple_table' => ['label' => __('Slider Table', GMP_LANG_CODE), 'or' => 'h', 'd' => ['title', 'img', 'desc'], 'eng' => 'table'],
        'slider_checkbox_table' => ['label' => __('Slider Checkbox Table', GMP_LANG_CODE), 'or' => 'h', 'd' => ['title', 'img', 'desc'], 'eng' => 'table_checkbox'],
      ];
      foreach ($this->_markersLists as $i => $v) {
        $this->_markersLists[$i]['prev_img'] = isset($this->_markersLists[$i]['prev_img']) ? $this->_markersLists[$i]['prev_img'] : $i . '.jpg';
        $this->_markersLists[$i]['slide_height'] = 150;
        $this->_markersLists[$i]['slide_width'] = in_array('img', $this->_markersLists[$i]['d']) && in_array('desc', $this->_markersLists[$i]['d']) ? 400 : 200;
        if (isset($this->_markersLists[$i]['two_cols']) && $this->_markersLists[$i]['two_cols']) {
          $this->_markersLists[$i]['slide_height'] = round($this->_markersLists[$i]['slide_height'] / 2);
        }
      }
    }
    return $this->_markersLists;
  }
  public function getMarkerListByKey($key)
  {
    $this->getMarkerLists();
    return isset($this->_markersLists[$key]) ? $this->_markersLists[$key] : false;
  }
  public function addAdminBarNewItem($wp_admin_bar)
  {
    $mainCap = frameGmp::_()->getModule('adminmenu')->getMainCap();
    if (!current_user_can($mainCap) || !$wp_admin_bar || !is_object($wp_admin_bar)) {
      return;
    }
    $wp_admin_bar->add_menu([
      'parent' => 'new-content',
      'id' => GMP_CODE . '-admin-bar-new-item',
      'title' => __('Google Map', GMP_LANG_CODE),
      'href' => frameGmp::_()
        ->getModule('options')
        ->getTabUrl($this->getCode() . '_add_new'),
    ]);
  }
}
