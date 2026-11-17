<section>
	<div class="supsystic-item supsystic-panel gmp-form-page gmp-mgr-form">
		<div id="containerWrapper">
			<div class="gmp-page-head">
				<div class="gmp-page-title">
					<span class="gmp-page-icon"><i class="fa fa-map-pin" aria-hidden="true"></i></span>
					<div>
						<h2><?php echo $this->editMarkerGroup ? esc_html__('Edit Category', GMP_LANG_CODE) : esc_html__('New Category', GMP_LANG_CODE); ?></h2>
						<p><?php esc_html_e('The name, color and cluster icon of the category, as visitors see them on the map and in its filter.', GMP_LANG_CODE); ?></p>
					</div>
				</div>
				<div class="gmp-page-actions">
					<a href="<?php echo esc_url($this->_getPageLink('marker_groups_options')); ?>" class="button button-table-action" id="markerGroupOptions" style="display: <?php echo $this->editMarkerGroup ? 'inline-flex;' : 'none;'; ?>">
						<i class="fa fa-sliders" aria-hidden="true"></i>
						<?php esc_html_e('Options', GMP_LANG_CODE); ?>
					</a>
					<a href="<?php echo esc_url($this->_getPageLink('marker_groups_add_new')); ?>" class="button button-table-action" id="addMarkerGroup" style="display: <?php echo $this->editMarkerGroup ? 'inline-flex;' : 'none;'; ?>">
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
								<label for="marker_group_title">
									<?php esc_html_e('Category Title', GMP_LANG_CODE); ?>:
								</label>
							</th>
							<td>
								<?php echo htmlGmp::wpKsesHtml(
          htmlGmp::text('marker_group[title]', [
            'value' => $this->editMarkerGroup ? esc_attr($this->marker_group['title']) : '',
            'attrs' => 'style="width: 50%;" id="marker_group_title"',
            'required' => true,
          ]),
        ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="marker_group_parent">
									<?php esc_html_e('Parent Category', GMP_LANG_CODE); ?>:
								</label>
							</th>
							<td>
								<?php echo htmlGmp::wpKsesHtml(
          htmlGmp::selectbox('marker_group[parent]', [
            'options' => $this->parentsList,
            'value' => $this->editMarkerGroup ? esc_attr($this->marker_group['parent']) : 0,
            'attrs' => 'style="width: 50%;" id="marker_group_parent"',
          ]),
        ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="marker_group_bg_color">
									<?php esc_html_e('Background Color', GMP_LANG_CODE); ?>:
								</label>
							</th>
							<td>
								<?php echo htmlGmp::wpKsesHtml(
          htmlGmp::colorpicker('marker_group[bg_color]', [
            'value' => $this->editMarkerGroup && $this->marker_group['params']['bg_color'] ? esc_attr($this->marker_group['params']['bg_color']) : '#E4E4E4',
          ]),
        ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="marker_group_claster_icon">
									<?php esc_html_e('Cluster Icon', GMP_LANG_CODE); ?>:
								</label>
							</th>
							<td>
								<?php
        $curMarkerGroupClusterIcon = uriGmp::_($this->editMarkerGroup && isset($this->marker_group['params']['claster_icon']) && $this->marker_group['params']['claster_icon'] ? esc_attr($this->marker_group['params']['claster_icon']) : GMP_MODULES_PATH . 'gmap/img/m1.png');
        $curMarkerGroupClusterIconWidth = $this->editMarkerGroup && isset($this->marker_group['params']['clasterer_icon_width']) && $this->marker_group['params']['clasterer_icon_width'] ? esc_attr($this->marker_group['params']['clasterer_icon_width']) : 53;
        $curMarkerGroupClusterIconHeight = $this->editMarkerGroup && isset($this->marker_group['params']['clasterer_icon_height']) && $this->marker_group['params']['marker_clasterer_icon_height'] ? esc_attr($this->marker_group['params']['marker_clasterer_icon_height']) : 52;
        ?>
								<div class="gmp-mgr-icon-row">
									<span class="gmp-mgr-icon-tile">
										<img id="gmpMarkerGroupClastererIconPrevImg" class="gmpSubOpt" src="<?php echo esc_attr($curMarkerGroupClusterIcon); ?>" alt="" />
									</span>
									<a id="gmpUploadMarkerGroupClastererIconBtn" class="button gmpSubOpt" href="#"><i class="fa fa-upload" aria-hidden="true"></i><?php esc_html_e('Upload Icon', GMP_LANG_CODE); ?></a>
									<a id="gmpDefaultMarkerGroupClastererIconBtn" class="button gmpSubOpt" href="#"><i class="fa fa-undo" aria-hidden="true"></i><?php esc_html_e('Default Icon', GMP_LANG_CODE); ?></a>
								</div>
								<div class="gmpMarkerGroupClastererUplRes"></div>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[claster_icon]', ['value' => esc_attr($curMarkerGroupClusterIcon)])); ?>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[claster_icon_width]', ['value' => esc_attr($curMarkerGroupClusterIconWidth)])); ?>
								<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[claster_icon_height]', ['value' => esc_attr($curMarkerGroupClusterIconHeight)])); ?>
							</td>
						</tr>
					</table>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('mod', ['value' => 'marker_groups'])); ?>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('action', ['value' => 'save'])); ?>
					<?php echo htmlGmp::defaultNonceForAdminPanel(); ?>
					<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('marker_group[id]', ['value' => $this->editMarkerGroup ? esc_attr($this->marker_group['id']) : ''])); ?>
				</form>
			</div>
		</div>
	</div>
</section>
