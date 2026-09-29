<?php
$list = $this->list;
?>
<section id="gmp-maps-list">
  <div class="supsystic-item supsystic-panel gmp-list-panel">
    <div class="gmp-table-toolbar">
      <div class="gmp-table-toolbar-left">
        <a class="button button-primary gmp-toolbar-button" href="<?php echo esc_url($this->addNewLink); ?>">
          <i class="fa fa-plus" aria-hidden="true"></i> <?php esc_html_e('Add New Map', GMP_LANG_CODE); ?>
        </a>
        <button type="button" class="button gmp-toolbar-button" id="gmpGmapCloneGroupBtn" disabled>
          <i class="fa fa-clone" aria-hidden="true"></i> <?php esc_html_e('Clone selected', GMP_LANG_CODE); ?>
        </button>
        <button type="button" class="button gmp-toolbar-button" id="gmpGmapRemoveGroupBtn" disabled>
          <i class="fa fa-trash-o" aria-hidden="true"></i> <?php esc_html_e('Delete selected', GMP_LANG_CODE); ?>
        </button>
        <label class="gmp-tables-search">
          <span class="screen-reader-text"><?php esc_html_e('Search maps', GMP_LANG_CODE); ?></span>
          <input id="gmpGmapTblSearchTxt" type="search" placeholder="<?php esc_attr_e('Search maps by title or ID...', GMP_LANG_CODE); ?>" autocomplete="off">
        </label>
      </div>
      <div class="gmp-pagination">
        <span id="gmp-pagination-info" class="gmp-pagination-info" role="status" aria-live="polite"></span>
        <div class="gmp-pagination-controls">
          <button type="button" class="gmp-page-btn" id="gmp-prev-page" aria-label="<?php esc_attr_e('Previous page', GMP_LANG_CODE); ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
          <span id="gmp-page-numbers" class="gmp-page-numbers"></span>
          <button type="button" class="gmp-page-btn" id="gmp-next-page" aria-label="<?php esc_attr_e('Next page', GMP_LANG_CODE); ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
        </div>
        <label class="gmp-per-page-label" for="gmp-per-page"><?php esc_html_e('Rows', GMP_LANG_CODE); ?>
          <select id="gmp-per-page"><option value="10">10</option><option value="20" selected>20</option><option value="50">50</option><option value="100">100</option></select>
        </label>
      </div>
    </div>
    <div class="gmp-table-scroll">
      <table id="gmp-maps-table" class="gmp-maps-table">
        <thead><tr>
          <th scope="col" class="gmp-col-check"><input id="gmp-select-all" type="checkbox" class="gmp-native-check" aria-label="<?php esc_attr_e('Select all maps on this page', GMP_LANG_CODE); ?>"></th>
          <th scope="col" class="gmp-col-id gmp-sortable" id="gmp-sort-id" tabindex="0"><?php esc_html_e('ID', GMP_LANG_CODE); ?><i class="fa fa-sort gmp-sort-icon" aria-hidden="true"></i></th>
          <th scope="col" class="gmp-col-title gmp-sortable" id="gmp-sort-title" tabindex="0"><?php esc_html_e('Title', GMP_LANG_CODE); ?><i class="fa fa-sort gmp-sort-icon" aria-hidden="true"></i></th>
          <th scope="col" class="gmp-col-markers gmp-sortable" id="gmp-sort-markers" tabindex="0"><?php esc_html_e('Markers', GMP_LANG_CODE); ?><i class="fa fa-sort gmp-sort-icon" aria-hidden="true"></i></th>
          <th scope="col" class="gmp-col-created gmp-sortable" id="gmp-sort-create_date" tabindex="0"><?php esc_html_e('Created', GMP_LANG_CODE); ?><i class="fa fa-sort gmp-sort-icon" aria-hidden="true"></i></th>
          <th scope="col" class="gmp-col-code"><?php esc_html_e('Shortcode', GMP_LANG_CODE); ?></th>
          <th scope="col" class="gmp-col-code"><?php esc_html_e('PHP code', GMP_LANG_CODE); ?></th>
          <th scope="col" class="gmp-col-actions"><?php esc_html_e('Actions', GMP_LANG_CODE); ?></th>
        </tr></thead>
        <tbody id="gmp-maps-tbody"><?php echo $this->getAdminListRows($list['rows']); ?></tbody>
      </table>
    </div>
    <div id="gmp-list-status" class="gmp-list-status" role="status" aria-live="polite"></div>
  </div>
</section>
