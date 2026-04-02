<?php
class gmapGmp extends moduleGmp
{
  private $_stylizations = [];
  private $_markersLists = [];
  private $_mapsInPosts = [];
  public $_mapsInPostsParams = [];

  public function init()
  {
    dispatcherGmp::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    add_action('wp_head', [$this, 'addMapStyles']);
    add_action('template_redirect', [$this, 'getMapsInPosts']);
    add_action('wp_footer', [$this, 'addMapDataToJs'], 5);
    add_shortcode(GMP_SHORTCODE, [$this, 'drawMapFromShortcode']);
    // Add to admin bar new item
    add_action('admin_bar_menu', [$this, 'addAdminBarNewItem'], 300);
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
        $this->_stylizations[$k] = utilsGmp::jsonDecode($this->_stylizations[$k]);
      }
    }
    return $this->_stylizations;
  }
  public function getStylizationByName($name)
  {
    $this->getStylizationsList();
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
