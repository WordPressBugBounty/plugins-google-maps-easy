<section>
  <div class="supsystic-item supsystic-panel gmp-form-page gmp-csv-page">
    <div id="containerWrapper">
      <div class="gmp-page-head">
        <div class="gmp-page-title">
          <span class="gmp-page-icon"><i class="fa fa-exchange" aria-hidden="true"></i></span>
          <div>
            <h2><?php esc_html_e('Import / Export', GMP_LANG_CODE); ?></h2>
            <p><?php esc_html_e('Move maps, markers, figures and heatmaps between sites with CSV files.', GMP_LANG_CODE); ?></p>
          </div>
        </div>
      </div>
      <div class="gmp-cards">
      <div class="gmp-form-card">
        <div class="gmp-form-card-head">
          <span class="gmp-form-card-icon"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span>
          <div>
            <h3><?php esc_html_e('Import and export', GMP_LANG_CODE); ?></h3>
            <p><?php esc_html_e('Export saves a CSV file; Import adds the rows of a CSV file.', GMP_LANG_CODE); ?></p>
          </div>
        </div>
      <table class="form-table">
        <tr>
          <th scope="row">
            <label>
              <?php _e('Maps', GMP_LANG_CODE); ?>
            </label>
          </th>
          <td>
            <button id="gmpCsvExportMapsBtn" class="button">
              <?php _e('Export', GMP_LANG_CODE); ?>
            </button>
            <?php echo htmlGmp::ajaxfile('csv_import_file_maps', [
              'url' => uriGmp::_(['baseUrl' => admin_url('admin-ajax.php'), 'page' => 'csv', 'action' => 'import', 'type' => 'maps', 'reqType' => 'ajax', '_wpnonce' => wp_create_nonce('gmp_nonce')]),
              'data' => 'gmpCsvImportData',
              'buttonName' => __('Import', GMP_LANG_CODE),
              'responseType' => 'json',
              'onSubmit' => 'gmpCsvImportOnSubmit',
              'onComplete' => 'gmpCsvImportOnComplete',
              'btn_class' => 'button',
            ]); ?>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="gmpCsvExportMarkersBtn">
              <?php _e('Markers', GMP_LANG_CODE); ?>
            </label>
          </th>
          <td>
            <button id="gmpCsvExportMarkersBtn" class="button">
              <?php _e('Export', GMP_LANG_CODE); ?>
            </button>
            <?php echo htmlGmp::ajaxfile('csv_import_file_markers', [
              'url' => uriGmp::_(['baseUrl' => admin_url('admin-ajax.php'), 'page' => 'csv', 'action' => 'import', 'type' => 'markers', 'reqType' => 'ajax', '_wpnonce' => wp_create_nonce('gmp_nonce')]),
              'data' => 'gmpCsvImportData',
              'buttonName' => __('Import', GMP_LANG_CODE),
              'responseType' => 'json',
              'onSubmit' => 'gmpCsvImportOnSubmit',
              'onComplete' => 'gmpCsvImportOnComplete',
              'btn_class' => 'button',
            ]); ?>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="gmpCsvExportFiguresBtn">
              <?php _e('Figures', GMP_LANG_CODE); ?>
            </label>
          </th>
          <td>
            <?php if (!$this->hasFigures) { ?>
              <span class="gmpProOptMiniLabel"><a target="_blank" href="<?php echo esc_attr($this->proLink); ?>"><?php _e('PRO option', GMP_LANG_CODE); ?></a></span>
            <?php } else { ?>
            <button id="gmpCsvExportFiguresBtn" class="button">
              <?php _e('Export', GMP_LANG_CODE); ?>
            </button>
            <?php echo htmlGmp::ajaxfile('csv_import_file_figures', [
              'url' => uriGmp::_(['baseUrl' => admin_url('admin-ajax.php'), 'page' => 'csv', 'action' => 'import', 'type' => 'figures', 'reqType' => 'ajax', '_wpnonce' => wp_create_nonce('gmp_nonce')]),
              'data' => 'gmpCsvImportData',
              'buttonName' => __('Import', GMP_LANG_CODE),
              'responseType' => 'json',
              'onSubmit' => 'gmpCsvImportOnSubmit',
              'onComplete' => 'gmpCsvImportOnComplete',
              'btn_class' => 'button',
            ]); ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="gmpCsvExportHeatMapBtn">
              <?php _e('HeatMap Layer', GMP_LANG_CODE); ?>
            </label>
          </th>
          <td>
            <?php if (!$this->hasHeatmap) { ?>
              <span class="gmpProOptMiniLabel"><a target="_blank" href="<?php echo esc_attr($this->proLink); ?>"><?php _e('PRO option', GMP_LANG_CODE); ?></a></span>
            <?php } else { ?>
            <button id="gmpCsvExportHeatmapBtn" class="button">
              <?php _e('Export', GMP_LANG_CODE); ?>
            </button>
            <?php echo htmlGmp::ajaxfile('csv_import_file_heatmap', [
              'url' => uriGmp::_(['baseUrl' => admin_url('admin-ajax.php'), 'page' => 'csv', 'action' => 'import', 'type' => 'heatmap', 'reqType' => 'ajax', '_wpnonce' => wp_create_nonce('gmp_nonce')]),
              'data' => 'gmpCsvImportData',
              'buttonName' => __('Import', GMP_LANG_CODE),
              'responseType' => 'json',
              'onSubmit' => 'gmpCsvImportOnSubmit',
              'onComplete' => 'gmpCsvImportOnComplete',
              'btn_class' => 'button',
            ]); ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="gmpCsvImportMsg"></div>
          </td>
        </tr>
      </table>
      </div>
      <div class="gmp-form-card">
        <div class="gmp-form-card-head">
          <span class="gmp-form-card-icon"><i class="fa fa-sliders" aria-hidden="true"></i></span>
          <div>
            <h3><?php _e('CSV Options', GMP_LANG_CODE); ?></h3>
            <p><?php esc_html_e('How the values of a CSV file are separated.', GMP_LANG_CODE); ?></p>
          </div>
        </div>
      <form id="gmpCsvForm">
        <table class="form-table no-border">
          <tr>
            <th scope="row">
              <label for="gmpCsvExportDelimiter">
                <?php _e('Delimiter', GMP_LANG_CODE); ?>
              </label>
            </th>
            <td>
              <?php echo htmlGmp::selectbox('opt_values[csv_options][delimiter]', [
                'options' => $this->delimiters,
                'value' => !empty($this->options['delimiter']) ? $this->options['delimiter'] : ';',
                'attrs' => 'style="min-width: 150px;" id="gmpCsvExportDelimiter"',
              ]); ?>
            </td>
          </tr>
        </table>
        <?php echo htmlGmp::hidden('page', ['value' => 'csv']); ?>
        <?php echo htmlGmp::hidden('action', ['value' => 'saveCsvOptions']); ?>
        <?php echo htmlGmp::defaultNonceForAdminPanel(); ?>
      </form>
      <p class="gmp-card-actions">
      <button id="gmpCsvSaveBtn" class="button button-primary">
        <i class="fa fa-save"></i>
        <?php _e('Save', GMP_LANG_CODE); ?>
      </button>
      </p>
      </div>
      </div>
    </div>
  </div>
</section>