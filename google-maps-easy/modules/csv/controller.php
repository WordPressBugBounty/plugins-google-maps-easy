<?php
class csvControllerGmp extends controllerGmp
{
  public function exportMaps()
  {
    $data = reqGmp::get('get');
    $delimiter = !empty($data['delimiter']) ? $data['delimiter'] : ';';
    $fileDate = str_replace(['/', '.', ':'], '_', date(GMP_DATE_FORMAT_HIS));
    $fileName = sprintf(__('Maps from %s - %s', GMP_LANG_CODE), get_bloginfo('name'), $fileDate);
    $maps = frameGmp::_()->getModule('gmap')->getModel()->getAllMaps([]); // Only maps data
    if (empty($maps)) {
      _e('You have no maps for now.', GMP_LANG_CODE);
      exit();
    }

    // Remove unneeded values
    $keys = [];
    foreach ($maps as $key => $val) {
      unset($maps[$key]['original_id']);
      unset($maps[$key]['view_id']);
      unset($maps[$key]['view_html_id']);
      unset($maps[$key]['params']['view_id']);
      unset($maps[$key]['params']['view_html_id']);
      unset($maps[$key]['params']['id']);
      // Built from "map_stylization" when a map is loaded
      unset($maps[$key]['params']['map_stylization_data']);
      unset($maps[$key]['params']['center_on_cur_user_pos_icon_path']);
      // Keys of all maps: with the first map's keys only, options it did not have were lost for the others
      $keys = array_unique(array_merge($keys, $this->_getKeys($maps[$key])));
    }
    $keys = $this->_dropParentKeys($keys);
    $c = $r = 0;
    $this->_connectCsvLib();
    $csvGenerator = toeCreateObjGmp('csvgeneratorGmp', [$fileName]);
    $csvGenerator->setDelimiter($delimiter);
    foreach ($keys as $k) {
      $csvGenerator->addCell($r, $c, $k);
      $c++;
    }
    $c = 0;
    $r = 1;
    foreach ($maps as $map) {
      $c = 0;
      foreach ($keys as $k) {
        $mapValue = $this->_prepareValueToExport($this->_getKeyVal($map, $k));
        if (is_array($mapValue)) {
          $mapValue = implode(';', $mapValue);
        }
        $csvGenerator->addCell($r, $c, $mapValue);
        $c++;
      }
      $r++;
    }
    $csvGenerator->generate();
    exit();
  }
  public function exportMarkers()
  {
    $data = reqGmp::get('get');
    $delimiter = !empty($data['delimiter']) ? $data['delimiter'] : ';';
    $fileSiteDate = str_replace(['/', '.', ':'], '_', esc_html(get_bloginfo('name')) . ' - ' . date(GMP_DATE_FORMAT_HIS));
    $fileName = sprintf(__('Markers from %s', GMP_LANG_CODE), $fileSiteDate);
    $markers = frameGmp::_()->getModule('marker')->getModel()->getAllMarkers();
    if (empty($markers)) {
      _e('You have no markers for now.', GMP_LANG_CODE);
      exit();
    }
    $this->_connectCsvLib();
    $csvGenerator = toeCreateObjGmp('csvgeneratorGmp', [$fileName]);
    if (!empty($data['delimiter'])) {
      $csvGenerator->setDelimiter($delimiter);
    }
    $c = $r = 0;
    $keys = ['id', 'map_id', 'title', 'description', 'coord_x', 'coord_y'];
    $marker_keys = [];
    foreach ($markers as $m) {
      $marker_keys = array_unique(array_merge($marker_keys, $this->_getKeys($m)));
    }
    sort($marker_keys);
    $keys = $this->_dropParentKeys(array_unique(array_merge($keys, $marker_keys)));
    foreach ($keys as $k) {
      $csvGenerator->addCell($r, $c, $k);
      $c++;
    }
    $c = 0;
    $r = 1;
    foreach ($markers as $marker) {
      $c = 0;
      foreach ($keys as $k) {
        $markerValue = $this->_prepareValueToExport($this->_getKeyVal($marker, $k));
        $csvGenerator->addCell($r, $c, $markerValue);
        $c++;
      }
      $r++;
    }
    $csvGenerator->generate();
    exit();
  }
  public function exportFigures()
  {
    $data = reqGmp::get('get');
    $delimiter = !empty($data['delimiter']) ? $data['delimiter'] : ';';
    $fileSiteDate = str_replace(['/', '.', ':'], '_', esc_html(get_bloginfo('name')) . ' - ' . date(GMP_DATE_FORMAT_HIS));
    $fileName = sprintf(__('Figures from %s', GMP_LANG_CODE), $fileSiteDate);
    if (!frameGmp::_()->getModule('shape')) {
      _e('This feature requires the PRO version.', GMP_LANG_CODE);
      exit();
    }
    $figures = frameGmp::_()->getModule('shape')->getModel()->exportGetAllShapes();
    if (empty($figures)) {
      _e('You have no figures for now.', GMP_LANG_CODE);
      exit();
    }
    $this->_connectCsvLib();
    $csvGenerator = toeCreateObjGmp('csvgeneratorGmp', [$fileName]);
    if (!empty($data['delimiter'])) {
      $csvGenerator->setDelimiter($delimiter);
    }
    $c = $r = 0;
    $keys = ['id', 'title', 'description', 'type', 'map_id', 'create_date', 'animation', 'sort_order'];
    $figures_keys = [];
    foreach ($figures as $m) {
      $figures_keys = array_unique(array_merge($figures_keys, $this->_getKeys($m)));
    }
    sort($figures_keys);
    $keys = $this->_dropParentKeys(array_unique(array_merge($keys, $figures_keys)));
    foreach ($keys as $k) {
      $csvGenerator->addCell($r, $c, $k);
      $c++;
    }
    $c = 0;
    $r = 1;
    foreach ($figures as $figure) {
      $c = 0;
      foreach ($keys as $k) {
        $figureValue = $this->_prepareValueToExport($this->_getKeyVal($figure, $k));
        $csvGenerator->addCell($r, $c, $figureValue);
        $c++;
      }
      $r++;
    }
    $csvGenerator->generate();
    exit();
  }
  public function exportHeatmap()
  {
    $data = reqGmp::get('get');
    $delimiter = !empty($data['delimiter']) ? $data['delimiter'] : ';';
    $fileSiteDate = str_replace(['/', '.', ':'], '_', esc_html(get_bloginfo('name')) . ' - ' . date(GMP_DATE_FORMAT_HIS));
    $fileName = sprintf(__('Heatmap from %s', GMP_LANG_CODE), $fileSiteDate);
    if (!frameGmp::_()->getModule('heatmap')) {
      _e('This feature requires the PRO version.', GMP_LANG_CODE);
      exit();
    }
    $heatmaps = frameGmp::_()->getModule('heatmap')->getModel()->getAllHeatmap();
    if (empty($heatmaps)) {
      _e('You have no heatmap for now.', GMP_LANG_CODE);
      exit();
    }
    $this->_connectCsvLib();
    $csvGenerator = toeCreateObjGmp('csvgeneratorGmp', [$fileName]);
    if (!empty($data['delimiter'])) {
      $csvGenerator->setDelimiter($delimiter);
    }
    $c = $r = 0;
    $keys = ['id', 'map_id'];
    $heatmap_keys = [];
    foreach ($heatmaps as $m) {
      $heatmap_keys = array_unique(array_merge($heatmap_keys, $this->_getKeys($m)));
    }
    sort($heatmap_keys);
    $keys = $this->_dropParentKeys(array_unique(array_merge($keys, $heatmap_keys)));
    foreach ($keys as $k) {
      $csvGenerator->addCell($r, $c, $k);
      $c++;
    }
    $c = 0;
    $r = 1;

    foreach ($heatmaps as $heatmap) {
      $c = 0;
      foreach ($keys as $k) {
        $heatmapValue = $this->_prepareValueToExport($this->_getKeyVal($heatmap, $k));
        $csvGenerator->addCell($r, $c, $heatmapValue);
        $c++;
      }
      $r++;
    }
    $csvGenerator->generate();
    exit();
  }
  public function import()
  {
    $data = reqGmp::get('post');
    @ini_set('auto_detect_line_endings', true);
    $res = new responseGmp();
    $this->_connectCsvLib();
    $csvGenerator = toeCreateObjGmp('csvgeneratorGmp', ['']);
    $type = reqGmp::getVar('type');

    if (reqGmp::getVar('csv_import_file_maps', 'file')) {
      $file = reqGmp::getVar('csv_import_file_maps', 'file');
      $type = 'maps';
    } elseif (reqGmp::getVar('csv_import_file_markers', 'file')) {
      $file = reqGmp::getVar('csv_import_file_markers', 'file');
      $type = 'markers';
    } elseif (reqGmp::getVar('csv_import_file_figures', 'file')) {
      $file = reqGmp::getVar('csv_import_file_figures', 'file');
      $type = 'figures';
    } elseif (reqGmp::getVar('csv_import_file_heatmap', 'file')) {
      $file = reqGmp::getVar('csv_import_file_heatmap', 'file');
      $type = 'heatmap';
    }

    if (empty($file) || empty($file['size'])) {
      $res->pushError(__('Missing File', GMP_LANG_CODE));
    }
    if (!empty($file['error'])) {
      $res->pushError(sprintf(__('File uploaded with error code %s', $file['error'])));
    }
    if (!$res->error()) {
      $fileArray = [];
      $content = (string) file_get_contents($file['tmp_name']);
      // Excel "CSV UTF-8" starts with a BOM: it stuck to the first column name, "id" was not found
      // and every row was added again
      if (strpos($content, "\xEF\xBB\xBF") === 0) {
        $content = substr($content, 3);
      }
      // Excel "CSV" is saved in the system code page: rows with non-Latin text were not saved at all
      if (!$this->_isUtf8($content)) {
        $content = $this->_toUtf8($content);
      }
      $csvParams['delimiter'] = $this->_detectDelimiter($content, !empty($data['delimiter']) ? $data['delimiter'] : $csvGenerator->getDelimiter());
      $handle = fopen('php://temp', 'w+');
      fwrite($handle, $content);
      rewind($handle);
      while (($row = fgetcsv($handle, 0, $csvParams['delimiter'], '"', '\\')) !== false) {
        // Empty lines, and the rows Excel keeps after the data (";;;;"), were counted as failed rows
        if (!array_filter($row, [$this, '_isNotEmptyCell'])) {
          continue;
        }
        $fileArray[] = $row;
      }
      fclose($handle);
      /*else
       while($row = @fgetcsv( $handle, 0, $csvParams['delimiter'], $csvParams['enclosure'], $csvParams['escape'] )) $fileArray[] = $row;*/
      //			/*var_dump($fileArray);
      //			exit();*/
      //			var_dump($fileArray);
      //			exit();
      if (!empty($fileArray)) {
        if (count($fileArray) > 1) {
          //$overwriteSameNames = (int) reqGmp::getVar('overwrite_same_names');
          $keys = array_map('trim', array_shift($fileArray));
          $imported = 0;
          $failReasons = [];
          switch ($type) {
            case 'maps':
              $mapModel = frameGmp::_()->getModule('gmap')->getModel();
              foreach ($fileArray as $i => $row) {
                $map = [];
                foreach ($keys as $j => $key) {
                  $value = $this->_prepareValueToImport(isset($row[$j]) ? $row[$j] : '');
                  if ($value === '') {
                    $value = null;
                  }
                  // "params.zoom" and "html_options.width" are saved as "zoom" and "width", nested options
                  // ("params.selectors.content_before", "params.markers_list_autoplay.enable") stay arrays:
                  // only "map_center" was kept as an array, the others were lost
                  $realKeys = explode('.', $key);
                  if (count($realKeys) > 1 && in_array($realKeys[0], ['params', 'html_options'])) {
                    array_shift($realKeys);
                  }
                  $this->_setKeyVal($map, implode('.', $realKeys), $value);
                }
                // Options the map does not use come as empty cells ("params.kml_file_url.0"):
                // they gave [null] where the map had no value, and the KML script failed on it
                $map = $this->_dropEmptyArrays($map);
                if (isset($map['id']) && $mapModel->existsId($map['id'])) {
                  $mapModel->updateMap($map);
                  $imported++;
                } else {
                  $originalMapId = isset($map['id']) ? (int) $map['id'] : 0;
                  if (isset($map['id'])) {
                    unset($map['id']);
                  }
                  $newMapId = $mapModel->saveNewMap($map);
                  if ($newMapId) {
                    $imported++;
                  }
                  // The map keeps its id from the file, markers in a markers file refer to it.
                  // dbGmp::query() is a stub, the id was never set.
                  if ($newMapId && $originalMapId) {
                    global $wpdb;
                    if ($wpdb->update($wpdb->prefix . 'gmp_maps', ['id' => $originalMapId], ['id' => $newMapId]) && $originalMapId > $newMapId) {
                      $wpdb->query('ALTER TABLE ' . $wpdb->prefix . 'gmp_maps AUTO_INCREMENT = ' . ($originalMapId + 1));
                    }
                  }
                }
              }
              break;
            case 'markers':
              $markerModel = frameGmp::_()->getModule('marker')->getModel();
              foreach ($fileArray as $i => $row) {
                $marker = [];
                foreach ($keys as $j => $key) {
                  $this->_setKeyVal($marker, $key, $this->_prepareValueToImport(isset($row[$j]) ? $row[$j] : ''));
                }
                if (isset($marker['id']) && !$markerModel->existsId($marker['id'])) {
                  unset($marker['id']);
                }
                // Excel saves dates in the system format ("25.10.2026 14:02"): MySQL got 0000-00-00 00:00:00
                if (isset($marker['create_date'])) {
                  $time = $marker['create_date'] !== '' ? strtotime($marker['create_date']) : false;
                  if ($time) {
                    $marker['create_date'] = date('Y-m-d H:i:s', $time);
                  } else {
                    unset($marker['create_date']);
                  }
                }
                // Categories go to save() as a list, like from the marker form. The file has the list in
                // "marker_group_ids" and the first one in "marker_group_id": new markers got no category
                // and markers with several categories kept only the first one.
                $groupIds = isset($marker['marker_group_ids']) && is_array($marker['marker_group_ids']) ? array_values(array_filter(array_map('intval', $marker['marker_group_ids']))) : [];
                if (!$groupIds && !empty($marker['marker_group_id'])) {
                  $groupIds = array_values(array_filter(array_map('intval', (array) $marker['marker_group_id'])));
                }
                $marker['marker_group_id'] = $groupIds ? $groupIds : 0;
                unset($marker['marker_group_ids']);
                if ($markerModel->save($marker)) {
                  $imported++;
                }
              }
              $failReasons = $markerModel->getErrors();
              break;
            case 'figures':
              if (!frameGmp::_()->getModule('shape')) {
                $res->pushError(__('Figures import is available in the PRO version', GMP_LANG_CODE));
                break;
              }
              $figuresModel = frameGmp::_()->getModule('shape')->getModel();
              foreach ($fileArray as $i => $row) {
                $figures = [];
                foreach ($keys as $j => $key) {
                  if (!empty($row[$j])) {
                    $this->_setKeyVal($figures, $key, $this->_prepareValueToImport($row[$j]));
                  }
                }
                if (isset($figures['id']) && !$figuresModel->existsId($figures['id'])) {
                  unset($figures['id']);
                }
                if ($figuresModel->save($figures)) {
                  $imported++;
                }
              }
              $failReasons = $figuresModel->getErrors();
              break;
            case 'heatmap':
              if (!frameGmp::_()->getModule('heatmap')) {
                $res->pushError(__('Heatmap import is available in the PRO version', GMP_LANG_CODE));
                break;
              }
              $heatmapModel = frameGmp::_()->getModule('heatmap')->getModel();
              foreach ($fileArray as $i => $row) {
                $heatmap = [];
                foreach ($keys as $j => $key) {
                  if (!empty($row[$j])) {
                    $this->_setKeyVal($heatmap, $key, $this->_prepareValueToImport($row[$j]));
                  }
                }
                if (isset($heatmap['id']) && !$heatmapModel->existsId($heatmap['id'])) {
                  unset($heatmap['id']);
                }
                if ($heatmapModel->save($heatmap)) {
                  $imported++;
                }
              }
              break;
          }
          if (!$res->error()) {
            $res->addMessage(sprintf(__('Imported %d of %d rows', GMP_LANG_CODE), $imported, count($fileArray)));
            // Why the other rows were not imported, the message gave only the count
            $failReasons = array_unique(array_filter(array_map('strval', array_filter((array) $failReasons, 'is_scalar'))));
            if ($imported < count($fileArray)) {
              $res->addMessage($failReasons ? sprintf(__('Not imported: %s', GMP_LANG_CODE), implode('; ', $failReasons)) : sprintf(__('Check that the file uses the "%s" delimiter and has the column names in the first row.', GMP_LANG_CODE), $csvParams['delimiter']));
            }
          }
          /*$importRes = $this->getModel()->import($fileArray, $overwriteSameNames);
					if($importRes) {
						if($importRes['map']['added'])
							$res->addMessage (sprintf(__('Added %s maps', GMP_LANG_CODE), $importRes['map']['added']));
						if($importRes['map']['updated'])
							$res->addMessage (sprintf(__('Updated %s maps', GMP_LANG_CODE), $importRes['map']['added']));
						if($importRes['marker']['added'])
							$res->addMessage (sprintf(__('Added %s markers', GMP_LANG_CODE), $importRes['map']['added']));
						if($importRes['marker']['updated'])
							$res->addMessage (sprintf(__('Updated %s markers', GMP_LANG_CODE), $importRes['map']['added']));
					} else
						$res->pushError ($this->getModel()->getErrors());*/
        } else {
          $res->pushError(__('File should contain more then 1 row, at least 1 row should be for headers', GMP_LANG_CODE));
        }
      } else {
        $res->pushError(__('Empty data in file', GMP_LANG_CODE));
      }
    }
    $res->ajaxExec();
  }
  public function saveCsvOptions()
  {
    $res = new responseGmp();
    if (frameGmp::_()->getModule('options')->getModel()->saveGroup(reqGmp::get('post'))) {
      $res->addMessage(__('Done', GMP_LANG_CODE));
    } else {
      $res->pushError($this->getModel('options')->getErrors());
    }
    return $res->ajaxExec();
  }
  private function _connectCsvLib()
  {
    importClassGmp('filegeneratorGmp');
    importClassGmp('csvgeneratorGmp');
  }
  private function _getSitePath()
  {
    return $this->getModel()->getSitePath();
  }
  private function _getKeys($data, $prefix = [])
  {
    $keys = [];
    foreach ($data as $k => $v) {
      if (is_array($v)) {
        $newPrefix = $prefix;
        $newPrefix[] = $k;
        $keys = array_merge($keys, $this->_getKeys($v, $newPrefix));
      } else {
        $keys[] = empty($prefix) ? $k : implode('.', $prefix) . '.' . $k;
      }
    }
    return $keys;
  }
  private function _isNotEmptyCell($value)
  {
    return trim((string) $value) !== '';
  }
  /**
   * Removes nested arrays that hold only empty values.
   */
  private function _dropEmptyArrays($data)
  {
    foreach ($data as $key => $value) {
      if (is_array($value)) {
        $value = $this->_dropEmptyArrays($value);
        if (array_filter($value, [$this, '_isNotEmptyValue'])) {
          $data[$key] = $value;
        } else {
          unset($data[$key]);
        }
      }
    }
    return $data;
  }
  private function _isNotEmptyValue($value)
  {
    return is_array($value) || ($value !== null && $value !== '');
  }
  private function _isUtf8($content)
  {
    return function_exists('mb_check_encoding') ? mb_check_encoding($content, 'UTF-8') : (bool) preg_match('//u', $content);
  }
  /**
   * A file in the Windows code page of Excel. Cyrillic words are runs of bytes 0xC0-0xFF,
   * in Western texts such letters stand alone ("café"), so that tells Windows-1251 from Windows-1252.
   */
  private function _toUtf8($content)
  {
    $from = preg_match('/[\xC0-\xFF]{3,}/', $content) ? 'Windows-1251' : 'Windows-1252';
    if (function_exists('mb_convert_encoding')) {
      return mb_convert_encoding($content, 'UTF-8', $from);
    }
    $converted = function_exists('iconv') ? @iconv($from, 'UTF-8//IGNORE', $content) : false;
    return $converted !== false ? $converted : $content;
  }
  /**
   * The delimiter of the file: Excel uses "," or ";" depending on the system settings, with the other one
   * selected in CSV Options nothing was imported
   */
  private function _detectDelimiter($content, $preferred)
  {
    $firstLine = strtok($content, "\n");
    if ($firstLine === false || strpos($firstLine, $preferred) !== false) {
      return $preferred;
    }
    $best = $preferred;
    $bestCount = 0;
    foreach ([';', ',', "\t"] as $candidate) {
      $count = substr_count($firstLine, $candidate);
      if ($count > $bestCount) {
        $best = $candidate;
        $bestCount = $count;
      }
    }
    return $best;
  }
  /**
   * An option empty for one row and an array for another gives both "a" and "a.b" keys:
   * "a" exported the array as "Array" and on import it overwrote "a.b". Keep the nested keys only.
   */
  private function _dropParentKeys($keys)
  {
    $res = [];
    foreach ($keys as $key) {
      $isParent = false;
      foreach ($keys as $other) {
        if (strpos($other, $key . '.') === 0) {
          $isParent = true;
          break;
        }
      }
      if (!$isParent) {
        $res[] = $key;
      }
    }
    return $res;
  }
  private function _getKeyVal($data, $key)
  {
    if (strpos($key, '.')) {
      $keys = explode('.', $key);
      $firstKey = array_shift($keys);
      return isset($data[$firstKey]) ? $this->_getKeyVal($data[$firstKey], implode('.', $keys)) : '';
    } else {
      return isset($data[$key]) ? $data[$key] : '';
    }
  }
  private function _setKeyVal(&$data, $key, $val)
  {
    if (strpos($key, '.')) {
      $keys = explode('.', $key);
      $firstKey = array_shift($keys);
      if (!isset($data[$firstKey]) || !is_array($data[$firstKey])) {
        $data[$firstKey] = [];
      }
      $this->_setKeyVal($data[$firstKey], implode('.', $keys), $val);
    } else {
      $data[$key] = $val;
    }
  }
  private function _prepareValueToExport($val)
  {
    if (is_array($val)) {
      $val = implode(';', $val);
    }
    $sitePath = $this->_getSitePath();
    return htmlspecialchars(str_replace($sitePath, '[GMP_SITE_PATH]', $val));
  }
  private function _prepareValueToImport($val)
  {
    $sitePath = $this->_getSitePath();
    // No addslashes(): values from the file are saved through $wpdb, "O'Brien" was saved as "O\'Brien"
    return str_replace('[GMP_SITE_PATH]', $sitePath, htmlspecialchars_decode($val));
  }
  private function _toYesNo($val)
  {
    return empty($val) ? 'No' : 'Yes';
  }
  private function _fromYesNo($val)
  {
    return $val === 'No' ? 0 : 1;
  }
  /**
   * @see controller::getPermissions();
   */
  public function getNoncedMethods()
  {
    return ['exportMaps', 'exportMarkers', 'exportFigures', 'exportHeatmap', 'import', 'saveCsvOptions'];
  }
  public function getPermissions()
  {
    return [
      GMP_USERLEVELS => [
        GMP_ADMIN => ['exportMaps', 'exportMarkers', 'exportFigures', 'exportHeatmap', 'import', 'saveCsvOptions'],
      ],
    ];
  }
}
