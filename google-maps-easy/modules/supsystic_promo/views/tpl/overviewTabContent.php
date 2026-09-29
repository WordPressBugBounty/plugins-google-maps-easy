<?php
$promoModule = frameGmp::_()->getModule('supsystic_promo');
$isPro = $promoModule->isPro();
$adminBase = admin_url('admin.php?page=google-maps-easy');
$addMapUrl = $adminBase . '&tab=gmap_add_new';
$allMapsUrl = $adminBase . '&tab=gmap';
$settingsUrl = $adminBase . '&tab=settings';
$proUrl = $promoModule->generateMainLink('utm_source=plugin&utm_medium=overview&utm_campaign=google_maps_upsell');
$featureGridUrl = $promoModule->generateMainLink('utm_source=plugin&utm_medium=overview&utm_campaign=google_maps_upsell&utm_content=feature_grid');
$pricingUrl = $promoModule->generateMainLink('utm_source=plugin&utm_medium=overview&utm_campaign=google_maps_upsell&utm_content=pro_powerhouse');
$comparisonUrl = $promoModule->generateMainLink('utm_source=plugin&utm_medium=overview&utm_campaign=google_maps_upsell&utm_content=free_pro_comparison');
?>

<div class="gmp-overview">
  <section class="gmp-overview-hero">
    <div class="gmp-overview-hero-copy">
      <div class="gmp-overview-kicker">
        <img src="<?php echo esc_url(GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME . '/modules/supsystic_promo/img/plugin-icon.png'); ?>" alt="">
        <span><?php echo esc_html($isPro ? __('Easy Google Maps PRO by Supsystic', GMP_LANG_CODE) : __('Easy Google Maps by Supsystic', GMP_LANG_CODE)); ?></span>
      </div>
      <h1><?php esc_html_e('Turn every location into a reason to visit.', GMP_LANG_CODE); ?></h1>
      <p><?php esc_html_e('Build responsive Google Maps with rich markers, category filters and branded experiences. Start with an unlimited map in Free, then add routes, layers and professional location discovery with PRO.', GMP_LANG_CODE); ?></p>
      <div class="gmp-overview-actions">
        <a class="button button-primary button-hero" href="<?php echo esc_url($addMapUrl); ?>"><i class="fa fa-plus"></i> <?php esc_html_e('Create map', GMP_LANG_CODE); ?></a>
        <a class="button button-secondary button-hero" href="<?php echo esc_url($allMapsUrl); ?>"><i class="fa fa-map"></i> <?php esc_html_e('View maps', GMP_LANG_CODE); ?></a>
        <?php if (!$isPro) { ?>
          <a class="button button-secondary button-hero gmp-overview-pro-button" href="<?php echo esc_url($proUrl); ?>" target="_blank" rel="noopener"><i class="fa fa-star"></i> <?php esc_html_e('Unlock PRO', GMP_LANG_CODE); ?></a>
        <?php } ?>
      </div>
      <div class="gmp-overview-trust">
        <span><i class="fa fa-check"></i> <?php esc_html_e('Unlimited maps and markers', GMP_LANG_CODE); ?></span>
        <span><i class="fa fa-check"></i> <?php esc_html_e('Responsive on every device', GMP_LANG_CODE); ?></span>
        <span><i class="fa fa-check"></i> <?php esc_html_e('WordPress native publishing', GMP_LANG_CODE); ?></span>
      </div>
    </div>

    <div class="gmp-overview-hero-media" aria-hidden="true">
      <div class="gmp-overview-map-window">
        <div class="gmp-overview-window-bar"><span></span><span></span><span></span><em><i class="fa fa-lock"></i> yourstore.com/locations</em><b><?php esc_html_e('Store locator', GMP_LANG_CODE); ?></b></div>
        <div class="gmp-overview-map-app">
          <div class="gmp-overview-locator">
            <strong class="gmp-overview-locator-title"><?php esc_html_e('Find your nearest store', GMP_LANG_CODE); ?></strong>
            <div class="gmp-overview-locator-search"><i class="fa fa-search"></i><span><?php esc_html_e('City or ZIP', GMP_LANG_CODE); ?></span><i class="fa fa-crosshairs"></i></div>
            <small class="gmp-overview-locator-count"><?php esc_html_e('4 locations within 5 km', GMP_LANG_CODE); ?></small>
            <ul class="gmp-overview-locator-list">
              <li class="is-active"><b class="is-red">1</b><div><strong><?php esc_html_e('Your Store #1', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Downtown', GMP_LANG_CODE); ?> · 0.4 km · <em><?php esc_html_e('Open', GMP_LANG_CODE); ?></em></span></div></li>
              <li><b class="is-teal">2</b><div><strong><?php esc_html_e('Your Store #2', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Harbor', GMP_LANG_CODE); ?> · 1.1 km · <em><?php esc_html_e('Open', GMP_LANG_CODE); ?></em></span></div></li>
              <li><b class="is-violet">3</b><div><strong><?php esc_html_e('Your Store #3', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Midtown', GMP_LANG_CODE); ?> · 1.8 km · <?php esc_html_e('Delivery', GMP_LANG_CODE); ?></span></div></li>
              <li><b class="is-orange">4</b><div><strong><?php esc_html_e('Your Store #4', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Northside', GMP_LANG_CODE); ?> · 2.6 km · <em><?php esc_html_e('Open', GMP_LANG_CODE); ?></em></span></div></li>
            </ul>
            <div class="gmp-overview-locator-layers">
              <span><i class="is-heat"></i><?php esc_html_e('Heatmap', GMP_LANG_CODE); ?></span>
              <span><i class="is-zone"></i><?php esc_html_e('Zones', GMP_LANG_CODE); ?></span>
              <span><i class="is-route"></i><?php esc_html_e('Route', GMP_LANG_CODE); ?></span>
            </div>
          </div>
          <div class="gmp-overview-map-canvas">
            <img class="gmp-overview-map-svg" src="<?php echo esc_url(GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME . '/modules/supsystic_promo/img/overview-map.svg'); ?>" alt="">
            <span class="gmp-overview-map-fullscreen"><i class="fa fa-expand"></i></span>
            <div class="gmp-overview-map-zoom"><span>+</span><span>−</span></div>
            <div class="gmp-overview-map-scale"><i></i>200 m</div>
            <div class="gmp-overview-infowindow">
              <span class="gmp-overview-infowindow-close">×</span>
              <img src="<?php echo esc_url(GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME . '/modules/supsystic_promo/img/map-popup-place.svg'); ?>" alt="">
              <div class="gmp-overview-infowindow-body">
                <strong><?php esc_html_e('Your Store #1', GMP_LANG_CODE); ?></strong>
                <span class="gmp-overview-infowindow-rating">4.8 <span>★★★★★</span> (128)</span>
                <small><?php esc_html_e('Downtown · 48 Market St', GMP_LANG_CODE); ?></small>
                <span class="gmp-overview-infowindow-meta"><em><?php esc_html_e('Open now', GMP_LANG_CODE); ?></em><?php esc_html_e('12 min · 1.2 km', GMP_LANG_CODE); ?></span>
                <b class="gmp-overview-infowindow-btn"><i class="fa fa-location-arrow"></i> <?php esc_html_e('Directions', GMP_LANG_CODE); ?></b>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php if ($isPro) { ?>
    <section class="gmp-overview-license-banner">
      <div class="gmp-overview-license-aside" aria-hidden="true"><i class="fa fa-check-circle"></i><strong>PRO</strong><span><?php esc_html_e('mapping toolkit active', GMP_LANG_CODE); ?></span></div>
      <div class="gmp-overview-license-content">
        <span class="gmp-overview-pill"><?php esc_html_e('PRO enabled', GMP_LANG_CODE); ?></span>
        <h2><?php esc_html_e('Your complete location experience is ready.', GMP_LANG_CODE); ?></h2>
        <p><?php esc_html_e('Directions, KML, heatmaps, figures, marker lists, advanced controls and frontend workflows are available in the map editor.', GMP_LANG_CODE); ?></p>
        <a class="button button-primary" href="<?php echo esc_url($addMapUrl); ?>"><i class="fa fa-arrow-right"></i> <?php esc_html_e('Build a PRO map', GMP_LANG_CODE); ?></a>
      </div>
    </section>
  <?php } ?>

  <section class="gmp-overview-layout">
    <div class="gmp-overview-main">
      <div class="gmp-overview-section-heading">
        <div><span class="gmp-overview-pill"><?php esc_html_e('51 mapping capabilities', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('From a single pin to a complete store locator.', GMP_LANG_CODE); ?></h2></div>
        <p><?php esc_html_e('Free covers the essentials. PRO adds the tools that turn location data into a guided customer journey.', GMP_LANG_CODE); ?></p>
      </div>

      <div class="gmp-overview-feature-grid">
        <div class="gmp-overview-feature-card"><i class="fa fa-map-marker"></i><h3><?php esc_html_e('Unlimited maps and markers', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Create as many maps and locations as the site needs. Search an address, click the map or use exact coordinates.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-free">FREE</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-picture-o"></i><h3><?php esc_html_e('Rich marker stories', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Combine text, photos, video, links, phone numbers and custom icons inside informative marker popups.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-free">FREE</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-object-group"></i><h3><?php esc_html_e('Categories and clustering', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Organize locations into filterable categories and group dense marker sets into clusters as visitors zoom.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-free">FREE</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-exchange"></i><h3><?php esc_html_e('Import, export and publish', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Move maps and markers with CSV, then publish through shortcode, PHP, Gutenberg, Elementor or a sidebar widget.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-free">FREE</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-location-arrow"></i><h3><?php esc_html_e('Directions and routes', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Keep visitors on the site with Get Direction buttons, alternate routes, current-position starts and turn-by-turn steps.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-pro">PRO</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-paint-brush"></i><h3><?php esc_html_e('300+ map themes', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Match the map to the brand with a large style library, advanced controls, fullscreen, print and cleaner points of interest.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-pro">PRO</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-list-alt"></i><h3><?php esc_html_e('Marker lists and sliders', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Add searchable tables, photo sliders, description cards and exposition layouts beside or below the map.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-pro">PRO</span></div>
        <div class="gmp-overview-feature-card"><i class="fa fa-area-chart"></i><h3><?php esc_html_e('Layers, zones and density', GMP_LANG_CODE); ?></h3><p><?php esc_html_e('Visualize KML, heatmaps, traffic, transit, cycling, polygons, circles, polylines and road-following paths.', GMP_LANG_CODE); ?></p><span class="gmp-overview-tag is-pro">PRO</span></div>
      </div>

      <?php if (!$isPro) { ?>
        <div class="gmp-overview-feature-cta">
          <a class="button button-primary button-hero" href="<?php echo esc_url($featureGridUrl); ?>" target="_blank" rel="noopener"><i class="fa fa-star"></i> <?php esc_html_e('Unlock PRO', GMP_LANG_CODE); ?></a>
        </div>
      <?php } ?>

      <section id="gmp-overview-pro" class="gmp-overview-pro-panel">
        <div class="gmp-overview-pro-copy">
          <span class="gmp-overview-pill"><?php echo esc_html($isPro ? __('PRO toolkit active', GMP_LANG_CODE) : __('Build beyond the basic map', GMP_LANG_CODE)); ?></span>
          <h2><?php echo esc_html($isPro ? __('Every advanced mapping tool is unlocked.', GMP_LANG_CODE) : __('Make the map answer the visitor’s next question.', GMP_LANG_CODE)); ?></h2>
          <p><?php echo esc_html($isPro ? __('Use advanced layers, routes, controls and location discovery directly in the editor.', GMP_LANG_CODE) : __('Visitors need more than pins. Help them search, compare, understand coverage and reach the right location without leaving your site.', GMP_LANG_CODE)); ?></p>
          <?php if (!$isPro) { ?>
            <a class="button button-primary button-hero gmp-overview-upgrade-cta" href="<?php echo esc_url($pricingUrl); ?>" target="_blank" rel="noopener"><i class="fa fa-star"></i> <?php esc_html_e('Unlock every PRO feature', GMP_LANG_CODE); ?></a>
            <small><i class="fa fa-shield"></i> <?php esc_html_e('30-day money-back guarantee', GMP_LANG_CODE); ?></small>
          <?php } ?>
        </div>
        <div class="gmp-overview-pro-stack" aria-label="<?php esc_attr_e('PRO feature highlights', GMP_LANG_CODE); ?>">
          <span><i class="fa fa-location-arrow"></i><b><?php esc_html_e('Directions', GMP_LANG_CODE); ?></b><small><?php esc_html_e('Routes and steps', GMP_LANG_CODE); ?></small></span>
          <span><i class="fa fa-fire"></i><b><?php esc_html_e('Heatmaps', GMP_LANG_CODE); ?></b><small><?php esc_html_e('Density insights', GMP_LANG_CODE); ?></small></span>
          <span><i class="fa fa-file-code-o"></i><b><?php esc_html_e('KML', GMP_LANG_CODE); ?></b><small><?php esc_html_e('Layers and filters', GMP_LANG_CODE); ?></small></span>
          <span><i class="fa fa-edit"></i><b><?php esc_html_e('Frontend', GMP_LANG_CODE); ?></b><small><?php esc_html_e('User submissions', GMP_LANG_CODE); ?></small></span>
          <span><i class="fa fa-th-large"></i><b><?php esc_html_e('Listings', GMP_LANG_CODE); ?></b><small><?php esc_html_e('Tables and sliders', GMP_LANG_CODE); ?></small></span>
          <span><i class="fa fa-crop"></i><b><?php esc_html_e('Shapes', GMP_LANG_CODE); ?></b><small><?php esc_html_e('Zones and paths', GMP_LANG_CODE); ?></small></span>
        </div>
      </section>

      <section class="gmp-overview-use-cases">
        <div class="gmp-overview-use-heading"><span class="gmp-overview-pill"><?php esc_html_e('Built for real businesses', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('One map editor, many location workflows.', GMP_LANG_CODE); ?></h2></div>
        <div class="gmp-overview-use-grid">
          <div class="gmp-overview-use-item"><i class="fa fa-shopping-bag"></i><strong><?php esc_html_e('Store locator', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Search branches, filter services and guide customers to the closest store.', GMP_LANG_CODE); ?></span></div>
          <div class="gmp-overview-use-item"><i class="fa fa-home"></i><strong><?php esc_html_e('Real estate', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Show properties with photos, prices, contact links and category filters.', GMP_LANG_CODE); ?></span></div>
          <div class="gmp-overview-use-item"><i class="fa fa-plane"></i><strong><?php esc_html_e('Travel guides', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Connect landmarks, routes, media-rich descriptions and themed layers.', GMP_LANG_CODE); ?></span></div>
          <div class="gmp-overview-use-item"><i class="fa fa-truck"></i><strong><?php esc_html_e('Delivery zones', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Explain service areas with polygons, paths, traffic and route planning.', GMP_LANG_CODE); ?></span></div>
          <div class="gmp-overview-use-item"><i class="fa fa-calendar"></i><strong><?php esc_html_e('Events and venues', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Map entrances, parking, stages and nearby points of interest.', GMP_LANG_CODE); ?></span></div>
        </div>
      </section>

      <section id="gmp-overview-compare" class="gmp-overview-comparison">
        <div class="gmp-overview-card-head"><div><span class="gmp-overview-pill"><?php esc_html_e('Free vs PRO', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Upgrade when the map becomes part of the customer journey.', GMP_LANG_CODE); ?></h2></div><p><?php esc_html_e('Nothing built in Free needs to be rebuilt after upgrading.', GMP_LANG_CODE); ?></p></div>
        <div class="gmp-overview-compare-table" role="table" aria-label="<?php esc_attr_e('Free and PRO feature comparison', GMP_LANG_CODE); ?>">
          <div class="gmp-overview-compare-row is-head" role="row"><strong role="columnheader"><?php esc_html_e('Capability', GMP_LANG_CODE); ?></strong><strong role="columnheader">FREE</strong><strong role="columnheader">PRO</strong></div>
          <div class="gmp-overview-compare-row" role="row"><span role="cell"><?php esc_html_e('Unlimited maps, markers, categories and clustering', GMP_LANG_CODE); ?></span><i class="fa fa-check" role="cell"></i><i class="fa fa-check" role="cell"></i></div>
          <div class="gmp-overview-compare-row" role="row"><span role="cell"><?php esc_html_e('CSV import/export and WordPress publishing', GMP_LANG_CODE); ?></span><i class="fa fa-check" role="cell"></i><i class="fa fa-check" role="cell"></i></div>
          <div class="gmp-overview-compare-row" role="row"><span role="cell"><?php esc_html_e('300+ themes and advanced map controls', GMP_LANG_CODE); ?></span><i class="fa fa-minus" role="cell"></i><i class="fa fa-check" role="cell"></i></div>
          <div class="gmp-overview-compare-row" role="row"><span role="cell"><?php esc_html_e('Directions, routes, steps and visitor position', GMP_LANG_CODE); ?></span><i class="fa fa-minus" role="cell"></i><i class="fa fa-check" role="cell"></i></div>
          <div class="gmp-overview-compare-row" role="row"><span role="cell"><?php esc_html_e('KML, heatmap, shapes, traffic and transit layers', GMP_LANG_CODE); ?></span><i class="fa fa-minus" role="cell"></i><i class="fa fa-check" role="cell"></i></div>
          <div class="gmp-overview-compare-row" role="row"><span role="cell"><?php esc_html_e('Marker lists, sliders and frontend editing', GMP_LANG_CODE); ?></span><i class="fa fa-minus" role="cell"></i><i class="fa fa-check" role="cell"></i></div>
        </div>
        <?php if (!$isPro) { ?>
          <div class="gmp-overview-compare-cta"><div><strong><?php esc_html_e('Ready for the complete mapping toolkit?', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Every PRO license unlocks the same feature set; plans differ by site count.', GMP_LANG_CODE); ?></span></div><a class="button button-primary" href="<?php echo esc_url($comparisonUrl); ?>" target="_blank" rel="noopener"><i class="fa fa-balance-scale"></i> <?php esc_html_e('Compare licenses', GMP_LANG_CODE); ?></a></div>
        <?php } ?>
      </section>

      <section class="gmp-overview-workflow">
        <div class="gmp-overview-workflow-heading"><span class="gmp-overview-pill"><?php esc_html_e('Fast setup', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Publish a useful map in four steps.', GMP_LANG_CODE); ?></h2></div>
        <ol><li><b>1</b><div><strong><?php esc_html_e('Connect Google', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Add your API key once in Settings.', GMP_LANG_CODE); ?></span></div></li><li><b>2</b><div><strong><?php esc_html_e('Add locations', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Drop pins or import marker data.', GMP_LANG_CODE); ?></span></div></li><li><b>3</b><div><strong><?php esc_html_e('Design the experience', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Choose styles, filters, layers and controls.', GMP_LANG_CODE); ?></span></div></li><li><b>4</b><div><strong><?php esc_html_e('Publish anywhere', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Use a block, widget or shortcode.', GMP_LANG_CODE); ?></span></div></li></ol>
      </section>

      <section id="gmp-overview-help" class="gmp-overview-card gmp-overview-help">
        <div class="gmp-overview-card-head"><div><span class="gmp-overview-pill"><?php esc_html_e('Help center', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Common setup questions', GMP_LANG_CODE); ?></h2></div><a class="button button-secondary" href="https://supsystic.com/docs/google-maps-easy/?utm_source=plugin&amp;utm_medium=overview&amp;utm_campaign=google_maps_help" target="_blank" rel="noopener"><i class="fa fa-book"></i> <?php esc_html_e('Open documentation', GMP_LANG_CODE); ?></a></div>
        <div class="gmp-overview-faq-list"><?php foreach ($this->faqList as $title => $desc) { ?><div class="gmp-overview-faq-item"><button class="gmp-overview-faq-question"><?php echo esc_html($title); ?><i class="fa fa-angle-down"></i></button><div class="gmp-overview-faq-answer"><?php echo wp_kses_post($desc); ?></div></div><?php } ?></div>
      </section>

      <section id="gmp-overview-videos" class="gmp-overview-video-grid">
        <div class="gmp-overview-card"><div class="gmp-overview-card-head"><div><span class="gmp-overview-pill"><?php esc_html_e('Learn', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Google Maps tutorial', GMP_LANG_CODE); ?></h2></div></div><div class="gmp-overview-video-frame"><iframe title="Google Maps Easy tutorial" src="https://www.youtube.com/embed/Ej8EtuLcLZk" frameborder="0" allowfullscreen loading="lazy"></iframe></div></div>
        <div class="gmp-overview-card"><div class="gmp-overview-card-head"><div><span class="gmp-overview-pill"><?php esc_html_e('Discover', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Supsystic overview', GMP_LANG_CODE); ?></h2></div></div><div class="gmp-overview-video-frame"><iframe title="Supsystic overview" src="https://www.youtube.com/embed/dKd_9g6JzfU" frameborder="0" allowfullscreen loading="lazy"></iframe></div></div>
      </section>

      <section id="gmp-overview-server" class="gmp-overview-card">
        <div class="gmp-overview-card-head"><div><span class="gmp-overview-pill"><?php esc_html_e('Diagnostics', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Server settings', GMP_LANG_CODE); ?></h2></div><p><?php esc_html_e('Environment details for troubleshooting.', GMP_LANG_CODE); ?></p></div>
        <div class="gmp-overview-server-grid"><?php foreach ($this->serverSettings as $title => $element) { ?><div class="gmp-overview-server-item <?php echo !empty($element['error']) ? 'is-error' : ''; ?>"><span><?php echo esc_html(trim($title)); ?></span><strong><?php echo esc_html(trim((string) $element['value'])); ?></strong></div><?php } ?></div>
      </section>
    </div>

    <div class="gmp-overview-sidebar">
      <section class="gmp-overview-card gmp-overview-resource-card">
        <span class="gmp-overview-pill"><?php esc_html_e('Resources', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Build your next map', GMP_LANG_CODE); ?></h2>
        <a class="gmp-overview-resource-link" href="<?php echo esc_url($addMapUrl); ?>"><i class="fa fa-plus-circle"></i> <?php esc_html_e('Create a new map', GMP_LANG_CODE); ?></a>
        <a class="gmp-overview-resource-link" href="<?php echo esc_url($allMapsUrl); ?>"><i class="fa fa-map"></i> <?php esc_html_e('Manage maps', GMP_LANG_CODE); ?></a>
        <a class="gmp-overview-resource-link" href="<?php echo esc_url($settingsUrl); ?>"><i class="fa fa-key"></i> <?php esc_html_e('Connect API key', GMP_LANG_CODE); ?></a>
        <a class="gmp-overview-resource-link" href="#gmp-overview-compare"><i class="fa fa-balance-scale"></i> <?php esc_html_e('Compare Free and PRO', GMP_LANG_CODE); ?></a>
        <a class="gmp-overview-resource-link" href="#gmp-overview-help"><i class="fa fa-book"></i> <?php esc_html_e('FAQ and documentation', GMP_LANG_CODE); ?></a>
        <a class="gmp-overview-resource-link" href="#gmp-overview-videos"><i class="fa fa-play"></i> <?php esc_html_e('Video tutorials', GMP_LANG_CODE); ?></a>
        <a class="gmp-overview-resource-link" href="#gmp-overview-server"><i class="fa fa-cog"></i> <?php esc_html_e('Server settings', GMP_LANG_CODE); ?></a>
      </section>

      <section id="gmp-overview-support" class="gmp-overview-card gmp-overview-support-card"><span class="gmp-overview-pill"><?php esc_html_e('Human support', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Need help with a map?', GMP_LANG_CODE); ?></h2><p><?php esc_html_e('Send the support team your API, marker, styling or integration question.', GMP_LANG_CODE); ?></p><a class="button button-primary" href="https://supsystic.com/contact-us/?utm_source=plugin&amp;utm_medium=overview&amp;utm_campaign=google_maps_support" target="_blank" rel="noopener"><i class="fa fa-life-ring"></i> <?php esc_html_e('Contact support', GMP_LANG_CODE); ?></a></section>

      <?php if (!$isPro) { ?>
        <section class="gmp-overview-card gmp-overview-sidebar-pro"><i class="fa fa-star"></i><span class="gmp-overview-pill"><?php esc_html_e('PRO', GMP_LANG_CODE); ?></span><h2><?php esc_html_e('Build maps that guide decisions.', GMP_LANG_CODE); ?></h2><p><?php esc_html_e('Add routes, layers, professional listings and visitor submissions.', GMP_LANG_CODE); ?></p><a class="button button-primary" href="<?php echo esc_url($proUrl); ?>" target="_blank" rel="noopener"><?php esc_html_e('View PRO options', GMP_LANG_CODE); ?></a></section>
      <?php } ?>
    </div>
  </section>
</div>
