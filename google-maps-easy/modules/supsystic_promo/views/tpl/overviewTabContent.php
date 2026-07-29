<div class="supsystic-overview">
  <div class="full-page">
    <div class="plugin-title"><img src="<?php echo GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME; ?>/modules/supsystic_promo/img/plugin-icon.png">Easy Google Maps By Supsystic</div>
    <div class="plugin-description">This easy-to-use plugin is the “Go-To” maps solution for Bloggers and Tourism Operators worldwide. The plugin engages exclusively with Google Maps – the biggest player in the mapping universe. It has been designed to deliver quality results fast and is renowned for being user friendly.</div>
  </div>
  <div class="supsystic-overview-flex">
    <div class="half-page half-page-left">
      <div class="border-wrapper">
        <ul>
          <li class="overview-section-btn" data-section="faq"><i class="fa fa-info-circle"></i> FAQ and Documentation</li>
          <li class="overview-section-btn" data-section="video"><i class="fa fa-play"></i> Video tutorial</li>
          <li class="overview-section-btn" data-section="settings"><i class="fa fa-cog"></i> Server Settings</li>
          <li class="overview-section-btn" data-section="support"><i class="fa fa-life-ring"></i> Support</li>
          <li class="overview-section-btn" data-section="promo_video"><i class="fa fa-star"></i> Our promo video</li>
          <li class="overview-section-btn"><a target="_blank" title="Go to supsystic.com" href="https://supsystic.com/plugins/google-maps-plugin/?utm_source=plugin&utm_campaign=google-maps"> Plugin page on supsystic.com <sup><i class="fa fa-external-link"></i></sup></a></li>
          <li class="overview-section-btn"><a target="_blank" title="Go to supsystic.com" href="https://supsystic.com/plugins/google-maps-plugin/?utm_source=plugin&utm_campaign=google-maps"> Compare FREE and PRO features <sup><i class="fa fa-external-link"></i></sup></a></li>
          <li class="overview-section-btn"><a target="_blank" title="Go to supsystic.com" href="https://supsystic.com/all-plugins/?utm_source=plugin&utm_campaign=google-maps"> Check other supsystic FREE plugins <sup><i class="fa fa-external-link"></i></sup></a></li>
        </ul>
      </div>
      <div class="border-wrapper">
        <div class="overview-contact-form overview-section" data-section="support">
          <h3><i class="fa fa-life-ring"></i> Support</h3>
          <div class="contact-info-section">
            <p>
            If you are experiencing any issues with the plugin, would like to request a new feature or improvement, or have any other questions, please contact our technical support team through our website:
            <a href="https://supsystic.com/contact-us/" target="_blank">https://supsystic.com/contact-us/</a>
            </p>
          </div>
          <div class="clear"></div>
        </div>

        <div data-section="faq" class="faq-list overview-section">
          <h3><?php _e('FAQ and Documentation', GMP_LANG_CODE); ?></h3>
          <?php foreach ($this->faqList as $title => $desc) { ?>
          <div class="faq-title">
            <i class="fa fa-info-circle"></i>
            <?php echo $title; ?>
            <div class="description" style="display: none;"><?php echo $desc; ?></div>
          </div>
          <?php } ?>
          <div style="clear: both;"></div>
          <a target="_blank" href="https://supsystic.com/docs/google-maps-easy/?utm_source=plugin&utm_medium=faq&utm_campaign=google-maps" class="button button-primary button-hero">
            <i class="fa fa-info-circle"></i>
            Check all FAQs
          </a>
          <div class="clear"></div>
        </div>
        <div data-section="video" class="video overview-section">
          <h3><i class="fa fa-play"></i> Video tutorial</h3>
          <iframe type="text/html"
            width="100%"
            height="350px"
            src="//www.youtube.com/embed/Ej8EtuLcLZk"
            frameborder="0">
          </iframe>
          <div class="clear"></div>
        </div>
        <div data-section="promo_video" class="video overview-section">
          <h3><i class="fa fa-star"></i> Our promo video</h3>
          <iframe type="text/html"
            width="100%"
            height="350px"
            src="//www.youtube.com/embed/dKd_9g6JzfU"
            frameborder="0">
          </iframe>
          <div class="clear"></div>
        </div>
        <div data-section="settings" class="server-settings overview-section">
          <h3><i class="fa fa-cog"></i> Server settings</h3>
          <ul class="settings-list">
            <?php foreach ($this->serverSettings as $title => $element) { ?>
            <li class="settings-line">
              <div class="settings-title"><?php echo $title; ?>:</div>
              <span><?php echo $element['value']; ?></span>
            </li>
            <?php } ?>
          </ul>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    <div class="half-page half-page-right">
      <?php if (frameGmp::_()->getModule('supsystic_promo')->isPro()) { ?>
      <a href="https://supsystic.com/plugins/google-maps-plugin/?utm_source=plugin&utm_campaign=google-maps" target="_blank"><img class="overview-supsystic-img" src="<?php echo GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME; ?>/modules/supsystic_promo/img/overview-upgrade.png"></a>
      <?php } ?>
      <a href="https://supsystic.com/pricing/?utm_source=plugin&utm_campaign=google-maps" target="_blank"><img class="overview-supsystic-img" src="<?php echo GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME; ?>/modules/supsystic_promo/img/overview-01.png"></a>
      <a href="https://supsystic.com/plugins/plugins-bundle/?utm_source=plugin&utm_campaign=google-maps" target="_blank"><img class="overview-supsystic-img" src="<?php echo GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME; ?>/modules/supsystic_promo/img/overview-02.png"></a>
      <a href="https://supsystic.com/all-plugins/?utm_source=plugin&utm_campaign=google-maps" target="_blank"><img style="margin-top:20px;" class="overview-supsystic-img" src="<?php echo GMP_PLUGINS_URL . '/' . GMP_PLUG_NAME; ?>/modules/supsystic_promo/img/overview-03.png"></a>
      <div class="clear"></div>
    </div>
  </div>
</div>