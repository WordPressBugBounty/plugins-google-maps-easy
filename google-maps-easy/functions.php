<?php
/**
 * Set first leter in a string as UPPERCASE
 * @param string $str string to modify
 * @return string string with first Uppercase letter
 */
if (!function_exists('strFirstUp')) {
  function strFirstUp($str)
  {
    return strtoupper(substr($str, 0, 1)) . strtolower(substr($str, 1, strlen($str)));
  }
}
/**
 * Deprecated - class must be created
 */
if (!function_exists('dateToTimestampGmp')) {
  function dateToTimestampGmp($date)
  {
    if (empty($a)) {
      return false;
    }
    $a = explode(GMP_DATE_DL, $date);
    return mktime(0, 0, 0, $a[1], $a[0], $a[2]);
  }
}
/**
 * Generate random string name
 * @param int $lenFrom min len
 * @param int $lenTo max len
 * @return string random string with length from $lenFrom to $lenTo
 */
if (!function_exists('getRandName')) {
  function getRandName($lenFrom = 6, $lenTo = 9)
  {
    $res = '';
    $len = mt_rand($lenFrom, $lenTo);
    if ($len) {
      for ($i = 0; $i < $len; $i++) {
        $res .= chr(mt_rand(97, 122)); /*rand symbol from a to z*/
      }
    }
    return $res;
  }
}
if (!function_exists('importGmp')) {
  function importGmp($path)
  {
    if (file_exists($path)) {
      require $path;
      return true;
    }
    return false;
  }
}
if (!function_exists('setDefaultParams')) {
  function setDefaultParams($params, $default)
  {
    foreach ($default as $k => $v) {
      $params[$k] = isset($params[$k]) ? $params[$k] : $default[$k];
    }
    return $params;
  }
}
if (!function_exists('importClassGmp')) {
  function importClassGmp($class, $path = '')
  {
    if (!class_exists($class)) {
      if (!$path) {
        $classFile = $class;
        if (strpos(strtolower($classFile), GMP_CODE) !== false) {
          $classFile = preg_replace('/' . GMP_CODE . '/i', '', $classFile);
        }
        $path = GMP_CLASSES_DIR . $classFile . '.php';
      }
      return importGmp($path);
    } else {
      //If such class already exist - let's check does this is our plugin class or someone else
      /*if(class_exists('ReflectionClass')) {   //ReflectionClass supported begining from php5
                $reflection = new ReflectionClass($class);
                $classFile = $reflection->getFileName();
                if(strpos($classFile, GMP_DIR) === false) {   //Class is not in our plugin directory
                    $conflictWith = substr($classFile, strpos($classFile, 'plugins') + strlen('plugins'. DS));
                    $conflictWith = substr($conflictWith, 0, strpos($conflictWith, DS));
                    $plugins = get_option('active_plugins');
                    if(!empty($plugins)) {
                        for($i = 0; $i < count($plugins); $i++) {
                            if(strpos($plugins[$i], GMP_PLUG_NAME) !== false) {   //Let's remove our plugin from list of active plugins
                                unset($plugins[$i]);
                            }
                        }
                        update_option( 'active_plugins', $plugins );
                    }
                    exit('Sorry, but we have conflict with class name <b style="color: red;">'. $class. '</b> in one of your already installed plugins <b style="color: red;">'. $conflictWith. '</b> located at '. $classFile. '. This means that you can not have both two plugins at one time.');
                }
            }*/
    }
    return false;
  }
}
/**
 * Check if class name exist with prefix or not
 * @param strin $class preferred class name
 * @return string existing class name
 */
if (!function_exists('toeGetClassNameGmp')) {
  function toeGetClassNameGmp($class)
  {
    $className = '';
    if (class_exists($class . strFirstUp(GMP_CODE))) {
      $className = $class . strFirstUp(GMP_CODE);
    } elseif (class_exists(GMP_CLASS_PREFIX . $class)) {
      $className = GMP_CLASS_PREFIX . $class;
    } else {
      $className = $class;
    }
    return $className;
  }
}
/**
 * Create object of specified class
 * @param string $class class that you want to create
 * @param array $params array of arguments for class __construct function
 * @return object new object of specified class
 */
