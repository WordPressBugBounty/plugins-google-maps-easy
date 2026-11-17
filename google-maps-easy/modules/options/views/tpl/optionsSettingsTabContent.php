<?php
/**
 * Settings of the plugin in the look of the Data Tables and Photo Gallery settings: a page head with Save and the
 * options in cards. The fields, their names (opt_values[...]), data-optkey, the form id and the hidden fields are the
 * same as before; only the layout changed. Options added by PRO or add-ons (filter optionsDefine) get a card of their
 * category.
 */
// Cards of the known options; the rest go to the card of their category
$cardOf = [
  'user_api_key' => 'api',
  'api_domain' => 'api',
  'api_region' => 'api',
  'access_roles' => 'access',
];
$cards = [
  'api' => [
    'icon' => 'fa-key',
    'title' => __('Google Maps API', GMP_LANG_CODE),
    'text' => __('Your own API key keeps every map working within your Google limits.', GMP_LANG_CODE),
    'rows' => [],
  ],
  'access' => [
    'icon' => 'fa-users',
    'title' => __('Access', GMP_LANG_CODE),
    'text' => __('Who can work with the plugin in the admin area.', GMP_LANG_CODE),
    'rows' => [],
  ],
];
$order = ['user_api_key' => 0, 'api_domain' => 1, 'api_region' => 2];
foreach ($this->options as $optCatKey => $optCatData) {
  if (empty($optCatData['opts'])) {
    continue;
  }
  foreach ($optCatData['opts'] as $optKey => $opt) {
    $card = isset($cardOf[$optKey]) ? $cardOf[$optKey] : 'cat_' . $optCatKey;
    if (!isset($cards[$card])) {
      $cards[$card] = [
        'icon' => 'fa-sliders',
        'title' => isset($optCatData['label']) ? $optCatData['label'] : $optCatKey,
        'text' => '',
        'rows' => [],
      ];
    }
    $cards[$card]['rows'][$optKey] = ['cat' => $optCatKey, 'opt' => $opt];
  }
  if (isset($optCatData['opts_html'])) {
    $cards['cat_' . $optCatKey . '_html'] = ['icon' => 'fa-sliders', 'title' => isset($optCatData['label']) ? $optCatData['label'] : $optCatKey, 'text' => '', 'rows' => [], 'html' => $optCatData['opts_html']];
  }
}
// The API key first: the field most people come here for
uksort($cards['api']['rows'], function ($a, $b) use ($order) {
  return (isset($order[$a]) ? $order[$a] : 9) - (isset($order[$b]) ? $order[$b] : 9);
});
?>
<?php if (!empty($this->onboarding)) { ?>
	<?php // A card of the plugin, not a WP notice: WP moves notices after the first h2 of the page, and this h2 was inside it ?>
	<div class="gmp-onboarding-note">
		<h3><?php _e('Connect your Google Maps API key', GMP_LANG_CODE); ?></h3>
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
			<p><?php printf(__("A full step-by-step guide is also available <a href='%s' target='_blank'>here</a>.", GMP_LANG_CODE), 'https://supsystic.com/documentation/create-google-maps-api-key/'); ?></p>
		</div>
	</div>
<?php } ?>
<div class="gmp-form-page gmp-settings-page">
	<div class="gmp-page-head">
		<div class="gmp-page-title">
			<span class="gmp-page-icon"><i class="fa fa-cogs" aria-hidden="true"></i></span>
			<div>
				<h2><?php esc_html_e('Settings', GMP_LANG_CODE); ?></h2>
				<p><?php esc_html_e('These options apply to all your maps.', GMP_LANG_CODE); ?></p>
			</div>
		</div>
		<div class="gmp-page-actions supsystic-bar-controls">
			<button class="button button-primary" id="gmpSettingsSaveBtn" data-toolbar-button title="<?php echo esc_attr__('Save all options', GMP_LANG_CODE); ?>">
				<i class="fa fa-fw fa-save"></i>
				<?php _e('Save', GMP_LANG_CODE); ?>
			</button>
		</div>
	</div>
	<form id="gmpSettingsForm" class="gmpInputsWithDescrForm">
		<div class="gmp-cards">
			<?php foreach ($cards as $cardKey => $card) {
     if (empty($card['rows']) && empty($card['html'])) {
       continue;
     } ?>
			<section class="gmp-form-card">
				<div class="gmp-form-card-head">
					<span class="gmp-form-card-icon"><i class="fa <?php echo esc_attr($card['icon']); ?>" aria-hidden="true"></i></span>
					<div>
						<h3><?php echo esc_html($card['title']); ?></h3>
						<?php if (!empty($card['text'])) { ?><p><?php echo esc_html($card['text']); ?></p><?php } ?>
					</div>
				</div>
				<table class="form-table">
					<?php foreach ($card['rows'] as $optKey => $row) {

       $opt = $row['opt'];
       $catClass = 'gmpOptCat_' . $row['cat'];
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
       $classes = [];
       if (isset($opt['pro']) && !empty($opt['pro'])) {
         $classes[] = 'gmpProOpt';
       }
       if ($htmlType === 'selectlist') {
         // A tag picker (Chosen, as in Ultimate Maps) instead of the tall list box; the select stays the field
         $classes[] = 'chosen';
       }
       if ($classes) {
         $htmlOgmp['attrs'] .= ' class="' . implode(' ', $classes) . '"';
       }
       $htmlInput = htmlGmp::$htmlType('opt_values[' . $optKey . ']', $htmlOgmp);
       if (in_array($htmlType, ['hidden'])) {
         echo htmlGmp::wpKsesHtml($htmlInput); // Just show hidden field, without any row at all
         continue;
       }
       $isOnboardingRow = $optKey === 'user_api_key' && !empty($this->onboarding);
       ?>
						<tr class="<?php echo esc_attr($catClass); ?><?php echo $isOnboardingRow ? ' gmp-onboarding-row' : ''; ?>" <?php echo $isOnboardingRow ? 'id="user_api_key"' : ''; ?>>
							<th scope="row">
								<label><?php _e($opt['label'], GMP_LANG_CODE); ?></label>
								<?php if (!empty($opt['desc'])) { ?>
									<i class="fa fa-question-circle supsystic-tooltip tooltip" data-tooltip-content="#tooltip_<?php echo esc_attr($optKey); ?>"></i>
									<span class="tooltipContent" id="tooltip_<?php echo esc_attr($optKey); ?>">
										<?php _e($opt['desc'], GMP_LANG_CODE); ?>
									</span>
								<?php } ?>
								<?php if (!empty($opt['changed_on'])) { ?>
									<span class="description gmp-field-changed">
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
							<td>
								<?php echo htmlGmp::wpKsesHtml($htmlInput); ?>
							</td>
						</tr>
					<?php
     } ?>
					<?php if (!empty($card['html'])) { ?>
						<tr>
							<td colspan="2">
								<?php if (is_callable($card['html'])) {
            echo call_user_func($card['html']);
          } elseif (is_string($card['html'])) {
            echo esc_attr($card['html']);
          } ?>
							</td>
						</tr>
					<?php } ?>
				</table>
			</section>
			<?php
   } ?>
		</div>
		<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('mod', ['value' => 'options'])); ?>
		<?php echo htmlGmp::wpKsesHtml(htmlGmp::hidden('action', ['value' => 'saveGroup'])); ?>
		<?php echo htmlGmp::defaultNonceForAdminPanel(); ?>
	</form>
</div>
