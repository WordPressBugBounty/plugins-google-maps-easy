<?php
/**
 * PRO feature windows of the free version (supsystic_promoGmp::printUpsellWindows()), made like those of Data Tables
 * and Photo Gallery. One <dialog>; each feature is a <template> that js/admin.upsell.js puts into it. Any element with
 * data-gmp-upsell="KEY" opens the window of that feature; the old "PRO option" links find their window by the
 * utm_medium of their link (the aliases below).
 *
 * @var array $this->features
 * @var array $this->aliases
 */
$features = $this->features;
?>
<dialog class="gmp-up" data-gmp-up data-gmp-up-aliases="<?php echo esc_attr(wp_json_encode($this->aliases)); ?>" data-gmp-up-card="<?php echo esc_attr(wp_json_encode($this->cardTexts)); ?>" aria-labelledby="gmp-up-title">
	<button type="button" class="gmp-up-close" data-gmp-up-close aria-label="<?php echo esc_attr__('Close', GMP_LANG_CODE); ?>">&times;</button>
	<div class="gmp-up-body" data-gmp-up-body></div>
</dialog>
<?php foreach ($features as $key => $f) { ?>
<template id="gmp-up-<?php echo esc_attr($key); ?>" data-chip="<?php echo esc_attr($f['chip']); ?>" data-icon="<?php echo esc_attr($f['icon']); ?>" data-group="<?php echo esc_attr($f['group']); ?>">
	<div class="gmp-up-hero">
		<span class="gmp-up-eyebrow"><i class="fa fa-diamond" aria-hidden="true"></i><?php esc_html_e('PRO feature', GMP_LANG_CODE); ?></span>
		<div class="gmp-up-head">
			<div>
				<h2 id="gmp-up-title"><?php echo esc_html($f['title']); ?></h2>
				<p><?php echo esc_html($f['text']); ?></p>
			</div>
			<a class="gmp-up-cta" href="<?php echo esc_url($f['url']); ?>" target="_blank" rel="noopener"><?php esc_html_e('Get PRO', GMP_LANG_CODE); ?><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
		</div>
	</div>
	<div class="gmp-up-main">
		<div class="gmp-up-demo" aria-hidden="true">
			<div class="gmp-up-demo-bar"><i></i><i></i><i></i><span><?php esc_html_e('On your page', GMP_LANG_CODE); ?></span></div>
			<div class="gmp-up-stage gmp-up-stage-<?php echo esc_attr($key); ?>">
				<?php echo $this->getDemoHtml($key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup built from constants ?>
			</div>
		</div>
		<div class="gmp-up-info">
			<h3><?php esc_html_e('What you get', GMP_LANG_CODE); ?></h3>
			<ul>
				<?php foreach ($f['points'] as $point) { ?><li><?php echo esc_html($point); ?></li><?php } ?>
			</ul>
			<h3><?php esc_html_e('Great for', GMP_LANG_CODE); ?></h3>
			<div class="gmp-up-uses">
				<?php foreach ($f['uses'] as $use) { ?><span><?php echo esc_html($use); ?></span><?php } ?>
			</div>
		</div>
	</div>
	<div class="gmp-up-more">
		<span class="gmp-up-more-label"><?php esc_html_e('Also in PRO', GMP_LANG_CODE); ?></span>
		<?php
  // The other features of its group; a feature alone in its group shows all the others
  $others = array_filter(
    $features,
    function ($other, $otherKey) use ($key, $f) {
      return $otherKey !== $key && $other['group'] === $f['group'];
    },
    ARRAY_FILTER_USE_BOTH,
  );
  if (!$others) {
    $others = $features;
    unset($others[$key]);
  }
  foreach ($others as $otherKey => $other) { ?>
			<button type="button" data-gmp-upsell="<?php echo esc_attr($otherKey); ?>"><i class="fa <?php echo esc_attr($other['icon']); ?>" aria-hidden="true"></i> <?php echo esc_html($other['chip']); ?></button>
		<?php
  } ?>
	</div>
</template>
<?php } ?>