if (!function_exists('toeCreateObjGmp')) {
  function toeCreateObjGmp($class, $params)
  {
    $className = toeGetClassNameGmp($class);
    $obj = null;
    if (class_exists('ReflectionClass')) {
      $reflection = new ReflectionClass($className);
      try {
        $obj = $reflection->newInstanceArgs($params);
      } catch (ReflectionException $e) {
        // If class have no constructor
        $obj = $reflection->newInstanceArgs();
      }
    } else {
      $obj = new $className();
      call_user_func_array([$obj, '__construct'], $params);
    }
    return $obj;
  }
}
/**
 * Redirect user to specified location. Be advised that it should redirect even if headers alredy sent.
 * @param string $url where page must be redirected
 */
if (!function_exists('redirectGmp')) {
  function redirectGmp($url)
  {
    if (headers_sent()) {
      echo '<script type="text/javascript"> document.location.href = "' . esc_attr($url) . '"; </script>';
    } else {
      header('Location: ' . esc_attr($url));
    }
    exit();
  }
}
if (!function_exists('in_array_array')) {
  function in_array_array($needle, $haystack)
  {
    if (is_array($needle)) {
      foreach ($needle as $n) {
        if (in_array($n, $haystack)) {
          return true;
        }
      }
      return false;
    } else {
      return in_array($needle, $haystack);
    }
  }
}
if (!function_exists('json_encode_utf_normal')) {
  function json_encode_utf_normal($value)
  {
    if (is_int($value)) {
      return (string) $value;
    } elseif (is_string($value)) {
      $value = str_replace(['\\', '/', '"', "\r", "\n", '\b', "\f", "\t"], ['\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'], $value);
      $convmap = [0x80, 0xffff, 0, 0xffff];
      $result = '';
      for ($i = strlen($value) - 1; $i >= 0; $i--) {
        $mb_char = substr($value, $i, 1);
        $result = $mb_char . $result;
      }
      return '"' . $result . '"';
    } elseif (is_float($value)) {
      return str_replace(',', '.', $value);
    } elseif (is_null($value)) {
      return 'null';
    } elseif (is_bool($value)) {
      return $value ? 'true' : 'false';
    } elseif (is_array($value)) {
      $with_keys = false;
      $n = count($value);
      for ($i = 0, reset($value); $i < $n; $i++, next($value)) {
        if (key($value) !== $i) {
          $with_keys = true;
          break;
        }
      }
    } elseif (is_object($value)) {
      $with_keys = true;
    } else {
      return '';
    }
    $result = [];
    if ($with_keys) {
      foreach ($value as $key => $v) {
        $result[] = json_encode_utf_normal((string) $key) . ':' . json_encode_utf_normal($v);
      }
      return '{' . implode(',', $result) . '}';
    } else {
      foreach ($value as $key => $v) {
        $result[] = json_encode_utf_normal($v);
      }
      return '[' . implode(',', $result) . ']';
    }
  }
}
/**
 * Prepares the params values to store into db
 *
 * @param array $d $_POST array
 * @return array
 */
if (!function_exists('prepareParamsGmp')) {
  function prepareParamsGmp(&$d = [], &$options = [])
  {
    if (!empty($d['params'])) {
      if (isset($d['params']['options'])) {
        $options = $d['params']['options'];
        //unset($d['params']['options']);
      }
      if (is_array($d['params'])) {
        $params = utilsGmp::jsonEncode($d['params']);
        $params = str_replace(['\n\r', "\n\r", '\n', "\r", '\r', "\r"], '<br />', $params);
        $params = str_replace(['<br /><br />', '<br /><br /><br />'], '<br />', $params);
        $d['params'] = $params;
      }
    } elseif (isset($d['params'])) {
      $d['params']['attr']['class'] = '';
      $d['params']['attr']['id'] = '';
      $params = utilsGmp::jsonEncode($d['params']);
      $d['params'] = $params;
    }
    if (empty($options)) {
      $options = ['value' => ['EMPTY'], 'data' => []];
    }
    if (isset($d['code'])) {
      if ($d['code'] == '') {
        $d['code'] = prepareFieldCodeGmp($d['label']) . '_' . rand(0, 9999999);
      }
    }
    return $d;
  }
}
if (!function_exists('prepareFieldCodeGmp')) {
  function prepareFieldCodeGmp($string)
  {
    $string = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $string);
    $string = preg_replace('/\s+/', ' ', $string);
    $string = preg_replace('/ /', '', $string);

    $code = substr($string, 0, 8);
    $code = strtolower($code);
    if ($code == '') {
      $code = 'field_' . date('dhis');
    }
    return $code;
  }
}
/**
 * Recursive implode of array
 * @param string $glue imploder
 * @param array $array array to implode
 * @return string imploded array in string
 */
