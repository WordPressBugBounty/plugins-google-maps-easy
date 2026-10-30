<?php

/**
 * Plugin Name: Easy Google Maps
 * Plugin URI: https://supsystic.com/plugins/google-maps-plugin/
 * Description: Google Maps with unlimited markers, custom icons, marker categories, clustering and map themes. Gutenberg block, Elementor widget, no coding.
 * Version: 1.16.1
 * Author: supsystic.com
 * Author URI: https://supsystic.com
 * Text Domain: google-maps-easy
 * Domain Path: /languages
 **/

/**
 * Base config constants and functions
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'functions.php';
/**
 * Connect all required core classes
 */
importClassGmp('dbGmp');
importClassGmp('installerGmp');
importClassGmp('baseObjectGmp');
importClassGmp('moduleGmp');
importClassGmp('modelGmp');
importClassGmp('viewGmp');
importClassGmp('controllerGmp');
importClassGmp('helperGmp');
importClassGmp('dispatcherGmp');
importClassGmp('fieldGmp');
importClassGmp('tableGmp');
importClassGmp('frameGmp');
/**
 * @deprecated since version 1.0.1
 */
importClassGmp('langGmp');
importClassGmp('reqGmp');
importClassGmp('uriGmp');
importClassGmp('htmlGmp');
importClassGmp('responseGmp');
importClassGmp('fieldAdapterGmp');
importClassGmp('validatorGmp');
importClassGmp('errorsGmp');
importClassGmp('utilsGmp');
importClassGmp('modInstallerGmp');
importClassGmp('installerDbUpdaterGmp');
importClassGmp('dateGmp');
/**
 * Check plugin version - maybe we need to update database, and check global errors in request
 */
installerGmp::update();
errorsGmp::init();
/**
 * Start application
 */
frameGmp::_()->parseRoute();
frameGmp::_()->init();
frameGmp::_()->exec();
installerGmp::updateIcon();

/**
 * Spooky Sale banner on the plugin admin pages
 */
if (is_admin()) {
  require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'promo' . DIRECTORY_SEPARATOR . 'spooky-sale.php';
  SupsysticSpookySale2026::register('google-maps-easy', plugin_dir_url(__FILE__) . 'promo/', [
    'proPid' => 185,
    'proUrl' => 'https://supsystic.com/plugins/google-maps-plugin/',
    'isPro' => function () {
      $promo = frameGmp::_()->getModule('supsystic_promo');
      return $promo && $promo->isPro();
    },
    'utmCampaign' => 'googlemaps',
  ]);
}

//var_dump(frameGmp::_()->getActivationErrors()); exit();
