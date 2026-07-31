<?php
class modulesModelGmp extends modelGmp
{
  public function get($d = [])
  {
    global $wpdb;
    $res = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}gmp_modules AS sup_m WHERE code = %s", $d['code']), ARRAY_A);
    return $res;
  }
  public function put($d = [])
  {
    $res = new responseGmp();
    $id = $this->_getIDFromReq($d);
    $d = prepareParamsGmp($d);
    if (is_numeric($id) && $id) {
      if (isset($d['active'])) {
        // Loose `== 1` here used to accept any truthy-looking value under PHP 7's
        // looser numeric-string comparisons; filter_var() is version-stable and
        // avoids a module silently landing on active=0 from an unexpected value type.
        $d['active'] = filter_var($d['active'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
      }
      global $wpdb;
      $tableName = $wpdb->prefix . 'gmp_modules';
      $data_where = [
        'id' => $id,
      ];
      $updateResult = $wpdb->update($tableName, $d, $data_where);
      if ($updateResult !== false) {
        $mod = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_modules WHERE " . $wpdb->prepare('id = %s', $id), ARRAY_A);
        $mod = !empty($mod) ? $mod : false;
        if (is_array($mod) && !isset($mod['type_id'])) {
          $mod = $mod[0];
        }
        if ($mod) {
          $newType = $wpdb->get_results("SELECT label FROM {$wpdb->prefix}gmp_modules_type WHERE " . $wpdb->prepare('id = %s', $mod['type_id']), ARRAY_A);
          if (is_array($newType) && !isset($newType['label'])) {
            $newType = $newType[0];
          }
          $newType = $newType['label'];
        }
      } else {
        if ($tableErrors = frameGmp::_()->getTable('modules')->getErrors()) {
          $res->errors = array_merge($res->errors, $tableErrors);
        } else {
          $res->errors[] = __('Module Update Failed', GMP_LANG_CODE);
        }
      }
    } else {
      $res->errors[] = __('Error module ID', GMP_LANG_CODE);
    }
    return $res;
  }
  protected function _getIDFromReq($d = [])
  {
    $id = 0;
    if (isset($d['id'])) {
      $id = $d['id'];
    } elseif (isset($d['code'])) {
      $fromDB = $this->get([
        'code' => $d['code'],
      ]);
      if (isset($fromDB[0]) && $fromDB[0]['id']) {
        $id = $fromDB[0]['id'];
      }
    }
    return $id;
  }
}
