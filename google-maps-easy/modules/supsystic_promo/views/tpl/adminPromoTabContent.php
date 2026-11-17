<?php
/**
 * A PRO tab of the map editor in the free version (Figures, Heatmap, Path Router): a PRO card with a picture of the
 * feature, Get PRO and Read more. The page passes through wp_kses (no SVG, no data attributes), so js/admin.upsell.js
 * puts the picture of the feature from its window into .gmp-up-tab-demo and makes Read more open that window (the
 * feature is found by the utm_medium of the link).
 */
$promoLink = 'https://supsystic.com/plugins/google-maps-plugin/?utm_source=plugin&utm_medium=' . $this->tabCode;
$feature = $this->feature;
?>
<div class="gmp-up-tab" id="gmpUpTab-<?php echo esc_attr($this->featureKey); ?>">
	<div class="gmp-up-tab-head">
		<span class="gmp-up-eyebrow"><i class="fa fa-diamond" aria-hidden="true"></i><?php esc_html_e('PRO feature', GMP_LANG_CODE); ?></span>
		<h3><?php echo esc_html($feature ? $feature['title'] : __($this->tabTitle, GMP_LANG_CODE)); ?></h3>
		<p><?php echo esc_html($feature ? $feature['text'] : __($this->tabDescription, GMP_LANG_CODE)); ?></p>
	</div>
	<div class="gmp-up-tab-demo"></div>
	<div class="gmp-up-tab-foot">
		<a class="gmp-up-cta" target="_blank" rel="noopener" href="<?php echo esc_url($promoLink); ?>"><?php esc_html_e('Get PRO', GMP_LANG_CODE); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
		<?php if ($feature) { ?>
			<a class="gmp-up-tab-more" href="<?php echo esc_url($promoLink); ?>" target="_blank" rel="noopener"><?php esc_html_e('Read more', GMP_LANG_CODE); ?></a>
		<?php } ?>
	</div>
</div>
