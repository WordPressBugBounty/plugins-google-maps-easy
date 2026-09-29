<?php
#[AllowDynamicProperties]
class frameGmp
{
  private $_modules = [];
  private $_tables = [];
  private $_allModules = [];
  /**
   * bool Uses to know if we are on one of the plugin pages
   */
  private $_inPlugin = false;
  /**
   * Array to hold all scripts and add them in one time in addScripts method
   */
  private $_scripts = [];
  private $_scriptsInitialized = false;
  private $_nonceIsInitialized = false;
  private $_styles = [];
  private $_stylesInitialized = false;

  private $_scriptsVars = [];
  private $_mod = '';
  private $_action = '';
  /**
   * Object with result of executing non-ajax module request
   */
  private $_res = null;

  public function __construct()
  {
    $this->_res = toeCreateObjGmp('response', []);
  }
  public static function getInstance()
  {
    static $instance;
    if (!$instance) {
      $instance = new frameGmp();
    }
    return $instance;
  }
  public static function _()
  {
    return self::getInstance();
  }
  public function parseRoute()
  {
    // Check plugin
    $pl = reqGmp::getVar('pl');
    if ($pl == GMP_CODE) {
      $mod = reqGmp::getMode();
      if ($mod) {
        $this->_mod = $mod;
      }
      $action = reqGmp::getVar('action');
      if ($action && preg_match('/^[A-Za-z0-9_]+$/', $action)) {
        $this->_action = $action;
      }
    }
  }
  public function setMod($mod)
  {
    $this->_mod = $mod;
  }
  public function getMod()
  {
    return $this->_mod;
  }
  public function setAction($action)
  {
    $this->_action = $action;
  }
  public function getAction()
  {
    return $this->_action;
  }
  protected function _extractModules()
  {
    global $wpdb;
    if (dbGmp::exist('gmp_modules')) {
      $activeModules = $wpdb->get_results("SELECT toe_m.*, toe_m_t.label as type_name FROM {$wpdb->prefix}gmp_modules as toe_m INNER JOIN {$wpdb->prefix}gmp_modules_type toe_m_t ON toe_m_t.id = toe_m.type_id ORDER BY id ASC", ARRAY_A);
      if ($activeModules) {
        foreach ($activeModules as $m) {
          $code = $m['code'];
          $moduleLocationDir = GMP_MODULES_DIR;
          if (!empty($m['ex_plug_dir'])) {
            $moduleLocationDir = utilsGmp::getExtModDir($m['ex_plug_dir']);
          }
          if (is_dir($moduleLocationDir . $code)) {
            $this->_allModules[$m['code']] = 1;
            // The "active" flag of an extension module is only reset by the extension's
            // deactivation hook, which can be skipped (bulk/CLI deactivation, removed folder).
            // Never load it while the plugin that ships it is inactive in WordPress.
            if ((bool) $m['active'] && (empty($m['ex_plug_dir']) || $this->_extPluginActive($m['ex_plug_dir']))) {
              importClassGmp($code . strFirstUp(GMP_CODE), $moduleLocationDir . $code . DS . 'mod.php');
              $moduleClass = toeGetClassNameGmp($code);
              if (class_exists($moduleClass)) {
                $this->_modules[$code] = new $moduleClass($m);
                if (is_dir($moduleLocationDir . $code . DS . 'tables')) {
                  $this->_extractTables($moduleLocationDir . $code . DS . 'tables' . DS);
                }
              }
            }
          }
        }
      }
    }
  }
  protected function _extPluginActive($plugDir)
  {
    static $activePlugins = null;
    if ($activePlugins === null) {
      $activePlugins = (array) get_option('active_plugins', []);
      if (is_multisite()) {
        $activePlugins = array_merge($activePlugins, array_keys((array) get_site_option('active_sitewide_plugins', [])));
      }
    }
    $prefix = trim($plugDir, '/\\') . '/';
    foreach ($activePlugins as $plugin) {
      if (strpos($plugin, $prefix) === 0) {
        return true;
      }
    }
    return false;
  }
  protected function _initModules()
  {
    if (!empty($this->_modules)) {
      foreach ($this->_modules as $key => $mod) {
        $mod->init();
      }
    }
  }
  public function init()
  {
    reqGmp::init();
    add_action('init', [$this, '_delayedInit'], 5);
  }
  public function _delayedInit()
  {
    //$startTime = microtime(true);
    reqGmp::init();
    $this->_extractTables();
    $this->_extractModules();

    $this->_initModules();

    dispatcherGmp::doAction('afterModulesInit');

    modInstallerGmp::checkActivationMessages();

    $this->_execModules();

    add_action('init', [$this, 'addScripts']);
    add_action('init', [$this, 'addStyles']);

    register_activation_hook(GMP_DIR . DS . GMP_MAIN_FILE, ['utilsGmp', 'activatePlugin']); //See classes/install.php file
    register_uninstall_hook(GMP_DIR . DS . GMP_MAIN_FILE, ['utilsGmp', 'deletePlugin']);
    register_deactivation_hook(GMP_DIR . DS . GMP_MAIN_FILE, ['utilsGmp', 'deactivatePlugin']);

    add_action('init', [$this, 'connectLang']);
    // $operationTime = microtime(true) - $startTime;
  }
  public function connectLang()
  {
    load_plugin_textdomain(GMP_LANG_CODE, false, GMP_PLUG_NAME . '/languages/');
  }
  /**
   * Check permissions for action in controller by $code and made corresponding action
   * @param string $code Code of controller that need to be checked
   * @param string $action Action that need to be checked
   * @return bool true if ok, else - should exit from application
   */
  public function checkPermissions($code, $action)
  {
    if ($this->havePermissions($code, $action)) {
      return true;
    } else {
      exit(_e('You have no permissions to view this page', GMP_LANG_CODE));
    }
  }
  /**
   * Framework plumbing that is public for PHP reasons but must never be reachable as an action.
   */
  private $_notActions = ['__construct', '__call', 'init', 'setcode', 'getcode', 'exec', 'getview', 'getmodel', 'getnoncedmethods', 'getpermissions', 'getpublicmethods', 'getmodule', 'display'];
  /**
   * Actions of extension modules that are called by site visitors. Used for extension
   * versions released before controllers declared getPublicMethods(). Their old forms
   * send no nonce, so a nonce is required only when the controller itself asks for it.
   */
  private $_legacyPublicActions = [
    'frontend_actions' => ['saveMarkerForm', 'deleteMarkerOnFrontend'],
  ];
  /**
   * Only real, public, non-framework methods declared on the controller can be actions.
   * This also rules out __call(), which would otherwise proxy any model method.
   */
  protected function _isControllerAction($controller, $action)
  {
    if (!$controller || $action === '' || $action[0] === '_' || in_array($action, $this->_notActions, true) || !method_exists($controller, $action)) {
      return false;
    }
    $method = new ReflectionMethod($controller, $action);
    return $method->isPublic() && !$method->isStatic();
  }
  /**
   * Actions of the module that visitors without admin rights may call.
   */
  public function getPublicActions($code)
  {
    $mod = $this->getModule($code);
    $controller = $mod ? $mod->getController() : null;
    $public = $controller && method_exists($controller, 'getPublicMethods') ? (array) $controller->getPublicMethods() : [];
    if (isset($this->_legacyPublicActions[$code])) {
      $public = array_merge($public, $this->_legacyPublicActions[$code]);
    }
    return array_map('strtolower', $public);
  }
  /**
   * Check permissions for action in controller by $code.
   * Deny by default: an action that is not listed in the controller permissions is
   * allowed to administrators only, unless the controller declares it public.
   * @param string $code Code of controller that need to be checked
   * @param string $action Action that need to be checked
   * @return bool true if ok, else - false
   */
  public function havePermissions($code, $action)
  {
    $mod = $this->getModule($code);
    $controller = $mod ? $mod->getController() : null;
    $action = strtolower((string) $action);
    if (!$this->_isControllerAction($controller, $action)) {
      return false;
    }
    $currentUserPosition = frameGmp::_()->getModule('user')->getCurrentUserPosition();
    // For multi-sites network admin role is undefined, let's do this here
    if (is_multisite() && is_admin() && is_super_admin()) {
      $currentUserPosition = GMP_ADMIN;
    }
    $res = null;
    $permissions = $controller->getPermissions();
    if (!empty($permissions[GMP_METHODS]) && is_array($permissions[GMP_METHODS])) {
      foreach ($permissions[GMP_METHODS] as $method => $levels) {
        if (strtolower($method) === $action) {
          $res = is_array($levels) ? in_array($currentUserPosition, $levels) : $levels == $currentUserPosition;
          break;
        }
      }
    }
    if ($res === null && !empty($permissions[GMP_USERLEVELS]) && is_array($permissions[GMP_USERLEVELS])) {
      foreach ($permissions[GMP_USERLEVELS] as $userlevel => $methods) {
        if (in_array($action, array_map('strtolower', (array) $methods), true)) {
          $res = $currentUserPosition == $userlevel;
          break;
        }
      }
    }
    $publicActions = $this->getPublicActions($code);
    if ($res === null) {
      $res = $currentUserPosition == GMP_ADMIN || in_array($action, $publicActions, true);
    }
    if ($res) {
      // Additional check for nonces
      $noncedMethods = array_map('strtolower', (array) $controller->getNoncedMethods());
      if (in_array($action, $noncedMethods, true)) {
        $nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : reqGmp::getVar('_wpnonce');
        $nonceAction = is_admin() && !in_array($action, $publicActions, true) ? 'gmp_nonce' : 'gmp_nonce_frontend';
        if (!wp_verify_nonce($nonce, $nonceAction)) {
          $res = false;
        }
      }
    }
    return (bool) $res;
  }
  public function getRes()
  {
    return $this->_res;
  }
  public function execAfterWpInit()
  {
    $this->_doExec();
  }
  /**
   * Check if method for module require some special permission. We can detect users permissions only after wp init action was done.
   */
  protected function _execOnlyAfterWpInit()
  {
    $res = false;
    $mod = $this->getModule($this->_mod);
    $action = strtolower($this->_action);
    if ($mod) {
      $permissions = $mod->getController()->getPermissions();
      if (!empty($permissions)) {
        // Special permissions
        if (isset($permissions[GMP_METHODS]) && !empty($permissions[GMP_METHODS])) {
          foreach ($permissions[GMP_METHODS] as $method => $permissions) {
            // Make case-insensitive
            $permissions[GMP_METHODS][strtolower($method)] = $permissions;
          }
          if (array_key_exists($action, $permissions[GMP_METHODS])) {
            // Permission for this method exists
            $res = true;
          }
        }
        if (isset($permissions[GMP_USERLEVELS]) && !empty($permissions[GMP_USERLEVELS])) {
          $res = true;
        }
      }
    }
    return $res;
  }
  protected function _execModules()
  {
    if ($this->_mod) {
      // If module exist and is active
      $mod = $this->getModule($this->_mod);
      if ($mod && $this->_action) {
        if ($this->_execOnlyAfterWpInit()) {
          add_action('init', [$this, 'execAfterWpInit']);
        } else {
          $this->_doExec();
        }
      }
    }
  }
  protected function _doExec()
  {
    $mod = $this->getModule($this->_mod);
    if ($mod && $this->checkPermissions($this->_mod, $this->_action)) {
      switch (reqGmp::getVar('reqType')) {
        case 'ajax':
          add_action('wp_ajax_' . $this->_action, [$mod->getController(), $this->_action]);
          // Logged-out requests only for actions the controller declares public.
          if (in_array(strtolower($this->_action), $this->getPublicActions($this->_mod), true)) {
            add_action('wp_ajax_nopriv_' . $this->_action, [$mod->getController(), $this->_action]);
          }
          break;
        default:
          $this->_res = $mod->exec($this->_action);
          break;
      }
    }
  }
  protected function _extractTables($tablesDir = GMP_TABLES_DIR)
  {
    $mDirHandle = opendir($tablesDir);
    while (($file = readdir($mDirHandle)) !== false) {
      if (is_file($tablesDir . $file) && $file != '.' && $file != '..' && strpos($file, '.php')) {
        $this->_extractTable(str_replace('.php', '', $file), $tablesDir);
      }
    }
  }
  protected function _extractTable($tableName, $tablesDir = GMP_TABLES_DIR)
  {
    importClassGmp('noClassNameHere', $tablesDir . $tableName . '.php');
    $this->_tables[$tableName] = tableGmp::_($tableName);
  }
  /**
   * public alias for _extractTables method
   * @see _extractTables
   */
  public function extractTables($tablesDir)
  {
    if (!empty($tablesDir)) {
      $this->_extractTables($tablesDir);
    }
  }
  public function exec()
  {
    /**
         * @deprecated
         */
    /*if(!empty($this->_modules)) {
            foreach($this->_modules as $mod) {
                $mod->exec();
            }
        }*/
  }
  public function getTables()
  {
    return $this->_tables;
  }
  /**
   * Return table by name
   * @param string $tableName table name in database
   * @return object table
   * @example frameGmp::_()->getTable('products')->getAll()
   */
  public function getTable($tableName)
  {
    if (empty($this->_tables[$tableName])) {
      $this->_extractTable($tableName);
    }
    return $this->_tables[$tableName];
  }
  public function getModules($filter = [])
  {
    $res = [];
    if (empty($filter)) {
      $res = $this->_modules;
    } else {
      foreach ($this->_modules as $code => $mod) {
        if (isset($filter['type'])) {
          if (is_numeric($filter['type']) && $filter['type'] == $mod->getTypeID()) {
            $res[$code] = $mod;
          } elseif ($filter['type'] == $mod->getType()) {
            $res[$code] = $mod;
          }
        }
      }
    }
    return $res;
  }