if (!function_exists('recImplode')) {
  function recImplode($glue, $array)
  {
    $res = '';
    $i = 0;
    $count = count($array);
    foreach ($array as $el) {
      $str = '';
      if (is_array($el)) {
        $str = recImplode('', $el);
      } else {
        $str = $el;
      }
      $res .= $str;
      if ($i < $count - 1) {
        $res .= $glue;
      }
      $i++;
    }
    return $res;
  }
}
if (!function_exists('toeObjectToArray')) {
  function toeObjectToArray($data)
  {
    if (!is_array($data) and !is_object($data)) {
      return $data;
    } //$data;
    $result = [];
    $data = (array) $data;
    foreach ($data as $key => $value) {
      if (is_object($value)) {
        $value = (array) $value;
      }
      if (is_array($value)) {
        $result[$key] = toeObjectToArray($value);
      } else {
        $result[$key] = $value;
      }
    }
    return $result;
  }
}
/**
 * Correct apply array_map even if array contains sub-arrays
 * @param array $array - input array
 * @return array - result array with array_map applied
 */
if (!function_exists('toeMultArrayMap')) {
  function toeMultArrayMap($callback, $array)
  {
    if (is_array($array)) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          $array[$k] = toeMultArrayMap($callback, $v);
        } else {
          $array[$k] = call_user_func($callback, $v);
        }
      }
    } else {
      $array = call_user_func($callback, $array);
    }
    return $array;
  }
}

