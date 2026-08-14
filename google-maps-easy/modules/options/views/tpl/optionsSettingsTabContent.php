<?php if (!empty($this->onboarding)) { ?>
	<div class="notice notice-info" style="padding: 12px 16px; margin-bottom: 15px;">
		<h2 style="margin-top: 0;"><?php _e('Connect your Google Maps API key', GMP_LANG_CODE); ?></h2>
		<p><?php _e('Paste your key into the "User API key" field below and click Save — you will be taken straight to creating your first map.', GMP_LANG_CODE); ?></p>
		<p>
			<a href="#" id="gmpOnboardingApiKeyHelpToggle"><?php _e("Don't have a key yet? Here's how to get one", GMP_LANG_CODE); ?></a>
		</p>
		<div id="gmpOnboardingApiKeyHelp" style="display: none;">
			<ol>
				<li><?php printf(__("Open the <a href='%s' target='_blank'>Google Cloud Console credentials page</a>.", GMP_LANG_CODE), 'https://console.cloud.google.com/google/maps-apis/credentials'); ?></li>
				<li><?php _e('Create (or select) a project, then click "Create credentials" &rarr; "API key".', GMP_LANG_CODE); ?></li>
				<li><?php _e('Enable the "Maps JavaScript API" for that project.', GMP_LANG_CODE); ?></li>
				<li><?php _e('Copy the generated key and paste it into the field below.', GMP_LANG_CODE); ?></li>
			</ol>
			<p><?php printf(__("A full step-by-step guide is also available <a href='%s' target='_blank'>here</a>.", GMP_LANG_CODE), '//supsystic.com/google-maps-api-key/'); ?></p>
		</div>
	</div>
<?php } ?>
<section class="supsystic-bar">
	<ul class="supsystic-bar-controls">
		<li title="<?php _e('Save all options'); ?>">
			<button class="button button-primary" id="gmpSettingsSaveBtn" data-toolbar-button>
				<i class="fa fa-fw fa-save"></i>
				<?php _e('Save', GMP_LANG_CODE); ?>
			</button>
		</li>
	</ul>
	<div style="clear: both;"></div>
	<hr />
</section>
<section>
	<form id="gmpSettingsForm" class="gmpInputsWithDescrForm">
		<div class="supsystic-item supsystic-panel">
			<div id="containerWrapper">
				<table class="form-table">
					<?php foreach ($this->options as $optCatKey => $optCatData) { ?>
						<?php
       /*if($optCatKey == 'system') continue;*/
       /*It will be hidden for now*/
       ?>
						<?php $catClass = 'gmpOptCat_' . $optCatKey; ?>
						<?php if (!isset($optCatData['hide_cat_label']) || !$optCatData['hide_cat_label']) { ?>
							<tr class="<?php echo esc_attr($catClass); ?>">
								<th colspan="4">
									<h3><?php _e($optCatData['label'], GMP_LANG_CODE); ?></h3>
								</th>
							</tr>
						<?php } ?>
						<?php if (isset($optCatData['opts']) && !empty($optCatData['opts'])) { ?>
							<?php foreach ($optCatData['opts'] as $optKey => $opt) { ?>
								<?php
        $htmlType = isset($opt['html']) ? $opt['html'] : false;
        $attrs = isset($opt['attrs']) ? $opt['attrs'] : '';
        if (empty($htmlType)) {
          continue;
        }
        if (in_array($optKey, ['cs_mode'])) {
          continue;
        } // Custom options
        $htmlOgmp = ['value' => $opt['value'], 'attrs' => 'data-optkey="' . $optKey . '" ' . $attrs];
        if (in_array($htmlType, ['selectbox', 'selectlist']) && isset($opt['options'])) {
          if (is_callable($opt['options'])) {
            $htmlOgmp['options'] = call_user_func($opt['options']);
          } elseif (is_array($opt['options'])) {
            $htmlOgmp['options'] = $opt['options'];
          }
        }
        if (isset($opt['pro']) && !empty($opt['pro'])) {
          $htmlOgmp['attrs'] .= ' class="gmpProOpt"';
        }
        $htmlInput = htmlGmp::$htmlType('opt_values[' . $optKey . ']', $htmlOgmp);
        if (in_array($htmlType, ['hidden'])) {
          echo htmlGmp::wpKsesHtml($htmlInput); // Just show hidden field, without any row at all
          continue;
        }
        ?>
								<tr class="<?php echo esc_attr($catClass); ?>" <?php echo $optKey === 'user_api_key' && !empty($this->onboarding) ? 'id="user_api_key" style="outline: 2px solid #2271b1; background: #f0f6fc;"' : ''; ?>>
									<th scope="row" class="col-perc col-w-20perc">
										<?php _e($opt['label'], GMP_LANG_CODE); ?>
										<?php if (!empty($opt['changed_on'])) { ?>
											<br />
											<span class="description">
												<?php $opt['value'] ? printf(__('Turned On %s', GMP_LANG_CODE), dateGmp::_($opt['changed_on'])) : printf(__('Turned Off %s', GMP_LANG_CODE), dateGmp::_($opt['changed_on'])); ?>
											</span>
										<?php } ?>
										<?php if (isset($opt['pro']) && !empty($opt['pro'])) { ?>
											<span class="gmpProOptMiniLabel">
												<a href="<?php echo esc_attr($opt['pro']); ?>" target="_blank">
													<?php _e('PRO option', GMP_LANG_CODE); ?>
												</a>
											</span>
										<?php } ?>
									</th>
									<td class="col-perc col-w-1perc">
										<i class="fa fa-question supsystic-tooltip tooltip" data-tooltip-content="#tooltip_<?php echo $optKey; ?>"></i>
										<span class="tooltipContent" id="tooltip_<?php echo $optKey; ?>">
												<?php _e($opt['desc'], GMP_LANG_CODE); ?>
										</span>
									</td>
									<td class="col-perc col-w-8perc">
										<?php echo htmlGmp::wpKsesHtml($htmlInput); ?>
									</td>
								</tr>
							<?php } ?>
						<?php } ?>
						<?php if (isset($optCatData['opts_html'])) { ?>
							<tr class="<?php echo esc_attr($catClass); ?>">
								<td colspan="3">
									<?php if (is_callable($optCatData['opts_html'])) {
           echo call_user_func($optCatData['opts_html']);
         } elseif (is_string($opt['options'])) {
           echo esc_attr($optCatData['opts_html']);
         } ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</table>
				<div style="clear: both;"></div>
			</div>
		</div>
		<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('mod', ['value' => 'options'])); ?>
		<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('action', ['value' => 'saveGroup'])); ?>
		<?php echo htmlGmp::defaultNonceForAdminPanel(); ?>
	</form>
</section>
