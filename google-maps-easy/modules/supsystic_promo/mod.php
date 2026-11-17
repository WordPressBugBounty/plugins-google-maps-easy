<?php
class supsystic_promoGmp extends moduleGmp
{
  private $_mainLink = '';
  private $_cdnUrl = '';
  public function __construct($d)
  {
    parent::__construct($d);
    $this->getMainLink();
  }
  public function init()
  {
    parent::init();
    $this->registerDeactivationDialog();
    dispatcherGmp::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    // dispatcherGmp::addAction('discountMsg', array($this, 'getDiscountMsg'));
    // add_action('admin_notices', array($this, 'checkAdminPromoNotices'));
    add_action('admin_notices', [$this, 'showUserApiKeyAdminNotice']);
    add_action('admin_notices', [$this, 'showActivationNotice']);
    if (is_admin() && frameGmp::_()->isAdminPlugOptsPage()) {
      // PRO feature windows of the free version: decided late, once every module (PRO among them) is loaded
      add_action('admin_enqueue_scripts', [$this, 'enqueueUpsellAssets'], 101);
      add_action('admin_footer', [$this, 'printUpsellWindows']);
    }
    $this->initReview();
  }
  /**
   * The review request (classes in review.php of this module): asks for a review on WordPress.org once a map does
   * something useful for the site. PRO (or any add-on) reports its own moments with do_action('gmp_review_moment', ...).
   */
  public function initReview()
  {
    require_once $this->getModDir() . 'review.php';
    gmpReviewGmp::getInstance()->init();
  }
  public function enqueueUpsellAssets()
  {
    if ($this->isPro()) {
      return;
    }
    $version = GMP_VERSION_PLUGIN . '-up-8';
    wp_enqueue_style('gmp-upsell', $this->getModPath() . 'css/admin.upsell.css', [], $version);
    wp_enqueue_script('gmp-upsell', $this->getModPath() . 'js/admin.upsell.js', ['jquery'], $version, true);
    wp_localize_script('gmp-upsell', 'gmpUpsellTexts', [
      'readMore' => __('Read more', GMP_LANG_CODE),
    ]);
  }
  public function printUpsellWindows()
  {
    if ($this->isPro()) {
      return;
    }
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the template escapes its values
    echo $this->getView()->getUpsellWindows();
  }
  /**
   * Old keys (the utm_medium of the "PRO option" links) => the window of their feature.
   * @return array
   */
  public function getUpsellAliases()
  {
    return [
      'type_control_position' => 'control_positions',
      'zoom_control_position' => 'control_positions',
      'street_view_control_position' => 'control_positions',
      'enable_custom_map_controls' => 'custom_controls',
      'enable_full_screen_btn' => 'full_screen',
      'hide_poi' => 'clean_map',
      'hide_countries' => 'clean_map',
      'center_on_cur_user_pos' => 'user_location',
      'marker_list_' => 'markers_list',
      'frontend_add_markers' => 'frontend_markers',
      'places_toolbar' => 'places',
      'trafic_layer' => 'traffic_layers',
      'transit_layer' => 'traffic_layers',
      'bicycling_layer' => 'traffic_layers',
      'add_kml_layers' => 'kml',
      'enable_directions_btn' => 'directions',
      'enable_infownd_print_btn' => 'print',
      'marker_period_from' => 'marker_period',
      'marker_period_to' => 'marker_period',
      'csv_import_export' => 'csv',
      'shapes' => 'figures',
    ];
  }
  /**
   * The PRO features with a window: group (for "Also in PRO"), icon, chip, title, text, points, uses and the Get PRO link.
   * @return array
   */
  public function getUpsellFeatures()
  {
    $features = [
      'control_positions' => [
        'group' => 'map',
        'icon' => 'fa-arrows',
        'chip' => __('Control positions', GMP_LANG_CODE),
        'title' => __('Put every map control where you want it', GMP_LANG_CODE),
        'text' => __('Move the map type, zoom and Street View controls to any corner or edge of the map.', GMP_LANG_CODE),
        'points' => [__('Twelve places around the map for each control', GMP_LANG_CODE), __('Keep the important part of the map free', GMP_LANG_CODE), __('Match the controls to your page layout', GMP_LANG_CODE)],
        'uses' => [__('Full-width maps', GMP_LANG_CODE), __('Store locators', GMP_LANG_CODE), __('Landing pages', GMP_LANG_CODE)],
      ],
      'custom_controls' => [
        'group' => 'map',
        'icon' => 'fa-th',
        'chip' => __('Custom controls', GMP_LANG_CODE),
        'title' => __('Your own controls over the map', GMP_LANG_CODE),
        'text' => __('A set of buttons in your colors: zoom, reset, search by address and category filters right on the map.', GMP_LANG_CODE),
        'points' => [__('Zoom, reset and search buttons', GMP_LANG_CODE), __('Category filters on the map', GMP_LANG_CODE), __('Your own colors, size and position', GMP_LANG_CODE), __('Search markers by address, name or category', GMP_LANG_CODE)],
        'uses' => [__('Brand sites', GMP_LANG_CODE), __('Store locators', GMP_LANG_CODE), __('Directories', GMP_LANG_CODE)],
      ],
      'full_screen' => [
        'group' => 'map',
        'icon' => 'fa-expand',
        'chip' => __('Full screen', GMP_LANG_CODE),
        'title' => __('Open the map full screen', GMP_LANG_CODE),
        'text' => __('A button that opens the map over the whole screen, so visitors can explore it in detail.', GMP_LANG_CODE),
        'points' => [__('One click to full screen and back', GMP_LANG_CODE), __('Works on phones and tablets', GMP_LANG_CODE), __('Markers and info windows stay as they are', GMP_LANG_CODE)],
        'uses' => [__('Big areas', GMP_LANG_CODE), __('Travel routes', GMP_LANG_CODE), __('Event venues', GMP_LANG_CODE)],
      ],
      'clean_map' => [
        'group' => 'map',
        'icon' => 'fa-eye-slash',
        'chip' => __('Clean map', GMP_LANG_CODE),
        'title' => __('Show only what matters on the map', GMP_LANG_CODE),
        'text' => __('Hide the shops, cafes and other places Google shows, and hide the countries you do not need.', GMP_LANG_CODE),
        'points' => [__('Hide points of interest of Google', GMP_LANG_CODE), __('Hide chosen countries', GMP_LANG_CODE), __('Your markers stand out', GMP_LANG_CODE)],
        'uses' => [__('Store locators', GMP_LANG_CODE), __('Dealer maps', GMP_LANG_CODE), __('Delivery areas', GMP_LANG_CODE)],
      ],
      'map_themes' => [
        'group' => 'map',
        'icon' => 'fa-paint-brush',
        'chip' => __('300+ map themes', GMP_LANG_CODE),
        'title' => __('Give the map the colors of your brand', GMP_LANG_CODE),
        'text' => __('More than 300 ready map styles: dark, light, muted, retro and many more, applied to the map in one click.', GMP_LANG_CODE),
        'points' => [__('300+ styles instead of the starter set', GMP_LANG_CODE), __('A live preview in the map editor', GMP_LANG_CODE), __('Works with markers, clusters and info windows', GMP_LANG_CODE)],
        'uses' => [__('Brand sites', GMP_LANG_CODE), __('Dark themes', GMP_LANG_CODE), __('Landing pages', GMP_LANG_CODE)],
      ],
      'static_map' => [
        'group' => 'map',
        'icon' => 'fa-picture-o',
        'chip' => __('Static map', GMP_LANG_CODE),
        'title' => __('Show the map as a fast static image', GMP_LANG_CODE),
        'text' => __('A picture of your map with its markers instead of the live map: the page loads faster and costs fewer Google requests.', GMP_LANG_CODE),
        'points' => [__('One image request instead of the full map', GMP_LANG_CODE), __('Markers are drawn on the image', GMP_LANG_CODE), __('Lower Google Maps costs on busy pages', GMP_LANG_CODE)],
        'uses' => [__('Contact pages', GMP_LANG_CODE), __('Footers', GMP_LANG_CODE), __('Busy sites', GMP_LANG_CODE)],
      ],
      'user_location' => [
        'group' => 'markers',
        'icon' => 'fa-location-arrow',
        'chip' => __('Visitor location', GMP_LANG_CODE),
        'title' => __('Center the map on the visitor', GMP_LANG_CODE),
        'text' => __('The map opens where the visitor is and shows the nearest of your places first.', GMP_LANG_CODE),
        'points' => [__('Asks the browser for the location', GMP_LANG_CODE), __('Your own icon for the visitor', GMP_LANG_CODE), __('Falls back to your map center', GMP_LANG_CODE)],
        'uses' => [__('Store locators', GMP_LANG_CODE), __('Delivery', GMP_LANG_CODE), __('Services nearby', GMP_LANG_CODE)],
      ],
      'markers_list' => [
        'group' => 'markers',
        'icon' => 'fa-list-ul',
        'chip' => __('Markers list', GMP_LANG_CODE),
        'title' => __('Show your markers as a list or slider', GMP_LANG_CODE),
        'text' => __('Cards of your places in a slider, list or table next to the map. A click on a card opens its marker and info window.', GMP_LANG_CODE),
        'points' => [__('Horizontal and vertical photo sliders', GMP_LANG_CODE), __('Name, address and description on every card', GMP_LANG_CODE), __('Autoplay and a loop', GMP_LANG_CODE), __('A table visitors can search', GMP_LANG_CODE), __('A click on a card opens its marker', GMP_LANG_CODE)],
        'uses' => [__('Store locators', GMP_LANG_CODE), __('Real estate', GMP_LANG_CODE), __('Tours', GMP_LANG_CODE)],
      ],
      'frontend_markers' => [
        'group' => 'markers',
        'icon' => 'fa-map-pin',
        'chip' => __('Visitor markers', GMP_LANG_CODE),
        'title' => __('Let visitors add markers', GMP_LANG_CODE),
        'text' => __('Visitors put their own places on the map with a title, a description and a category.', GMP_LANG_CODE),
        'points' => [__('A form right on your page', GMP_LANG_CODE), __('Only for logged-in users, if you like', GMP_LANG_CODE), __('A limit of markers per visitor', GMP_LANG_CODE), __('Edit and delete their own markers', GMP_LANG_CODE)],
        'uses' => [__('Communities', GMP_LANG_CODE), __('Events', GMP_LANG_CODE), __('Reports and sightings', GMP_LANG_CODE)],
      ],
      'places' => [
        'group' => 'markers',
        'icon' => 'fa-search',
        'chip' => __('Places search', GMP_LANG_CODE),
        'title' => __('Search places around the map', GMP_LANG_CODE),
        'text' => __('A toolbar to find restaurants, hotels, shops and other places of Google near your locations.', GMP_LANG_CODE),
        'points' => [__('Place types chosen by you', GMP_LANG_CODE), __('A radius slider', GMP_LANG_CODE), __('Results as markers on the map', GMP_LANG_CODE)],
        'uses' => [__('Hotels', GMP_LANG_CODE), __('Real estate', GMP_LANG_CODE), __('Travel guides', GMP_LANG_CODE)],
      ],
      'marker_period' => [
        'group' => 'markers',
        'icon' => 'fa-calendar',
        'chip' => __('Display periods', GMP_LANG_CODE),
        'title' => __('Show markers and figures only on their dates', GMP_LANG_CODE),
        'text' => __('Each marker or figure appears on the map on its start date and goes away by itself after its end date.', GMP_LANG_CODE),
        'points' => [__('A start and an end for each marker', GMP_LANG_CODE), __('The same for figures', GMP_LANG_CODE), __('No need to come back and delete them', GMP_LANG_CODE)],
        'uses' => [__('Events', GMP_LANG_CODE), __('Seasonal offers', GMP_LANG_CODE), __('Pop-up stores', GMP_LANG_CODE)],
      ],
      'csv' => [
        'group' => 'markers',
        'icon' => 'fa-file-excel-o',
        'chip' => __('CSV import', GMP_LANG_CODE),
        'title' => __('Import and export maps, markers and figures', GMP_LANG_CODE),
        'text' => __('Bring hundreds of markers and figures in from a spreadsheet and move your maps between sites.', GMP_LANG_CODE),
        'points' => [__('Markers and figures from CSV files', GMP_LANG_CODE), __('Maps with all their settings', GMP_LANG_CODE), __('Back up and move to another site', GMP_LANG_CODE)],
        'uses' => [__('Large store lists', GMP_LANG_CODE), __('Agencies', GMP_LANG_CODE), __('Migrations', GMP_LANG_CODE)],
      ],
      'traffic_layers' => [
        'group' => 'layers',
        'icon' => 'fa-road',
        'chip' => __('Traffic layers', GMP_LANG_CODE),
        'title' => __('Show live traffic, transit and bike lanes', GMP_LANG_CODE),
        'text' => __('Turn on the traffic, public transport and bicycle layers of Google for any map.', GMP_LANG_CODE),
        'points' => [__('Live traffic in color', GMP_LANG_CODE), __('Public transport lines', GMP_LANG_CODE), __('Bicycle routes and lanes', GMP_LANG_CODE)],
        'uses' => [__('Offices', GMP_LANG_CODE), __('Events', GMP_LANG_CODE), __('City guides', GMP_LANG_CODE)],
      ],
      'kml' => [
        'group' => 'layers',
        'icon' => 'fa-file-code-o',
        'chip' => __('KML layers', GMP_LANG_CODE),
        'title' => __('Put your KML files on the map', GMP_LANG_CODE),
        'text' => __('Layers from KML files or links: points, zones and routes. Visitors switch the layers on and off, and a linked file syncs on its own.', GMP_LANG_CODE),
        'points' => [__('Several KML files or links on one map', GMP_LANG_CODE), __('Visitors turn layers on and off', GMP_LANG_CODE), __('Import placemarks as markers and figures', GMP_LANG_CODE), __('Hourly sync with the linked file', GMP_LANG_CODE)],
        'uses' => [__('City guides', GMP_LANG_CODE), __('Delivery zones', GMP_LANG_CODE), __('Trails', GMP_LANG_CODE)],
      ],
      'figures' => [
        'group' => 'layers',
        'icon' => 'fa-cubes',
        'chip' => __('Figures', GMP_LANG_CODE),
        'title' => __('Draw zones, lines and circles', GMP_LANG_CODE),
        'text' => __('Polygons, polylines and circles with their own colors, titles and descriptions with photos, video and links.', GMP_LANG_CODE),
        'points' => [__('Polygons, polylines and circles', GMP_LANG_CODE), __('Colors, borders and transparency', GMP_LANG_CODE), __('A description window like the markers', GMP_LANG_CODE), __('Display periods', GMP_LANG_CODE)],
        'uses' => [__('Delivery areas', GMP_LANG_CODE), __('Service zones', GMP_LANG_CODE), __('Routes', GMP_LANG_CODE)],
      ],
      'heatmap' => [
        'group' => 'layers',
        'icon' => 'fa-fire',
        'chip' => __('Heatmap', GMP_LANG_CODE),
        'title' => __('Show where things happen most', GMP_LANG_CODE),
        'text' => __('A heatmap layer turns many points into smooth colors: low density in cool colors, high density in warm ones.', GMP_LANG_CODE),
        'points' => [__('Points added right on the map', GMP_LANG_CODE), __('Radius, opacity and your own gradient', GMP_LANG_CODE), __('Works together with markers', GMP_LANG_CODE)],
        'uses' => [__('Sales coverage', GMP_LANG_CODE), __('Events and visits', GMP_LANG_CODE), __('Research', GMP_LANG_CODE)],
      ],
      'path_router' => [
        'group' => 'layers',
        'icon' => 'fa-random',
        'chip' => __('Path router', GMP_LANG_CODE),
        'title' => __('Build a route through your points', GMP_LANG_CODE),
        'text' => __('A route between several points by car, on foot or by bike, in your order or the shortest one Google finds.', GMP_LANG_CODE),
        'points' => [__('Several stops in one route', GMP_LANG_CODE), __('Driving, walking, cycling or transit', GMP_LANG_CODE), __('Your order or the shortest one', GMP_LANG_CODE)],
        'uses' => [__('Tours', GMP_LANG_CODE), __('Deliveries', GMP_LANG_CODE), __('Running and cycling routes', GMP_LANG_CODE)],
      ],
      'directions' => [
        'group' => 'layers',
        'icon' => 'fa-location-arrow',
        'chip' => __('Directions', GMP_LANG_CODE),
        'title' => __('Give visitors directions to your places', GMP_LANG_CODE),
        'text' => __('A Get directions button in the info window draws the way from the visitor to the marker with steps and distance.', GMP_LANG_CODE),
        'points' => [__('From the visitor or any address', GMP_LANG_CODE), __('Driving, walking, cycling or transit', GMP_LANG_CODE), __('Alternative routes and the steps of the way', GMP_LANG_CODE)],
        'uses' => [__('Stores', GMP_LANG_CODE), __('Restaurants', GMP_LANG_CODE), __('Event venues', GMP_LANG_CODE)],
      ],
      'print' => [
        'group' => 'layers',
        'icon' => 'fa-print',
        'chip' => __('Print', GMP_LANG_CODE),
        'title' => __('Let visitors print a place', GMP_LANG_CODE),
        'text' => __('A print button in the info window prints the marker with its description and the map.', GMP_LANG_CODE),
        'points' => [__('One click from the info window', GMP_LANG_CODE), __('The map and the description together', GMP_LANG_CODE), __('Handy for directions on paper', GMP_LANG_CODE)],
        'uses' => [__('Venues', GMP_LANG_CODE), __('Tours', GMP_LANG_CODE), __('Older visitors', GMP_LANG_CODE)],
      ],
      'access_roles' => [
        'group' => 'site',
        'icon' => 'fa-users',
        'chip' => __('Access by role', GMP_LANG_CODE),
        'title' => __('Let your team work with maps', GMP_LANG_CODE),
        'text' => __('Choose the user roles that can use the plugin in the admin area, not only administrators.', GMP_LANG_CODE),
        'points' => [__('Any WordPress role', GMP_LANG_CODE), __('Editors manage maps without full admin rights', GMP_LANG_CODE), __('Change it any time', GMP_LANG_CODE)],
        'uses' => [__('Agencies', GMP_LANG_CODE), __('Editorial teams', GMP_LANG_CODE), __('Franchises', GMP_LANG_CODE)],
      ],
    ];
    foreach ($features as $key => $feature) {
      $features[$key]['url'] = $this->generateMainLink('utm_source=plugin&utm_medium=upsell_' . $key . '&utm_campaign=googlemaps');
    }
    return $features;
  }
  /**
   * One-time notice shown right after the plugin gets activated, pointing
   * straight at the API key setup step. Self-clearing: shown once, then the
   * flag set in utilsGmp::activatePlugin() is removed.
   */
  public function showActivationNotice()
  {
    if (!current_user_can('manage_options') || !get_option('gmp_show_activation_notice')) {
      return;
    }
    delete_option('gmp_show_activation_notice');
    $onboardingUrl = frameGmp::_()->getModule('options')->getTabUrl('settings') . '&gmp_onboarding=1#user_api_key';
    printf(
      '<div class="notice notice-success is-dismissible"><p><b>%1$s</b> %2$s</p></div>',
      esc_html__('Easy Google Maps is activated.', GMP_LANG_CODE),
      sprintf(__("<a href='%s'>Connect your Google Maps API key</a> to create your first map.", GMP_LANG_CODE), esc_url($onboardingUrl)),
    );
  }
  function showUserApiKeyAdminNotice()
  {
    if (!frameGmp::_()->isAdminPlugOptsPage()) {
      // Our notices - only for our plugin pages for now
      return;
    }
    if ((int) frameGmp::_()->getModule('options')->get('hide_user_api_key_msg')) {
      // User already dismissed this notice for good
      return;
    }
    $class = 'supsystic-admin-notice';
    $settingsLink = frameGmp::_()->getModule('options')->getTabUrl('settings') . '&gmp_onboarding=1#user_api_key';
    $notices = [
      'user_api_key_msg' => [
        'class' => 'updated notice is-dismissible ' . $class,
        'html' => sprintf(__("Please, set your own Google API key in Easy Google Maps plugin <a href='%s'>Settings</a>! More info about Maps and API keys you can find <a href='%s' target='_blank'>here</a>.", GMP_LANG_CODE), $settingsLink, 'https://supsystic.com/documentation/create-google-maps-api-key/'),
        'mod' => 'options',
      ],
    ];

    foreach ($notices as $key => $notice) {
      if (
        frameGmp::_()
          ->getModule($notice['mod'])
          ->get(substr($key, 0, -4))
      ) {
        unset($notices[$key]);
        continue;
      }
    }
    foreach ($notices as $key => $notice) {
      printf('<div class="%1$s" data-code="%2$s"><p>%3$s</p></div>', $notice['class'], esc_attr($key), $notice['html']);
    }
    if (!empty($notices)) {
      printf(
        '<div class="supsystic-admin-notice gmp-api-key-popup-overlay" data-code="user_api_key_msg" style="position:fixed;z-index:100000;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;">' .
          '<div style="background:#fff;max-width:480px;width:90%%;padding:24px 28px;border-radius:4px;box-shadow:0 5px 25px rgba(0,0,0,.3);">' .
          '<h2 style="margin-top:0;">%1$s</h2>' .
          '<p>%2$s</p>' .
          '<p style="margin-top:20px;">' .
          '<a href="%3$s" class="button button-primary">%4$s</a>&nbsp; ' .
          '<button type="button" class="button" data-statistic-code="hide" onclick="jQuery(this).closest(\'.gmp-api-key-popup-overlay\').hide();">%5$s</button>' .
          '</p>' .
          '</div>' .
          '</div>',
        esc_html__('Connect your Google Maps API key', GMP_LANG_CODE),
        esc_html__("You haven't set up your own Google Maps API key yet. Without it maps may stop working or show errors once the shared default key hits its limits.", GMP_LANG_CODE),
        esc_attr($settingsLink),
        esc_html__('Connect API key', GMP_LANG_CODE),
        esc_html__("Hide it and don't show again", GMP_LANG_CODE),
      );
    }
  }
  public function checkAdminPromoNotices()
  {
    if (!frameGmp::_()->isAdminPlugOptsPage()) {
      // Our notices - only for our plugin pages for now
      return;
    }
    $notices = [];
    // Start usage
    $startUsage = (int) frameGmp::_()->getModule('options')->get('start_usage');
    $currTime = time();
    $day = 24 * 3600;
    if ($startUsage) {
      // Already saved
      $rateMsg = sprintf(__("<h3>Hey, I noticed you just use %s over a week - that's awesome!</h3><p>Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.</p>", GMP_LANG_CODE), GMP_WP_PLUGIN_NAME);
      $rateMsg .=
        '<p><a href="https://wordpress.org/support/view/plugin-reviews/google-maps-easy?rate=5#postform" target="_blank" class="button button-primary" data-statistic-code="done">' .
        __('Ok, you deserve it', GMP_LANG_CODE) .
        '</a>
			<a href="#" class="button" data-statistic-code="later">' .
        __('Nope, maybe later', GMP_LANG_CODE) .
        '</a>
			<a href="#" class="button" data-statistic-code="hide">' .
        __('I already did', GMP_LANG_CODE) .
        '</a></p>';
      // $checkOtherPlugins = '<p>'
      // 	. sprintf(__("Check out <a href='%s' target='_blank' class='button button-primary' data-statistic-code='hide'>our other Plugins</a>! Years of experience in WordPress plugins developers made that list unbreakable!", GMP_LANG_CODE), frameGmp::_()->getModule('options')->getTabUrl('featured-plugins'))
      // . '</p>';
      $notices = [
        'rate_msg' => ['html' => $rateMsg, 'show_after' => 7 * $day],
        // 'check_other_plugs_msg' => array('html' => $checkOtherPlugins, 'show_after' => 1 * $day),
      ];
      // Wait for next week - when icons will be ready
      if (!class_exists('frameGmp')) {
        $ultimateMapsInstallUrl = admin_url('plugin-install.php?tab=search&type=term&s=Ultimate+Maps+by+Supsystic');
        $ultimateMapsMsg =
          '<p>' .
          sprintf(
            __("Tired from Google Maps and it's pricings? We developed <b>Free Maps alternative for You</b> - <a href='%s' target='_blank'>Ultimate Maps by Supsystic</a>! Just try it in <a href='%s' target='_blank'>few clicks</a>!", GMP_LANG_CODE),
            $ultimateMapsInstallUrl,
            $ultimateMapsInstallUrl,
          ) .
          '</p>';
        $notices['ultimate_maps_promo'] = ['html' => $ultimateMapsMsg, 'show_after' => 1 * $day];
      }
      foreach ($notices as $nKey => $n) {
        if ($currTime - $startUsage <= $n['show_after']) {
          unset($notices[$nKey]);
          continue;
        }
        $done = (int) frameGmp::_()
          ->getModule('options')
          ->get('done_' . $nKey);
        if ($done) {
          unset($notices[$nKey]);
          continue;
        }
        $hide = (int) frameGmp::_()
          ->getModule('options')
          ->get('hide_' . $nKey);
        if ($hide) {
          unset($notices[$nKey]);
          continue;
        }
        $later = (int) frameGmp::_()
          ->getModule('options')
          ->get('later_' . $nKey);
        if ($later && $currTime - $later <= 2 * $day) {
          // remember each 2 days
          unset($notices[$nKey]);
          continue;
        }
      }
    } else {
      frameGmp::_()->getModule('options')->getModel()->save('start_usage', $currTime);
    }
    if (!empty($notices)) {
      $html = '';
      foreach ($notices as $nKey => $n) {
        $this->getModel()->saveUsageStat($nKey . '.' . 'show', true);
        $html .= '<div class="updated notice is-dismissible supsystic-admin-notice" data-code="' . esc_attr($nKey) . '">' . $n['html'] . '</div>';
      }
      echo htmlGmp::wpKsesHtml($html);
    }
  }
  public function addAdminTab($tabs)
  {
    $tabs['overview'] = [
      'label' => __('Overview', GMP_LANG_CODE),
      'callback' => [$this, 'getOverviewTabContent'],
      'fa_icon' => 'fa-info',
      'sort_order' => 5,
    ];
    return $tabs;
  }
  public function getOverviewTabContent()
  {
    return $this->getView()->getOverviewTabContent();
  }
  private function _preparePromoLink($link, $ref = '')
  {
    if (empty($ref)) {
      $ref = 'user';
    }
    $link .= '?ref=' . $ref;
    return $link;
  }
  /**
   * Public shell for private method
   */
  public function preparePromoLink($link, $ref = '')
  {
    return $this->_preparePromoLink($link, $ref);
  }
  public function getMainLink()
  {
    if (empty($this->_mainLink)) {
      $affiliateQueryString = '';
      $this->_mainLink = 'https://supsystic.com/plugins/google-maps-plugin/' . $affiliateQueryString;
    }
    return $this->_mainLink;
  }
  public function isPro()
  {
    return frameGmp::_()->getModule('add_map_options') ? true : false;
  }
  public function generateMainLink($params = '')
  {
    $mainLink = $this->getMainLink();
    if (!empty($params)) {
      return $mainLink . (strpos($mainLink, '?') ? '&' : '?') . $params;
    }
    return $mainLink;
  }
  public function addPromoMapTabs()
  {
    $tabs = [];
    $descirption['figures'] = 'With Figures Feature, you can create polygon, polyline and circle shapes on your map with an easy to use interface. Provide a Figure title and description, using text, photos, videos and links for the overlay.';
    $descirption['heatmap'] = 'Heatmap is an extremely common map visualization type, especially prevalent in weather, travel, fitness and social areas. Use the points to display the relative density of the points on the map as a smoothly varying set of colours, depend on low and high density.';
    $descirption['path_router'] = 'This is an easy way to build route between different points, choose travel mod, optimize path by Google solution of the traveling salesperson problem, or select your order of route.';
    if (!$this->isPro()) {
      $tabs['gmpShapeTab'] = [
        'label' => __('Figures', GMP_LANG_CODE),
        'content' => $this->getView()->getPromoTabContent('shapes&utm_campaign=googlemaps', 'Figures', $descirption['figures']),
        'promo' => true,
      ];
      $tabs['gmpHeatmapTab'] = [
        'label' => __('Heatmap', GMP_LANG_CODE),
        'content' => $this->getView()->getPromoTabContent('heatmap&utm_campaign=googlemaps', 'Heatmap Layer', $descirption['heatmap']),
        'promo' => true,
      ];
      $tabs['gmpRouterTab'] = [
        'label' => __('Path Router', GMP_LANG_CODE),
        'content' => $this->getView()->getPromoTabContent('path_router&utm_campaign=googlemaps', 'Path Router', $descirption['path_router']),
        'promo' => true,
      ];
    }
    return $tabs;
  }

  /**
   * Why the plugin is deactivated: the short dialog shared by the Supsystic plugins (deactivation-v1/deactivation.php
   * in this folder says what is sent and when). Nothing is sent unless the user sends the answer.
   */
  public function registerDeactivationDialog()
  {
    if (!is_admin()) {
      return;
    }
    require_once __DIR__ . '/deactivation-v1/deactivation.php';
    Supsystic_Deactivation_V1::register([
      'slug' => 'google-maps-easy',
      'name' => 'Easy Google Maps',
      'file' => dirname(__DIR__, 2) . '/gmp.php',
      'tables' => ['gmp_modules', 'gmp_options', 'gmp_maps'],
      'pro' => [
        'slug' => 'google-maps-easy-pro',
        'name' => 'Easy Google Maps PRO',
        'email' => function () {
          $license = class_exists('frameGmp') ? frameGmp::_()->getModule('license') : null;
          return $license ? (string) $license->getModel()->getEmail() : '';
        },
      ],
    ]);
  }
}