  public function getModule($code)
  {
    return isset($this->_modules[$code]) ? $this->_modules[$code] : null;
  }
  public function inPlugin()
  {
    return $this->_inPlugin;
  }
  /**
   * Push data to script array to use it all in addScripts method
   * @see wp_enqueue_script definition
   */
  public function addScript($handle, $src = '', $deps = [], $ver = false, $in_footer = false, $vars = [])
  {
    $src = empty($src) ? $src : uriGmp::_($src);
    if (!$ver) {
      $ver = GMP_VERSION_PLUGIN;
    }
    if ($this->_scriptsInitialized) {
      wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
    } else {
      $this->_scripts[] = [
        'handle' => $handle,
        'src' => $src,
        'deps' => $deps,
        'ver' => $ver,
        'in_footer' => $in_footer,
        'vars' => $vars,
      ];
    }
  }
  /**
   * Add all scripts from _scripts array to worumsess
   */
  public function addScripts()
  {
    if (!empty($this->_scripts)) {
      foreach ($this->_scripts as $s) {
        wp_enqueue_script($s['handle'], $s['src'], $s['deps'], $s['ver'], $s['in_footer']);

        if ($s['vars'] || isset($this->_scriptsVars[$s['handle']])) {
          $vars = [];
          if ($s['vars']) {
            $vars = $s['vars'];
          }
          if ($this->_scriptsVars[$s['handle']]) {
            $vars = array_merge($vars, $this->_scriptsVars[$s['handle']]);
          }
          if ($vars) {
            foreach ($vars as $k => $v) {
              $v = is_array($v) ? $v : [$v];
              wp_localize_script($s['handle'], $k, $v);
            }
          }
        }
      }
    }
    $this->_scriptsInitialized = true;
  }
  public function addJSVar($script, $name, $val)
  {
    if ($this->_scriptsInitialized) {
      if ($this->_nonceIsInitialized && $script == 'nonceGmp') {
      } else {
        $val = is_array($val) ? $val : [$val];
        wp_localize_script($script, $name, $val);
      }
      if ($script == 'nonceGmp') {
        $this->_nonceIsInitialized = true;
      }
    } else {
      $this->_scriptsVars[$script][$name] = $val;
    }
  }

