<section>
	<div class="supsystic-item supsystic-panel gmp-form-page gmp-mgr-form gmp-mgr-options">
		<div id="containerWrapper">
			<div class="gmp-page-head">
				<div class="gmp-page-title">
					<span class="gmp-page-icon"><i class="fa fa-sliders" aria-hidden="true"></i></span>
					<div>
						<h2><?php esc_html_e('Category options', GMP_LANG_CODE); ?></h2>
						<p><?php esc_html_e('Font sizes of the category levels in the filter of the map.', GMP_LANG_CODE); ?></p>
					</div>
				</div>
				<div class="gmp-page-actions">
					<a class="button button-table-action" id="addMarkerGroup" href="<?php echo esc_url($this->_getPageLink('marker_groups_add_new')); ?>">
						<i class="fa fa-plus" aria-hidden="true"></i>
						<?php esc_html_e('Add Category', GMP_LANG_CODE); ?>
					</a>
					<button class="button button-primary" id="gmpMgrSaveBtn">
						<i class="fa fa-save" aria-hidden="true"></i>
						<?php esc_html_e('Save', GMP_LANG_CODE); ?>
					</button>
				</div>
			</div>
			<div id="gmpMgrTab" class="mgrTabContent">
				<form id="gmpMgrForm">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="marker_group_title"><?php esc_html_e('Font Size', GMP_LANG_CODE); ?>:</label>
								<i class="fa fa-question supsystic-tooltip" title="<?php esc_attr_e('Set font size in pixels for every level of Categories Tree starting from first level.', GMP_LANG_CODE); ?>"></i>
							</th>
							<td>
								<div class="gmp-mgr-level-actions">
									<a href="#" class="button mgrFontSizeLevelAddBtn">
										<i class="fa fa-plus" aria-hidden="true"></i>
										<?php esc_html_e('Add Level', GMP_LANG_CODE); ?>
									</a>
									<a href="#" class="button mgrFontSizeLevelClearBtn">
										<i class="fa fa-eraser" aria-hidden="true"></i>
										<?php esc_html_e('Clear', GMP_LANG_CODE); ?>
									</a>
								</div>
								<div id="mgrFontSizeLevelExample" class="mgrFontSizeLevel" style="display: none;">
									<i class="fa fa-arrows-v mgrMoveHandle"></i>
									<input type="text" name="options[lvl_font_size][]" placeholder="14" disabled="disabled" />
									<a href="#" class="button mgrFontSizeLevelRemoveBtn" title="<?php esc_attr_e('Remove', GMP_LANG_CODE); ?>">
										<i class="fa fa-trash-o"></i>
									</a>
								</div>
								<div id="mgrFontSizeLevelsOuterShell">
									<div id="mgrFontSizeLevelsShell">
										<?php if (!empty($this->options['lvl_font_size'])) { ?>
											<?php foreach ($this->options['lvl_font_size'] as $l) { ?>
												<div class="mgrFontSizeLevel">
													<i class="fa fa-arrows-v mgrMoveHandle"></i>
													<input type="text" name="options[lvl_font_size][]" value="<?php echo esc_attr($l); ?>" placeholder="14" />
													<a href="#" class="button mgrFontSizeLevelRemoveBtn" title="<?php esc_attr_e('Remove', GMP_LANG_CODE); ?>">
														<i class="fa fa-trash-o"></i>
													</a>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('mod', ['value' => 'marker_groups'])); ?>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('action', ['value' => 'saveMarkerGroupsOptions'])); ?>
					<?php echo htmlGmp::defaultNonceForAdminPanel(); ?>
				</form>
			</div>
		</div>
	</div>
</section>
