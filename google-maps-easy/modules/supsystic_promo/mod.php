<?php
class supsystic_promoGmp extends moduleGmp
{
  private $_mainLink = '';
  private $_cdnUrl = '';
  private $_specSymbols = [
    'from' => ['?', '&'],
    'to' => ['%', '^'],
  ];
  private $_minDataInStatToSend = 20; // At least 20 points in table shuld be present before send stats
  public function __construct($d)
  {
    parent::__construct($d);
    $this->getMainLink();
  }
  public function init()
  {
    parent::init();
    add_action('admin_footer', [$this, 'displayAdminFooter'], 9);
    if (is_admin()) {
      $this->checkStatisticStatus();
    }
    //$this->weLoveYou();
    dispatcherGmp::addFilter('mainAdminTabs', [$this, 'addAdminTab']);
    // dispatcherGmp::addAction('discountMsg', array($this, 'getDiscountMsg'));
    // add_action('admin_notices', array($this, 'checkAdminPromoNotices'));
    add_action('admin_notices', [$this, 'showUserApiKeyAdminNotice']);
    add_action('admin_notices', [$this, 'showActivationNotice']);
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
        'html' => sprintf(__("Please, set your own Google API key in Easy Google Maps plugin <a href='%s'>Settings</a>! More info about Maps and API keys you can find <a href='%s' target='_blank'>here</a>.", GMP_LANG_CODE), $settingsLink, '//supsystic.com/google-maps-api-key/'),
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
  // We used such methods - _encodeSlug() and _decodeSlug() - as in slug wp don't understand urlencode() functions
  private function _encodeSlug($slug)
  {
    return str_replace($this->_specSymbols['from'], $this->_specSymbols['to'], $slug);
  }
  private function _decodeSlug($slug)
  {
    return str_replace($this->_specSymbols['to'], $this->_specSymbols['from'], $slug);
  }
  public function decodeSlug($slug)
  {
    return $this->_decodeSlug($slug);
  }
  public function modifyMainAdminSlug($mainSlug)
  {
    $firstTimeLookedToPlugin = !installerGmp::isUsed();
    if ($firstTimeLookedToPlugin) {
      $mainSlug = $this->_getNewAdminMenuSlug($mainSlug);
    }
    return $mainSlug;
  }
  private function _getWelcomMessageMenuData($option, $modifySlug = true)
  {
    return array_merge($option, [
      'page_title' => __('Welcome to Supsystic Secure', GMP_LANG_CODE),
      'menu_slug' => $modifySlug ? $this->_getNewAdminMenuSlug($option['menu_slug']) : $option['menu_slug'],
      'function' => [$this, 'showWelcomePage'],
    ]);
  }
  public function addWelcomePageToMenus($options)
  {
    $firstTimeLookedToPlugin = !installerGmp::isUsed();
    if ($firstTimeLookedToPlugin) {
      foreach ($options as $i => $opt) {
        $options[$i] = $this->_getWelcomMessageMenuData($options[$i]);
      }
    }
    return $options;
  }
  private function _getNewAdminMenuSlug($menuSlug)
  {
    // We can't use "&" symbol in slug - so we used "|" symbol
    $newSlug = $this->_encodeSlug(str_replace('admin.php?page=', '', $menuSlug));
    return 'welcome-to-' . frameGmp::_()->getModule('adminmenu')->getMainSlug() . '|return=' . $newSlug;
  }
  public function addWelcomePageToMainMenu($option)
  {
    $firstTimeLookedToPlugin = !installerGmp::isUsed();
    if ($firstTimeLookedToPlugin) {
      $option = $this->_getWelcomMessageMenuData($option, false);
    }
    return $option;
  }
  public function showWelcomePage()
  {
    $this->getView()->showWelcomePage();
  }
  public function displayAdminFooter()
  {
    if (frameGmp::_()->isAdminPlugPage()) {
      $this->getView()->displayAdminFooter();
    }
  }
  private function _preparePromoLink($link, $ref = '')
  {
    if (empty($ref)) {
      $ref = 'user';
    }
    $link .= '?ref=' . $ref;
    return $link;
  }
  public function weLoveYou()
  {
    if (!frameGmp::_()->getModule(implode('', ['l', 'ic', 'e', 'ns', 'e']))) {
      //
    }
  }
  /**
   * Public shell for private method
   */
  public function preparePromoLink($link, $ref = '')
  {
    return $this->_preparePromoLink($link, $ref);
  }
  public function checkStatisticStatus()
  {
    $canSend = (int) frameGmp::_()->getModule('options')->get('send_stats');
    if ($canSend) {
      $this->getModel()->checkAndSend();
    }
  }
  public function getMinStatSend()
  {
    return $this->_minDataInStatToSend;
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
  // public function getDiscountMsg() {
  // 	if($this->isPro()
  // 		&& frameGmp::_()->getModule('options')->getActiveTab() == 'license'
  // 		&& frameGmp::_()->getModule('license')
  // 		&& frameGmp::_()->getModule('license')->getModel()->isActive()
  // 	) {
  // 		$proPluginsList = array(
  // 			'ultimate-maps-by-supsystic-pro', 'newsletters-by-supsystic-pro', 'contact-form-by-supsystic-pro', 'live-chat-pro',
  // 			'digital-publications-supsystic-pro', 'coming-soon-supsystic-pro', 'price-table-supsystic-pro', 'tables-generator-pro',
  // 			'social-share-pro', 'popup-by-supsystic-pro', 'supsystic_slider_pro', 'supsystic-gallery-pro', 'google-maps-easy-pro',
  // 			'backup-supsystic-pro',
  // 		);
  // 		$activePluginsList = get_option('active_plugins', array());
  // 		$activeProPluginsCount = 0;
  // 		foreach($activePluginsList as $actPl) {
  // 			foreach($proPluginsList as $proPl) {
  // 				if(strpos($actPl, $proPl) !== false) {
  // 					$activeProPluginsCount++;
  // 				}
  // 			}
  // 		}
  // 		if($activeProPluginsCount === 1) {
  // 			$buyLink = $this->getDiscountBuyUrl();
  // 			$this->getView()->getDiscountMsg($buyLink);
  // 		}
  // 	}
  // }
  public function getDiscountBuyUrl()
  {
    $license = frameGmp::_()->getModule('license')->getModel()->getCredentials();
    $license['key'] = md5($license['key']);
    $license = urlencode(base64_encode(implode('|', $license)));
    $plugin_code = 'google_maps_easy_pro';
    return 'https://supsystic.com/?mod=manager&pl=lms&action=applyDiscountBuyUrl&plugin_code=' . $plugin_code . '&lic=' . $license;
  }
}