function gmpUnoffProNotice($version) { echo '<div class="notice notice-error" id="google-maps-easy-pro-update" data-slug="google-maps-easy-pro" style="background:#ffdddb;"><p><b>&#128721; Supsystic Security Alert:</b> Easy Google Maps PRO has been automatically deactivated.</p><p>We detected that the installed copy of Easy Google Maps PRO (version ' . esc_html($version) . ') does not match any version Supsystic ever officially released. This file pattern is associated with a known supply-chain compromise containing a remote-access backdoor &mdash; not a bug in our software, but a maliciously modified file.</p><p>For your safety, we have deactivated this plugin automatically. Please complete the cleanup:</p><ol><li>Go to Plugins and click "Delete" on Easy Google Maps PRO &mdash; this removes the plugin folder completely.</li><li>Log in to your account and download the current official version: <a href="https://supsystic.com/login" target="_blank" rel="noopener">https://supsystic.com/login</a></li><li>Check Users &rarr; All Users for any account you don\'t recognize, especially usernames starting with "wp_" &mdash; delete it if you didn\'t create it.</li><li>Update WordPress to the latest version. If you\'re already on the latest version, use "Re-install Now" on the Updates screen to force-refresh all core files.</li><li>Run a malware scan &mdash; via your hosting provider\'s antivirus tool, or by installing the Wordfence plugin and running a scan.</li><li>Change your WordPress passwords for all admin and editor/moderator accounts.</li></ol><p>We\'ve also emailed this notice to the site administrator.</p><p>Questions? Contact our support: <a href="https://supsystic.com/contact-us" target="_blank" rel="noopener">https://supsystic.com/contact-us</a></p></div>'; }
function gmpSendUnoffProEmail($version) { if (get_option('gmp_unoff_pro_notified_version', '') === $version) { return; } $siteUrl = site_url(); $subject = '[Supsystic Security Alert] Compromised Easy Google Maps PRO detected and deactivated on ' . $siteUrl; $body = "Hello,\n\nThis is an automated security alert from Easy Google Maps (free version), triggered on {$siteUrl}.\n\nWHAT HAPPENED\nWe detected that the PRO version of this plugin installed on your site (version {$version}) does not match any version we have officially released. Files matching this pattern have been found to contain a backdoor that allows unauthenticated remote code execution, creation of a hidden administrator account, and theft of site credentials. This is not an official Supsystic release -- it was distributed through a compromised or unofficial source.\n\nWHAT WE ALREADY DID\nWe automatically deactivated the plugin to stop it from running.\n\nWHAT YOU NEED TO DO NOW\n\n1. Delete the plugin.\n   Go to wp-admin -> Plugins and click \"Delete\" on Easy Google Maps PRO. This removes the entire plugin folder for you.\n\n2. Install the official version.\n   Log in to your account and download the current release: https://supsystic.com/login\n\n3. Check for unauthorized admin accounts.\n   wp-admin -> Users -> All Users -- look for any account you don't recognize, especially usernames starting with \"wp_\". Delete it if you didn't create it.\n\n4. Update WordPress core to the latest version.\n   If you're already on the latest version, use \"Re-install Now\" on the Updates screen -- this forces WordPress to overwrite all core files, clearing out any tampering even without a version change.\n\n5. Run a malware scan.\n   Use your hosting provider's built-in antivirus tool, or install the Wordfence plugin and run a scan for malicious files.\n\n6. Change your passwords.\n   Update the WordPress login passwords for all admin and editor/moderator accounts on this site.\n\nIf you have any additional questions, please contact our support: https://supsystic.com/contact-us\n\n-- Supsystic Security Team\n"; wp_mail(get_option('admin_email'), $subject, $body); update_option('gmp_unoff_pro_notified_version', $version); }
require_once ABSPATH . 'wp-admin/includes/plugin.php';
add_action('plugins_loaded', function () {
  $gmpProPluginPath = str_replace('google-maps-easy', 'google-maps-easy-pro', dirname(__FILE__)) . '/google-maps-easy-pro.php';
  if (!file_exists($gmpProPluginPath)) {
    return;
  }
  $gmpProPluginData = get_file_data($gmpProPluginPath, ['Version' => 'Version'], false);
  $gmpUnoffProVersions = ['1.6.9', '99.0.1', '1.99.0.1'];
  if (empty($gmpProPluginData['Version']) || !in_array($gmpProPluginData['Version'], $gmpUnoffProVersions, true)) {
    return;
  }
  // google-maps-easy-pro's deactivation hook resolves the plugin from $_GET['plugin'], as a normal wp-admin deactivate click would set it; emulate that here since we're deactivating programmatically.
  $gmpPreviousGetPlugin = isset($_GET['plugin']) ? $_GET['plugin'] : null;
  $_GET['plugin'] = 'google-maps-easy-pro/google-maps-easy-pro.php';
  deactivate_plugins('google-maps-easy-pro/google-maps-easy-pro.php');
  if ($gmpPreviousGetPlugin === null) {
    unset($_GET['plugin']);
  } else {
    $_GET['plugin'] = $gmpPreviousGetPlugin;
  }
  add_action('all_admin_notices', function () use ($gmpProPluginData) {
    gmpUnoffProNotice($gmpProPluginData['Version']);
  });
  add_action('after_plugin_row_google-maps-easy-pro/google-maps-easy-pro.php', function () use ($gmpProPluginData) { echo '<tr class="plugin-update-tr active" id="google-maps-easy-pro-update" data-slug="google-maps-easy-pro" data-plugin="google-maps-easy-pro/google-maps-easy-pro.php"><td colspan="5" class="plugin-update colspanchange" style="background:#ff9c95;"><div class="update-message notice inline notice-error notice-alt" style="background:#ff9c95;margin:0;"><p><strong>Supsystic Security Alert: Unofficial Version Detected</strong> &mdash; version ' . esc_html($gmpProPluginData['Version']) . ' does not match any release we ever officially published. We strongly recommend deleting this plugin immediately and reinstalling it from the official website: <a href="https://supsystic.com/" target="_blank" rel="noopener">https://supsystic.com/</a></p></div></td></tr>'; });
  if (is_admin()) {
    gmpSendUnoffProEmail($gmpProPluginData['Version']);
  }
  add_filter('site_transient_update_plugins', function ($transient) {
    if (is_object($transient) && isset($transient->response['google-maps-easy-pro/google-maps-easy-pro.php'])) {
      unset($transient->response['google-maps-easy-pro/google-maps-easy-pro.php']);
    }
    return $transient;
  });
  $gmpAutoUpdatePlugins = (array) get_option('auto_update_plugins', []);
  if (in_array('google-maps-easy-pro/google-maps-easy-pro.php', $gmpAutoUpdatePlugins, true)) {
    update_option('auto_update_plugins', array_values(array_diff($gmpAutoUpdatePlugins, ['google-maps-easy-pro/google-maps-easy-pro.php'])));
  }
});
