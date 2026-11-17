<?php
class supsystic_promoViewGmp extends viewGmp
{
  public function getOverviewTabContent()
  {
    frameGmp::_()->getModule('templates')->loadJqueryUi();
    frameGmp::_()->addScript('admin.overview', $this->getModule()->getModPath() . 'js/admin.overview.js', ['jquery'], GMP_VERSION_PLUGIN . '-overview-2');
    frameGmp::_()->addStyle('admin.overview', $this->getModule()->getModPath() . 'css/admin.overview.css', [], GMP_VERSION_PLUGIN . '-overview-10');
    if (!$this->getModule()->isPro()) {
      // Free vs PRO matrix and prices of the live plugin page (views/tpl/overviewProOffer.php), only without PRO
      frameGmp::_()->addStyle('admin.pro-offer', $this->getModule()->getModPath() . 'css/admin.pro-offer.css', [], GMP_VERSION_PLUGIN . '-offer-2');
      frameGmp::_()->addScript('admin.pro-offer', $this->getModule()->getModPath() . 'js/admin.pro-offer.js', [], GMP_VERSION_PLUGIN . '-offer-2', true);
      add_action('admin_footer', [$this, 'printProOffer']);
    }
    $this->assign('mainLink', $this->getModule()->getMainLink());
    $this->assign('faqList', $this->getFaqList());
    $this->assign('serverSettings', $this->getServerSettings());
    return parent::getContent('overviewTabContent');
  }
  /** Printed in the footer: the page content passes through wp_kses, which takes out the SVG of the offer */
  public function printProOffer()
  {
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup of the plugin
    echo $this->getContent('overviewProOffer');
  }
  public function getFaqList()
  {
    return [
      __('How to create Google Maps API Key?', GMP_LANG_CODE) => sprintf(
        __(
          'Your map suddenly stopped working and you get the following error?' .
            "<blockquote style='color: gray; font-style: italic;'>Oops! Something went wrong.This page didn't load Google Maps correctly. See the JavaScript console for technical details.</blockquote>" .
            "Please check you browser console, if you'll see such error <blockquote style='color: gray; font-style: italic;'>This site has exceeded its daily quota for maps.</blockquote>" .
            " - this <a href='https://supsystic.com/documentation/create-google-maps-api-key/' target='_blank'>article</a> is written for you and required for reading.",
          GMP_LANG_CODE,
        ),
        $this->getModule()->getMainLink(),
      ),
      __('How to use Easy Google Maps Widget?', GMP_LANG_CODE) => sprintf(
        __(
          "1. Go to Appearance -> Widgets in the WordPress navigation menu.<br />2. Find the Easy Google Maps in the list of available widgets.<br />3. Drag the Easy Google Maps widget to widget area, which you need.<br />4. Choose the map for widget and configure the settings - Widget Map width and height.<br />5. Click 'Save'.",
          GMP_LANG_CODE,
        ),
        $this->getModule()->getMainLink(),
      ),
      __('How to add map into the site content?', GMP_LANG_CODE) => sprintf(__("You can add a map in the site content via shortcode or php code. Learn more about how to do this <a  target='_blank' href='https://supsystic.com/documentation/add-map-site-content/'>here</a>.", GMP_LANG_CODE), $this->getModule()->getMainLink()),
      __('How to add map in popup window?', GMP_LANG_CODE) => sprintf(
        __("You can add a map in popup window by inserting map shortcode in any popup text field. Learn more about how to do this <a  target='_blank' href='https://supsystic.com/documentation/add-map-popup-window/'>here</a>.", GMP_LANG_CODE),
        $this->getModule()->getMainLink(),
      ),
      __('How to zoom and center the initial map on markers?', GMP_LANG_CODE) => sprintf(
        __(
          "There is a few different ways to zoom and centralize map. The easiest one is to drag your map using mouse - 'Draggable' option must be enabled, or with pan controller help in live preview. <a  target='_blank' href='https://supsystic.com/documentation/zoom-center-initial-map-markers/'>Read more...</a>",
          GMP_LANG_CODE,
        ),
        $this->getModule()->getMainLink(),
      ),
    ];
  }
  public function getServerSettings()
  {
    global $wpdb;
    return [
      'Operating System' => ['value' => PHP_OS],
      'PHP Version' => ['value' => PHP_VERSION],
      'Server Software' => ['value' => $_SERVER['SERVER_SOFTWARE']],
      'MySQL' => ['value' => $wpdb->db_version()],
      'PHP Allow URL Fopen' => ['value' => ini_get('allow_url_fopen') ? __('Yes', GMP_LANG_CODE) : __('No', GMP_LANG_CODE)],
      'PHP Memory Limit' => ['value' => ini_get('memory_limit')],
      'PHP Max Post Size' => ['value' => ini_get('post_max_size')],
      'PHP Max Upload Filesize' => ['value' => ini_get('upload_max_filesize')],
      'PHP Max Script Execute Time' => ['value' => ini_get('max_execution_time')],
      'PHP EXIF Support' => ['value' => extension_loaded('exif') ? __('Yes', GMP_LANG_CODE) : __('No', GMP_LANG_CODE)],
      'PHP EXIF Version' => ['value' => phpversion('exif')],
      'PHP XML Support' => ['value' => extension_loaded('libxml') ? __('Yes', GMP_LANG_CODE) : __('No', GMP_LANG_CODE), 'error' => !extension_loaded('libxml')],
      'PHP CURL Support' => ['value' => extension_loaded('curl') ? __('Yes', GMP_LANG_CODE) : __('No', GMP_LANG_CODE), 'error' => !extension_loaded('curl')],
    ];
  }
  /**
   * The PRO feature windows (views/tpl/upsellWindows.php), printed in the footer of the plugin pages without PRO.
   */
  public function getUpsellWindows()
  {
    $this->assign('features', $this->getModule()->getUpsellFeatures());
    $this->assign('aliases', $this->getModule()->getUpsellAliases());
    // The PRO card of the map editor (js/admin.upsell.js builds it from the windows)
    $this->assign('cardTexts', [
      'eyebrow' => 'Easy Google Maps PRO',
      'title' => __('Do more with this map', GMP_LANG_CODE),
      'text' => __('Your settings stay as they are, and the PRO options open right in these cards.', GMP_LANG_CODE),
      'cta' => __('Get PRO', GMP_LANG_CODE),
      'url' => $this->getModule()->generateMainLink('utm_source=plugin&utm_medium=map_pro_card&utm_campaign=googlemaps'),
    ]);
    return parent::getContent('upsellWindows');
  }
  /**
   * A small picture of the feature: a part of the city of img/map-city.svg (the same city as on the Overview) with the
   * feature drawn on it, looped and still for visitors who turn off motion (css/admin.upsell.css). Static markup, no
   * user data.
   * - Every point is in the coordinates of the city. Routes run along the street axes and turn only where two streets
   *   cross, zones follow the streets around their blocks, pins stand on a street.
   * - .gmp-up-world is the whole city at the scale of the picture, with its point $center in the middle of the frame;
   *   the pins and the heat inside it are placed in % of the city, so they stay on their streets at any frame size.
   * - The cards (info windows, lists, controls) sit on the frame, outside the city.
   * @param string $key
   * @return string
   */
  public function getDemoHtml($key)
  {
    // The box of the city (viewBox of img/map-city.svg)
    $box = [-240, -40, 1200, 640];
    $at = function ($x, $y) use ($box) {
      return 'left:' . round((($x - $box[0]) / $box[2]) * 100, 3) . '%;top:' . round((($y - $box[1]) / $box[3]) * 100, 3) . '%';
    };
    $pin = function ($x, $y, $color = '#e5484d', $extra = '') use ($at) {
      return '<span class="gmp-up-pin' . ($extra ? ' ' . $extra : '') . '" style="' . $at($x, $y) . ';--gmp-up-pin:' . $color . '"></span>';
    };
    $poi = function ($x, $y, $icon) use ($at) {
      return '<span class="gmp-up-poi" style="' . $at($x, $y) . '"><i class="fa ' . $icon . '"></i></span>';
    };
    // A route: straight runs along the streets from one crossing to the next
    $line = function ($points) {
      $d = '';
      foreach ($points as $i => $p) {
        $d .= ($i ? 'L' : 'M') . $p[0] . ' ' . $p[1];
      }
      return $d;
    };
    // Harbor Rd, the embankment of the bay (the same curve as in img/map-city.svg)
    $harbor = 'M360 470C424 396 480 373 550 342C620 311 650 300 720 296C790 292 880 284 970 281';
    $center = [360, 280];
    $svg = '';
    $geo = '';
    $html = '';
    $wrap = '';
    switch ($key) {
      case 'control_positions':
        $center = [345, 290];
        $html = '<span class="gmp-up-ctl gmp-up-ctl-type"><b>Map</b><i>Satellite</i></span><span class="gmp-up-ctl gmp-up-ctl-zoom"><b>+</b><b>&minus;</b></span><span class="gmp-up-ctl gmp-up-ctl-street"><i class="fa fa-male"></i></span>';
        $geo = $pin(329, 305);
        break;
      case 'map_themes':
        $wrap = ' is-plain';
        $html = '<span class="gmp-up-themes"><b class="is-1"></b><b class="is-2"></b><b class="is-3"></b><b class="is-4"></b><b class="is-5"></b><b class="is-6"></b></span>';
        break;
      case 'static_map':
        $wrap = ' is-static';
        $center = [360, 285];
        $geo = $pin(298, 262) . $pin(425, 330, '#0e8a93');
        $html = '<span class="gmp-up-badge"><i class="fa fa-bolt"></i> Static image &middot; 1 request</span>';
        break;
      case 'custom_controls':
        $center = [370, 280];
        $html = '<span class="gmp-up-toolbar"><b>+</b><b>&minus;</b><b><i class="fa fa-refresh"></i></b><b><i class="fa fa-search"></i></b><b><i class="fa fa-filter"></i></b></span>';
        $geo = $pin(392, 305);
        break;
      case 'full_screen':
        // The city zooms in around the pin
        $wrap = ' is-zooming';
        $center = [360, 352];
        $html = '<span class="gmp-up-fs"><i class="fa fa-expand"></i></span>';
        $geo = $pin(360, 352);
        break;
      case 'clean_map':
        // Places of the base map along the streets fade out, the own pin stays
        $geo = $poi(330, 230, 'fa-cutlery') . $poi(425, 190, 'fa-shopping-bag') . $poi(298, 330, 'fa-bus') . $poi(470, 352, 'fa-coffee') . $pin(360, 262);
        $center = [360, 270];
        break;
      case 'user_location':
        $center = [355, 275];
        $geo = '<span class="gmp-up-me" style="' . $at(360, 330) . '"></span>' . $pin(470, 230, '#0e8a93') . $pin(235, 205);
        break;
      case 'markers_list':
        // The same scene as the Markers list window of Ultimate Maps (css/admin.upsell.css, "Markers list"): cards of the
        // places slide by under the map, and the pin of the card in front turns red and grows, in turn (8 s loop).
        // Still, the cards stand and the pins are teal.
        $wrap = ' is-ml';
        $center = [395, 300];
        // Pin k is lit while card k is in front: a shift of the loop, as --d (like the pins of Ultimate Maps)
        foreach ([[298, 262, 0], [392, 305, -5.33], [490, 345, -2.66]] as $p) {
          $geo .= '<span class="gmp-up-mlp" style="' . $at($p[0], $p[1]) . ';--d:' . $p[2] . '"><span class="gmp-up-pin"></span></span>';
        }
        $cards = '';
        foreach (['a', 'b', 'c', 'd', 'a', 'b'] as $ph) {
          $cards .= '<span class="gmp-up-mlc"><i class="gmp-up-mlc-ph is-' . $ph . '"></i><b></b><i></i></span>';
        }
        $html = '<span class="gmp-up-mls"><span class="gmp-up-mls-track">' . $cards . '</span></span>';
        break;
      case 'frontend_markers':
        $center = [340, 280];
        $geo = $pin(298, 250) . $pin(425, 250, '#0e8a93', 'is-drop');
        $html = '<span class="gmp-up-form"><b>Add a place</b><i></i><i class="is-short"></i><em>Submit</em></span>';
        break;
      case 'places':
        $center = [370, 320];
        $html = '<span class="gmp-up-search"><i class="fa fa-search"></i> Restaurants near me</span><span class="gmp-up-chips"><b>Cafes</b><b class="is-on">Restaurants</b><b>Hotels</b></span>';
        $geo = $pin(318, 352, '#f59e0b', 'is-pop') . $pin(392, 305, '#f59e0b', 'is-pop is-2') . $pin(425, 380, '#f59e0b', 'is-pop is-3');
        break;
      case 'traffic_layers':
        // Traffic on 5th Ave and on Market St
        $center = [360, 290];
        $svg = '<path class="gmp-up-traffic is-orange" d="M360 20V470"/><path class="gmp-up-traffic is-green" d="M-250 305H360"/><path class="gmp-up-traffic is-red" d="M360 305H650"/>';
        $html = '<span class="gmp-up-legend"><b style="--c:#22c55e">Fast</b><b style="--c:#f59e0b">Slow</b><b style="--c:#ef4444">Jam</b></span>';
        break;
      case 'kml':
        // Three KML layers from a file and a link, with the panel of the layers on the right. One 12 s scene
        // (css/admin.upsell.css, "KML layers"), the same story as in Ultimate Maps: Bars and Restaurants are switched
        // off, the link syncs, Bars comes back with two more places, Restaurants comes back. The counts are objects
        // (places and the zone, block or route). Still, it shows every layer on and synced.
        $wrap = ' has-side is-kml';
        $center = [405, 290];
        // A small pin of a layer; --d shifts it on the timeline of its layer, a new one has a ring
        $kp = function ($x, $y, $layer, $d = 0, $new = false) use ($at) {
          return '<span class="gmp-up-kp is-' . $layer . ($new ? ' is-new' : '') . '" style="' . $at($x, $y) . ';--d:' . $d . '"><i></i>' . ($new ? '<b></b>' : '') . '</span>';
        };
        // Cafés: a zone around a block and its places along the streets; Restaurants: a block; Bars: a route
        $svg = '<path class="gmp-up-kz is-cafes" d="M425 230H490V305H425Z"/><path class="gmp-up-kz is-rest" d="M360 305H425V352H360Z"/><path class="gmp-up-kline" pathLength="100" d="M360 395V352H490V369"/>';
        foreach ([[360, 200], [392, 230], [445, 230], [425, 262], [490, 250], [490, 290], [458, 305]] as $p) {
          $geo .= $kp($p[0], $p[1], 'cafes');
        }
        foreach ([[330, 230], [360, 262], [392, 305], [360, 330], [425, 330]] as $i => $p) {
          $geo .= $kp($p[0], $p[1], 'rest', $i * 0.15);
        }
        foreach ([[330, 352], [392, 352], [455, 352], [360, 380], [425, 385]] as $i => $p) {
          $geo .= $kp($p[0], $p[1], 'bars', $i * 0.15);
        }
        foreach ([[490, 330], [425, 352]] as $i => $p) {
          $geo .= $kp($p[0], $p[1], 'bars', $i * 0.2, true);
        }
        $row = function ($layer, $name, $num, $newNum = '') {
          $count = $newNum ? '<span class="gmp-up-kl-num is-two"><em class="is-old">' . $num . '</em><em class="is-new">' . $newNum . '</em><em class="is-plus">+' . ($newNum - $num) . '</em></span>' : '<span class="gmp-up-kl-num">' . $num . '</span>';
          return '<span class="gmp-up-kl-row is-' . $layer . '"><i></i><span class="gmp-up-kl-name">' . $name . '</span>' . $count . '<span class="gmp-up-tg"><i></i></span></span>';
        };
        $html =
          '<span class="gmp-up-kl">' .
          '<b class="gmp-up-kl-title">KML layers</b>' .
          '<span class="gmp-up-kl-src"><i class="fa fa-file-code-o"></i><em>cafes.kml</em></span>' .
          '<span class="gmp-up-kl-src is-url"><i class="fa fa-link"></i><em class="gmp-up-kl-url"><span>https://&hellip;</span>/bars.kml</em><i class="fa fa-refresh gmp-up-kl-sync"></i></span>' .
          '<span class="gmp-up-kl-state"><span class="is-done"><i class="fa fa-check"></i>Synced &middot; just now</span><span class="is-busy"><i class="fa fa-refresh"></i>Syncing&hellip;</span></span>' .
          $row('cafes', 'Caf&eacute;s', 8) .
          $row('rest', 'Restaurants', 6) .
          $row('bars', 'Bars', 6, 8) .
          '<span class="gmp-up-kl-foot"><i class="fa fa-clock-o"></i>Auto sync &middot; hourly</span>' .
          '</span>';
        break;
      case 'directions':
        $center = [375, 280];
        $svg = '<path class="gmp-up-route is-moving" d="' . $line([[250, 352], [425, 352], [425, 230], [490, 230], [490, 205]]) . '"/>';
        $geo = $pin(250, 352, '#0e8a93') . $pin(490, 205);
        $html = '<span class="gmp-up-iw"><b>Downtown store</b><small>Open till 21:00</small><em><i class="fa fa-location-arrow"></i> Get directions</em></span>';
        break;
      case 'print':
        $geo = $pin(318, 305);
        $html = '<span class="gmp-up-iw is-left"><b>Downtown store</b><small>12 Market St.</small><em><i class="fa fa-print"></i> Print</em></span>';
        break;
      case 'marker_period':
        $center = [390, 290];
        $geo = $pin(298, 262, '#e5484d', 'is-blink') . $pin(392, 352, '#0e8a93', 'is-blink is-2') . $pin(490, 250, '#f59e0b', 'is-blink is-3');
        $html = '<span class="gmp-up-cal"><i class="fa fa-calendar"></i> 1 &ndash; 31 Dec</span>';
        break;
      case 'csv':
        $wrap = ' is-plain';
        $html = '<span class="gmp-up-sheet"><span class="gmp-up-sheet-file"><i class="fa fa-file-excel-o"></i>maps.csv</span><i class="fa fa-long-arrow-right"></i><span class="gmp-up-sheet-rows"><span><i class="fa fa-map-marker"></i><b></b></span><span><i class="fa fa-map-marker"></i><b></b></span><span class="is-fig"><i class="fa fa-cubes"></i><b></b></span><span class="is-fig"><i class="fa fa-cubes"></i><b></b></span></span></span>';
        break;
      case 'figures':
        // A zone drawn around two blocks, a circle round a crossing and a polyline along the streets
        $center = [390, 280];
        $svg = '<path class="gmp-up-zone is-draw" pathLength="100" d="M298 230H425V305H298Z"/><circle class="gmp-up-circle" cx="490" cy="230" r="28"/><path class="gmp-up-route is-moving" d="' . $line([[250, 352], [490, 352], [490, 305], [535, 305]]) . '"/>';
        break;
      case 'heatmap':
        // The heat lies over the places with the most markers
        $center = [390, 280];
        $dots = [[425, 312], [425, 338], [410, 352], [445, 352], [398, 305], [455, 305], [298, 245], [298, 268], [318, 230], [282, 230], [490, 205], [490, 226], [508, 230]];
        $geo = '<span class="gmp-up-heat is-small" style="' . $at(500, 218) . '"></span><span class="gmp-up-heat" style="' . $at(305, 250) . '"></span><span class="gmp-up-heat is-big" style="' . $at(425, 330) . '"></span>';
        foreach ($dots as $dot) {
          $geo .= '<span class="gmp-up-dot" style="' . $at($dot[0], $dot[1]) . '"></span>';
        }
        break;
      case 'path_router':
        $center = [375, 275];
        $svg = '<path class="gmp-up-route is-moving" d="' . $line([[260, 352], [298, 352], [298, 230], [360, 230], [360, 305], [490, 305], [490, 195]]) . '"/>';
        foreach ([[260, 352], [298, 262], [360, 305], [490, 195]] as $n => $wp) {
          $geo .= '<span class="gmp-up-wp" style="' . $at($wp[0], $wp[1]) . '">' . ($n + 1) . '</span>';
        }
        break;
      case 'access_roles':
        $wrap = ' is-plain';
        $html = '<span class="gmp-up-roles"><b class="is-on"><i class="fa fa-check"></i>Administrator</b><b class="is-on is-2"><i class="fa fa-check"></i>Editor</b><b class="is-3"><i class="fa fa-check"></i>Author</b><b><i class="fa fa-check"></i>Shop manager</b></span>';
        break;
      default:
        $geo = $pin(360, 305);
    }
    $city = $this->getModule()->getModPath() . 'img/map-city.svg?ver=' . GMP_VERSION_PLUGIN . '-roads-1';
    return '<div class="gmp-up-map' . $wrap . '" style="--cx:' . $center[0] . ';--cy:' . $center[1] . '">' .
      '<div class="gmp-up-world">' .
      '<svg class="gmp-up-base" viewBox="' . implode(' ', $box) . '" aria-hidden="true" focusable="false">' .
      '<image href="' . esc_url($city) . '" x="' . $box[0] . '" y="' . $box[1] . '" width="' . $box[2] . '" height="' . $box[3] . '"/>' .
      $svg .
      '</svg>' .
      $geo .
      '</div>' .
      $html .
      '</div>';
  }
  public function getPromoTabContent($tabCode, $tabTitle, $tabDescription)
  {
    $tabCode = isset($tabCode) ? $tabCode : '';
    $tabTitle = isset($tabTitle) ? $tabTitle : '';
    $tabDescription = isset($tabDescription) ? $tabDescription : '';
    $this->assign('tabCode', $tabCode);
    $this->assign('tabTitle', $tabTitle);
    $this->assign('tabDescription', $tabDescription);
    // The feature of the tab: "shapes&utm_campaign=..." -> figures
    $medium = strtok($tabCode, '&');
    $aliases = $this->getModule()->getUpsellAliases();
    $featureKey = isset($aliases[$medium]) ? $aliases[$medium] : $medium;
    $features = $this->getModule()->getUpsellFeatures();
    $this->assign('featureKey', $featureKey);
    $this->assign('feature', isset($features[$featureKey]) ? $features[$featureKey] : null);
    return parent::getContent('adminPromoTabContent');
  }
}
