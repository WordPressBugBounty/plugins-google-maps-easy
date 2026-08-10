<?php
class supsystic_promoViewGmp extends viewGmp
{
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
            " - this <a href='//supsystic.com/documentation/create-google-maps-api-key/' target='_blank'>article</a> is written for you and required for reading.",
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
}
