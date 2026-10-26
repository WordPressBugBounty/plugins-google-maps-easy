<?php
class markerModelGmp extends modelGmp
{
  public static $tableObj;
  function __construct()
  {
    $this->_setTbl('marker');
    if (empty(self::$tableObj)) {
      self::$tableObj = frameGmp::_()->getTable('marker');
    }
  }
  public function save($marker = [], &$update = false, $import = false)
  {
    $id = isset($marker['id']) ? (int) $marker['id'] : 0;
    $marker['title'] = isset($marker['title']) ? trim($marker['title']) : '';
    $marker['coord_x'] = isset($marker['coord_x']) ? (float) $marker['coord_x'] : 0;
    $marker['coord_y'] = isset($marker['coord_y']) ? (float) $marker['coord_y'] : 0;
    // Display period (PRO): the admin form sends period_date_*, the KML import sends period_*.
    // Only the keys that were sent are saved, so other callers never reset an existing period.
    $periodData = [];
    foreach (['from', 'to'] as $edge) {
      if (array_key_exists('period_date_' . $edge, $marker)) {
        $periodData['period_' . $edge] = $this->preparePeriodDate($marker['period_date_' . $edge], $edge);
      } elseif (array_key_exists('period_' . $edge, $marker)) {
        $periodData['period_' . $edge] = $this->preparePeriodDate($marker['period_' . $edge], $edge);
      }
    }
    if (!$this->hasPeriodColumns()) {
      $periodData = [];
    }
    $update = (bool) $id;
    if (!empty($marker['title'])) {
      $marker = apply_filters('gmp_before_marker_save', $marker);
      if (isset($marker['description'])) {
        //Replace site url in markers descriptions backend. Fixed site migration issue.
        $marker['description'] = str_replace(GMP_SITE_URL, 'GMP_SITE_URL', $marker['description']);
      }
      if (!isset($marker['marker_group_id'])) {
        $marker['marker_group_id'] = 0;
      }
      if (!isset($marker['icon']) || !frameGmp::_()->getModule('icons')->getModel()->iconExists($marker['icon'])) {
        $marker['icon'] = 1;
      }
      $marker['map_id'] = isset($marker['map_id']) ? (int) $marker['map_id'] : 0;
      if (!$update) {
        $marker['create_date'] = date('Y-m-d H:i:s');
        if ($marker['map_id']) {
          global $wpdb;
          $maxSortOrder = $wpdb->get_var("SELECT MAX(sort_order) FROM {$wpdb->prefix}gmp_markers WHERE " . $wpdb->prepare('map_id = %s', $marker['map_id']));
          $marker['sort_order'] = ++$maxSortOrder;
        }
      }

      //save first groups value in markers table to better compatibility
      $markerGroupIds = $marker['marker_group_id'];
      if (!$import) {
        $first_value = is_array($markerGroupIds) ? reset($markerGroupIds) : $markerGroupIds;
        $marker['marker_group_id'] = $first_value;
      }
      $marker['params'] = isset($marker['params']) ? utilsGmp::serialize($marker['params']) : '';

      if ($import) {
        $markerGroupIds = [];
        $markerGroupIds[] = $marker['marker_group_id'];
      }

      if ($update) {
        dispatcherGmp::doAction('beforeMarkerUpdate', $id, $marker);
        global $wpdb;
        $tableName = $wpdb->prefix . 'gmp_markers';
        $data_update = [
          'title' => $marker['title'],
          'description' => $marker['description'],
          'icon' => $marker['icon'],
          'address' => $marker['address'],
          'coord_x' => $marker['coord_x'],
          'coord_y' => $marker['coord_y'],
          'params' => $marker['params'],
          'id' => $marker['id'],
          'map_id' => $marker['map_id'],
          'marker_group_id' => $marker['marker_group_id'],
        ];
        if (!empty($marker['create_date'])) {
          $data_update['create_date'] = $marker['create_date'];
        }
        if (!empty($marker['sort_order'])) {
          $data_update['sort_order'] = $marker['sort_order'];
        }
        $data_update = array_merge($data_update, $periodData);
        $data_where = [
          'id' => $id,
        ];
        $dbRes = $wpdb->update($tableName, $data_update, $data_where);
        if ($dbRes) {
          $dbResId = $id;
        }

        $tableName = $wpdb->prefix . 'gmp_marker_groups_relation';
        $data_where = [
          'marker_id' => $marker['id'],
        ];
        $wpdb->delete($tableName, $data_where);

        if (!empty($markerGroupIds) && is_array($markerGroupIds)) {
          foreach ($markerGroupIds as $markerId) {
            global $wpdb;
            $tableName = $wpdb->prefix . 'gmp_marker_groups_relation';
            $dbRes = $wpdb->insert($tableName, [
              'marker_id' => $marker['id'],
              'groups_id' => $markerId,
            ]);
            if ($dbRes) {
              $dbResId = $wpdb->insert_id;
            }
          }
        } else {
          global $wpdb;
          $tableName = $wpdb->prefix . 'gmp_marker_groups_relation';
          $dbRes = $wpdb->insert($tableName, [
            'marker_id' => $marker['id'],
            'groups_id' => $markerGroupIds,
          ]);
          if ($dbRes) {
            $dbResId = $wpdb->insert_id;
          }
        }
        dispatcherGmp::doAction('afterMarkerUpdate', $id, $marker);
      } else {
        dispatcherGmp::doAction('beforeMarkerInsert', $marker);

        global $wpdb;
        // 0 when the insert fails: afterMarkerInsert below got an undefined variable
        $dbResId = 0;
        $tableName = $wpdb->prefix . 'gmp_markers';
        $dbRes = $wpdb->insert(
          $tableName,
          array_merge(
            [
              'title' => $marker['title'],
              'description' => isset($marker['description']) ? $marker['description'] : '',
              'icon' => $marker['icon'],
              'address' => isset($marker['address']) ? $marker['address'] : '',
              'coord_x' => $marker['coord_x'],
              'coord_y' => $marker['coord_y'],
              'params' => $marker['params'],
              'map_id' => $marker['map_id'],
              'marker_group_id' => $marker['marker_group_id'],
              'create_date' => $marker['create_date'],
              'sort_order' => !empty($marker['sort_order']) ? $marker['sort_order'] : '',
            ],
            $periodData,
          ),
        );
        if ($dbRes) {
          $dbResId = $wpdb->insert_id;
        }
        if ($dbRes) {
          $tableName = $wpdb->prefix . 'gmp_marker_groups_relation';
          $data_where = [
            'marker_id' => $dbResId,
          ];
          $wpdb->delete($tableName, $data_where);
          if (!empty($markerGroupIds) && is_array($markerGroupIds)) {
            foreach ($markerGroupIds as $markerId) {
              $tableName = $wpdb->prefix . 'gmp_marker_groups_relation';
              $wpdb->insert($tableName, [
                'marker_id' => $dbResId,
                'groups_id' => $markerId,
              ]);
            }
          }
        }
        dispatcherGmp::doAction('afterMarkerInsert', $dbResId, $marker);
      }

      if ($dbRes) {
        if (!$update) {
          $id = $dbResId;
        }
        do_action('gmp_save_lang_data', [
          'type' => 'markers',
          'marker_id' => $id,
          'map' => frameGmp::_()->getModule('gmap')->getModel()->getMapById($marker['map_id']),
        ]);
        return $id;
      } else {
        $this->pushError(frameGmp::_()->getTable('marker')->getErrors());
      }
    } else {
      $this->pushError(__('Please enter marker name', GMP_LANG_CODE), 'marker_opts[title]');
    }
    return false;
  }
  public function existsId($id)
  {
    if ($id) {
      global $wpdb;
      $marker = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE " . $wpdb->prepare('id = %s', $id), ARRAY_A);
      if (!empty($marker)) {
        return true;
      }
    }
    return false;
  }
  public function getById($id)
  {
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE " . $wpdb->prepare('id = %s', $id), ARRAY_A);
    return $this->_afterGet($row);
  }
  public function getMarkerByTitle($title)
  {
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE " . $wpdb->prepare('title = %s', $title), ARRAY_A);
    return $this->_afterGet($row);
  }
  public function hasPeriodColumns()
  {
    static $hasColumns = null;
    if ($hasColumns === null) {
      global $wpdb;
      $hasColumns = (bool) $wpdb->get_var("SHOW COLUMNS FROM `{$wpdb->prefix}gmp_markers` LIKE 'period_to'");
    }
    return $hasColumns;
  }
  /**
   * Period date/time as "Y-m-d H:i:s" in the site's time, null for an empty or wrong value.
   * Accepts the datetime-local field ("2026-06-01T10:30"), the old date picker ("01-06-2026") and DB values.
   */
  public function preparePeriodDate($value, $edge)
  {
    $value = trim((string) $value);
    if ($value === '' || strpos($value, '0000-00-00') === 0) {
      return null;
    }
    $time = strtotime(str_replace('T', ' ', $value));
    if (!$time) {
      return null;
    }
    // A date without time: "to" lasts until the end of that day
    if ($edge === 'to' && !preg_match('/\d{1,2}:\d{2}/', $value)) {
      return date('Y-m-d 23:59:59', $time);
    }
    return date('Y-m-d H:i:s', $time);
  }
  public function _afterGet($marker, $widthMapData = false, $withoutIcons = false)
  {
    if (!empty($marker)) {
      if (!$withoutIcons) {
        $marker['icon_data'] = frameGmp::_()->getModule('icons')->getModel()->getIconFromId($marker['icon']);
      }
      $marker['params'] = utilsGmp::unserialize($marker['params']);
      if (!is_array($marker['params'])) {
        $marker['params'] = [];
      }
      /*$marker['position'] = array(
				'coord_x' => $marker['coord_x'],
				'coord_y' => $marker['coord_y'],
			);*/
      if (isset($marker['params']['marker_title_link']) && !empty($marker['params']['marker_title_link']) && strpos($marker['params']['marker_title_link'], 'http') !== 0) {
        $marker['params']['marker_title_link'] = 'http://' . $marker['params']['marker_title_link'];
      }
      if (!isset($marker['params']['title_is_link'])) {
        $marker['params']['title_is_link'] = false;
      }

      $siteUrl = uriGmp::isHttps() ? uriGmp::makeHttps(GMP_SITE_URL) : GMP_SITE_URL;
      // Go to absolute path as "../wp-content/" will not work on frontend
      // (string): the column may hold NULL, str_replace() with null is deprecated since PHP 8.1
      $marker['description'] = str_replace('../wp-content/', $siteUrl . 'wp-content/', (string) $marker['description']);
      //Replace site url in markers descriptions frontend.
      $marker['description'] = str_replace('GMP_SITE_URL', $siteUrl, $marker['description']);

      //if(uriGmp::isHttps()) {
      //	$marker['description'] = uriGmp::makeHttps($marker['description']);
      //}
      if ($widthMapData && !empty($marker['map_id'])) {
        $marker['map'] = frameGmp::_()->getModule('gmap')->getModel()->getMapById($marker['map_id'], false);
      }

      if ($widthMapData && !empty($marker['map_id'])) {
        $marker['map'] = frameGmp::_()->getModule('gmap')->getModel()->getMapById($marker['map_id'], false);
      }

      if (!empty($marker['id'])) {
        $marker['marker_group_ids'] = frameGmp::_()->getModule('marker')->getModel('marker_groups_relation')->getRelationsByMarkerId($marker['id']);
      }
    }
    return $marker;
  }

