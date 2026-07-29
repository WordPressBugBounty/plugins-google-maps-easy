<?php
class supsystic_promoViewGmp extends viewGmp
{
  public function displayAdminFooter()
  {
    parent::display('adminFooter');
  }
  public function showWelcomePage()
  {
    $this->assign('askOptions', [
      1 => ['label' => 'Google'],
      2 => ['label' => 'Worgmpess.org'],
      3 => ['label' => 'Refer a friend'],
      4 => ['label' => 'Find on the web'],
      5 => ['label' => 'Other way...'],
    ]);
    $this->assign('originalPage', uriGmp::getFullUrl());
    parent::display('welcomePage');
  }
  public function getOverviewTabContent()
  {
    frameGmp::_()->getModule('templates')->loadJqueryUi();

    frameGmp::_()->getModule('templates')->loadSlimscroll();
    frameGmp::_()->addScript('admin.overview', $this->getModule()->getModPath() . 'js/admin.overview.js');
    frameGmp::_()->addStyle('admin.overview', $this->getModule()->getModPath() . 'css/admin.overview.css');
    $this->assign('mainLink', $this->getModule()->getMainLink());
    $this->assign('faqList', $this->getFaqList());
    $this->assign('serverSettings', $this->getServerSettings());
    $this->assign('news', $this->getNewsContent());
    return parent::getContent('overviewTabContent');
  }
  public function getFaqList()
  {
    return [
      __('How to create Google Maps API Key?', GMP_LANG_CODE) => sprintf(
        __(
          'Your map suddenly stopped working and you get the following error?' .
            "<blockquote style='color: gray; font-style: italic;'>Oops! Something went wrong.This page didn't load Google Maps correctly. See the JavaScript console for technical details.</blockquote>" .
            "Please check you browser console, if you'll see such error <blockquote style='color: gray; font-style: italic;'>This site has exceeded its daily quota for maps.</blockquote>" .
            " - this <a href='//supsystic.com/google-maps-api-key/' target='_blank'>article</a> is written for you and required for reading.",
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
      __('How to add map into the site content?', GMP_LANG_CODE) => sprintf(__("You can add a map in the site content via shortcode or php code. Learn more about how to do this <a href='https://supsystic.com/add-map-into-site-content/'>here</a>.", GMP_LANG_CODE), $this->getModule()->getMainLink()),
      __('How to add map in popup window?', GMP_LANG_CODE) => sprintf(
        __("You can add a map in popup window by inserting map shortcode in any popup text field. Learn more about how to do this <a href='https://supsystic.com/add-map-in-popup-window/'>here</a>.", GMP_LANG_CODE),
        $this->getModule()->getMainLink(),
      ),
      __('How to zoom and center the initial map on markers?', GMP_LANG_CODE) => sprintf(
        __(
          "There is a few different ways to zoom and centralize map. The easiest one is to drag your map using mouse - 'Draggable' option must be enabled, or with pan controller help in live preview. <a href='https://supsystic.com/how-to-zoom-and-center-the-initial-map-on-markers/'>Read more...</a>",
          GMP_LANG_CODE,
        ),
        $this->getModule()->getMainLink(),
      ),
      __('How to get PRO version of plugin for FREE?', GMP_LANG_CODE) => sprintf(
        __("You have an incredible opportunity to get PRO version for free. Make Translation of plugin! It will be amazing if you take advantage of this offer! More info you can find here <a target='_blank' href='%s'>Get PRO version of any plugin for FREE'</a>", GMP_LANG_CODE),
        $this->getModule()->getMainLink(),
      ),
      __('Translation', GMP_LANG_CODE) => sprintf(
        __(
          "All available languages are provided with the Supsystic Google Maps plugin. If your language isn't available, your plugin will be in English by default.<br /><b>Available Translations: English, Polish, German, Spanish, Russian</b><br />Translate or update a translation Google Maps WordPress plugin in your language and get a Premium license for FREE. <a target='_blank' href='%s'>Contact us</a>.",
          GMP_LANG_CODE,
        ),
        $this->getModule()->getMainLink() . '#contact',
      ),
    ];
  }
  public function getNewsContent()
  {
    $getData = wp_remote_get('https://supsystic.com/news/main.html');
    $content = '';
    if ($getData && is_array($getData) && isset($getData['response']) && isset($getData['response']['code']) && $getData['response']['code'] == 200 && isset($getData['body']) && !empty($getData['body'])) {
      $content = $getData['body'];
    } else {
      $content = sprintf(__("There was some problem while trying to retrieve our news, but you can always check all list <a target='_blank' href='%s'>here</a>.", GMP_LANG_CODE), 'https://supsystic.com/news');
    }
    return $content;
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
  public function getPromoTabContent($tabCode, $tabTitle, $tabDescription)
  {
    $tabCode = isset($tabCode) ? $tabCode : '';
    $tabTitle = isset($tabTitle) ? $tabTitle : '';
    $tabDescription = isset($tabDescription) ? $tabDescription : '';
    $this->assign('tabCode', $tabCode);
    $this->assign('tabTitle', $tabTitle);
    $this->assign('tabDescription', $tabDescription);
    return parent::getContent('adminPromoTabContent');
  }
  // public function getDiscountMsg($buyLink = '#') {
  // 	$this->assign('bundlePageLink', '//supsystic.com/all-plugins/');
  // 	$this->assign('buyLink', $buyLink);
  // 	parent::display('discountMsg');
  // }
}