  public function addStyle($handle, $src = false, $deps = [], $ver = false, $media = 'all')
  {
    $src = empty($src) ? $src : uriGmp::_($src);
    if (!$ver) {
      $ver = GMP_VERSION_PLUGIN;
    }
    if ($this->_stylesInitialized) {
      wp_enqueue_style($handle, $src, $deps, $ver, $media);
    } else {
      $this->_styles[] = [
        'handle' => $handle,
        'src' => $src,
        'deps' => $deps,
        'ver' => $ver,
        'media' => $media,
      ];
    }
  }
  public function addStyles()
  {
    if (!empty($this->_styles)) {
      foreach ($this->_styles as $s) {
        wp_enqueue_style($s['handle'], $s['src'], $s['deps'], $s['ver'], $s['media']);
      }
    }
    $this->_stylesInitialized = true;
  }
  public function getScripts()
  {
    return $this->_scripts;
  }
  public function getStyles()
  {
    return $this->_styles;
  }
  public function setScriptsInitialized($state)
  {
    $this->_scriptsInitialized = $state;
  }
  public function setStylesInitialized($state)
  {
    $this->_stylesInitialized = $state;
  }
  public function getJSVars()
  {
    return $this->_scriptsVars;
  }

  //Very interesting thing going here.............
  public function loadPlugins()
  {
    require_once ABSPATH . 'wp-includes/pluggable.php';
  }
  public function loadWPSettings()
  {
    require_once ABSPATH . 'wp-settings.php';
  }
  public function loadLocale()
  {
    require_once ABSPATH . 'wp-includes/locale.php';
  }
  public function moduleActive($code)
  {
    return isset($this->_modules[$code]);
  }
  public function moduleExists($code)
  {
    if ($this->moduleActive($code)) {
      return true;
    }
    return isset($this->_allModules[$code]);
  }
  public function isTplEditor()
  {
    $tplEditor = reqGmp::getVar('tplEditor');
    return (bool) $tplEditor;
  }
  /**
   * This is custom method for each plugin and should be modified if you create copy from this instance.
   */
  public function isAdminPlugOptsPage()
  {
    $page = reqGmp::getVar('page');
    if (is_admin() && strpos((string) $page, frameGmp::_()->getModule('adminmenu')->getMainSlug()) !== false) {
      return true;
    }
    return false;
  }
  public function isAdminPlugPage()
  {
    if ($this->isAdminPlugOptsPage()) {
      return true;
    }
    return false;
  }
  public function licenseDeactivated()
  {
    return !$this->getModule('license') && $this->moduleExists('license');
  }
  public function savePluginActivationErrors()
  {
    update_option(GMP_CODE . '_plugin_activation_errors', ob_get_contents());
  }
  public function getActivationErrors()
  {
    return get_option(GMP_CODE . '_plugin_activation_errors');
  }

  public function getPluginUrl()
  {
    return plugins_url('', dirname(__FILE__));
  }
}