  public function getMapMarkers($mapId, $withGroup = false, $userId = false)
  {
    $mapId = (int) $mapId;
    $params = ['map_id' => $mapId];
    if ($userId) {
      $params['user_id'] = $userId;
    }
    global $wpdb;
    $markers = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}gmp_markers AS toe_mr WHERE map_id = %s ORDER BY sort_order ASC", $mapId), ARRAY_A);
    if (!empty($markers)) {
      $iconIds = [];
      foreach ($markers as $i => $m) {
        $markers[$i] = $this->_afterGet($markers[$i], false, true);
        // We need to do shortcode only on frontend
        if (isset($markers[$i]['description'])) {
          ob_start();
          echo do_shortcode($markers[$i]['description']);
          $out = ob_get_contents();
          ob_end_clean();
          $markers[$i]['description'] = $out;
        }
        $iconIds[$m['icon']] = 1;
      }
      $usedIcons = frameGmp::_()->getModule('icons')->getModel()->getIconsByIds(array_keys($iconIds));
      foreach ($markers as $i => $m) {
        $markers[$i]['icon_data'] = isset($usedIcons[$m['icon']]) ? $usedIcons[$m['icon']] : '';
      }
    }
    return $markers;
  }
  public function getMapMarkersIds($mapId)
  {
    global $wpdb;
    $markers = $wpdb->get_col($wpdb->prepare("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE map_id= %s", $mapId), ARRAY_A);
    return $markers;
  }
  public function getMarkersByIds($ids)
  {
    if (!is_array($ids)) {
      $ids = [$ids];
    }
    $ids = array_map('intval', $ids);
    global $wpdb;
    $ids = implode(',', array_map('absint', $ids));
    $markers = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE id IN (%1s)", $ids), ARRAY_A);
    if (!empty($markers)) {
      foreach ($markers as $i => $m) {
        $markers[$i] = $this->_afterGet($markers[$i]);
      }
    }
    return $markers;
  }
  /**
   * Category links live in a separate table; drop them together with the markers.
   */
  private function _removeGroupRelations($markerIds)
  {
    $markerIds = array_filter(array_map('intval', (array) $markerIds));
    if (empty($markerIds)) {
      return;
    }
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->prefix}gmp_marker_groups_relation WHERE marker_id IN (" . implode(',', $markerIds) . ')');
  }
  public function removeMarker($markerId)
  {
    dispatcherGmp::doAction('beforeMarkerRemove', $markerId);
    global $wpdb;
    $this->_removeGroupRelations($markerId);
    $tableName = $wpdb->prefix . 'gmp_markers';
    $data_where = [
      'id' => $markerId,
    ];
    return $res = $wpdb->delete($tableName, $data_where);
  }
  public function removeList($ids)
  {
    $ids = array_map('intval', $ids);
    global $wpdb;
    $this->_removeGroupRelations($ids);
    foreach ($ids as $id) {
      $tableName = $wpdb->prefix . 'gmp_markers';
      $data_where = [
        'id' => $id,
      ];
      $res = $wpdb->delete($tableName, $data_where);
    }
    if ($res) {
      return true;
    }
  }
  public function findAddress($params)
  {
    if (!isset($params['addressStr']) || strlen($params['addressStr']) < 3) {
      $this->pushError(__('Address is empty or not match', GMP_LANG_CODE));
      return false;
    }
    $addr = $params['addressStr'];
    $getdata = http_build_query([
      'address' => $addr,
      'language' => 'en',
      'sensor' => 'false',
    ]);
    $apiDomain = frameGmp::_()->getModule('gmap')->getView()->getApiDomain();
    $google_response = utilsGmp::jsonDecode(file_get_contents($apiDomain . 'maps/api/geocode/json?' . $getdata));
    $res = [];
    foreach ($google_response['results'] as $response) {
      $res[] = [
        'position' => $response['geometry']['location'],
        'address' => $response['formatted_address'],
      ];
    }
    return $res;
  }
  public function removeMarkersFromMap($mapId)
  {
    global $wpdb;
    $this->_removeGroupRelations($wpdb->get_col($wpdb->prepare("SELECT id FROM {$wpdb->prefix}gmp_markers WHERE map_id = %d", $mapId)));
    $tableName = $wpdb->prefix . 'gmp_markers';
    $data_where = [
      'map_id' => $mapId,
    ];
    return $res = $wpdb->delete($tableName, $data_where);
  }
  public function getAllMarkers($d = [], $widthMapData = false)
  {
    // if(isset($d['limitFrom']) && isset($d['limitTo']))
    // 	frameGmp::_()->getTable('marker')->limitFrom($d['limitFrom'])->limitTo($d['limitTo']);
    // if(isset($d['orderBy']) && !empty($d['orderBy'])) {
    // 	frameGmp::_()->getTable('marker')->orderBy( $d['orderBy'] );
    // }
    //     $iconsModel = frameGmp::_()->getModule('icons')->getModel();
    //     foreach($markerList as $i => &$m) {
    // 	$markerList[$i] = $this->_afterGet($markerList[$i], $widthMapData);
    //     }
    //     return $markerList;
    global $wpdb;
    $markerList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_markers", ARRAY_A);
    foreach ($markerList as $i => &$m) {
      $markerList[$i] = $this->_afterGet($markerList[$i], $widthMapData);
    }
    return $markerList;
  }
  public function getTotalCountBySearch($search, $mapId = false)
  {
    global $wpdb;
    if (!empty($search)) {
      if ($mapId) {
        // Markers have a title, not a label column
        $count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) AS total FROM {$wpdb->prefix}gmp_markers WHERE map_id = %s AND (id = %s OR title = %s)", $mapId, $search, $search));
      } else {
        $count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) AS total FROM {$wpdb->prefix}gmp_markers WHERE (id = %s OR title = %s)", $search, $search));
      }
    } else {
      if ($mapId) {
        $count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) AS total FROM {$wpdb->prefix}gmp_markers WHERE map_id = %s", $mapId));
      } else {
        $count = (int) $wpdb->get_var("SELECT COUNT(*) AS total FROM {$wpdb->prefix}gmp_markers ");
      }
    }
    return $count;
  }
  public function getListForTblBySearch($search, $limitStart, $rowsLimit, $mapId)
  {
    global $wpdb;
    // In the order of the list (sort_order): ordered by id, the list loading saved the id order back
    // (markerControllerGmp::_prepareListForTbl) and the markers dragged in the list returned to their places
    if (!empty($search)) {
      $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE " . $wpdb->prepare(' map_id = %s AND (id = %s OR title = %s) ORDER BY sort_order ASC, id ASC LIMIT %1s,%1s', $mapId, $search, $search, (int) $limitStart, (int) $rowsLimit), ARRAY_A);
    } else {
      $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}gmp_markers WHERE " . $wpdb->prepare(' map_id = %s ORDER BY sort_order ASC, id ASC LIMIT %1s,%1s', $mapId, (int) $limitStart, (int) $rowsLimit), ARRAY_A);
    }
    foreach ($data as $i => &$m) {
      $data[$i] = $this->_afterGet($data[$i]);
    }
    return $data;
  }
  public function setMarkersToMap($addMarkerIds, $mapId)
  {
    if (!is_array($addMarkerIds)) {
      $addMarkerIds = [$addMarkerIds];
    }
    $addMarkerIds = array_map('intval', $addMarkerIds);
    global $wpdb;
    $dbRes = false;
    foreach ($addMarkerIds as $addMarkerId) {
      $tableName = $wpdb->prefix . 'gmp_markers';
      $data_update = [
        'map_id' => (int) $mapId,
      ];
      $data_where = [
        'id' => $addMarkerId,
        'map_id' => 0,
      ];
      $dbRes = $wpdb->update($tableName, $data_update, $data_where);
    }
    if ($dbRes) {
      return true;
    }
  }
  public function getCount($d = [])
  {
    global $wpdb;
    return $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}gmp_markers ");
  }
  public function updatePos($d = [])
  {
    // $d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
    // if($d['id']) {
    // 		'coord_x' => $d['lat'],
    // 		'coord_y' => $d['lng'],
    // 	), array(
    // 		'id' => $d['id'],
    // 	));
    // } else
    // 	$this->pushError (__('Invalid Marker ID'));
    // return false;
    global $wpdb;
    $d['id'] = isset($d['id']) ? (int) $d['id'] : 0;
    if ($d['id']) {
      $tableName = $wpdb->prefix . 'gmp_markers';
      $data_update = [
        'coord_x' => $d['lat'],
        'coord_y' => $d['lng'],
      ];
      $data_where = [
        'id' => $d['id'],
      ];
      return $dbRes = $wpdb->update($tableName, $data_update, $data_where);
    } else {
      $this->pushError(__('Invalid Marker ID'));
    }
    return false;
  }
  public function replaceDeletedIconIdToDefault($id)
  {
    if ($id) {
      global $wpdb;
      $tableName = $wpdb->prefix . 'gmp_markers';
      $data_update = [
        'icon' => '1',
      ];
      $data_where = [
        'icon' => $id,
      ];
      return $dbRes = $wpdb->update($tableName, $data_update, $data_where);
    } else {
      $this->pushError(__('Invalid ID'));
    }
    return false;
    // if($id) {
    // 		'icon' => '1',
    // 	), array(
    // 		'icon' => $id,
    // 	));
    // } else
    // 	$this->pushError (__('Invalid ID'));
    // return false;
  }
}
