<section>
	<div class="supsystic-item supsystic-panel gmp-form-page gmp-mgr-page">
		<div id="containerWrapper">
			<div class="gmp-page-head">
				<div class="gmp-page-title">
					<span class="gmp-page-icon"><i class="fa fa-map-pin" aria-hidden="true"></i></span>
					<div>
						<h2><?php esc_html_e('Marker Categories', GMP_LANG_CODE); ?></h2>
						<p><?php esc_html_e('Group your markers, give each group its color and cluster icon and let visitors filter the map by them.', GMP_LANG_CODE); ?></p>
					</div>
				</div>
				<div class="gmp-page-actions">
					<a class="button button-table-action" id="markerGroupOptions" href="<?php echo esc_url($this->_getPageLink('marker_groups_options')); ?>" title="<?php esc_attr_e('Options', GMP_LANG_CODE); ?>">
						<i class="fa fa-sliders" aria-hidden="true"></i>
						<?php esc_html_e('Options', GMP_LANG_CODE); ?>
					</a>
					<a class="button button-primary button-table-action" id="addMarkerGroup" href="<?php echo esc_url($this->_getPageLink('marker_groups_add_new')); ?>" title="<?php esc_attr_e('Add Category', GMP_LANG_CODE); ?>">
						<i class="fa fa-plus" aria-hidden="true"></i>
						<?php esc_html_e('Add Category', GMP_LANG_CODE); ?>
					</a>
				</div>
			</div>
			<div class="gmp-form-card gmp-mgr-card">
				<ul class="supsystic-bar-controls">
					<li title="<?php esc_attr_e('Search', GMP_LANG_CODE); ?>" class="gmp-mgr-search">
						<i class="fa fa-search" aria-hidden="true"></i>
						<input id="gmpMgrTblSearchTxt" type="text" name="tbl_search" placeholder="<?php esc_attr_e('Search', GMP_LANG_CODE); ?>">
					</li>
					<li title="<?php esc_attr_e('Delete selected', GMP_LANG_CODE); ?>">
						<button class="button" id="gmpMgrRemoveGroupBtn" disabled data-toolbar-button>
							<i class="fa fa-trash" aria-hidden="true"></i>
							<?php esc_html_e('Delete Selected', GMP_LANG_CODE); ?>
						</button>
					</li>
					<li title="<?php esc_attr_e('Clear All', GMP_LANG_CODE); ?>">
						<button class="button" id="gmpMgrClearBtn" data-toolbar-button>
							<i class="fa fa-eraser" aria-hidden="true"></i>
							<?php esc_html_e('Clear All', GMP_LANG_CODE); ?>
						</button>
					</li>
				</ul>
				<div id="gmpMgrTblNavShell" class="supsystic-tbl-pagination-shell"></div>
				<div style="clear: both;"></div>
				<div id="gmpMgrTbl"></div>
				<div id="gmpMgrTblNav"></div>
				<div id="gmpMgrTblEmptyMsg" class="gmp-empty" style="display: none;">
					<span class="gmp-empty-icon"><i class="fa fa-map-pin" aria-hidden="true"></i></span>
					<h3><?php esc_html_e('You have no marker categories yet', GMP_LANG_CODE); ?></h3>
					<p><?php printf(
       wp_kses(__("<a href='%s'>Create</a> your first category, then pick it for your markers in the map editor.", GMP_LANG_CODE), ['a' => ['href' => []]]),
       esc_url($this->_getPageLink('marker_groups_add_new'))
     ); ?></p>
				</div>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
</section>
