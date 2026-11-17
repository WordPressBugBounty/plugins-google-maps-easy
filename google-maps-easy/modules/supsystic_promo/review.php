<?php
/**
 * Asks for a review on WordPress.org once the plugin has done something useful for the site, the same system as in
 * Data Tables and Photo Gallery: a map with markers went live on a published page, several maps or many markers were
 * built, PRO reported its own moment (a route or a figure) or, as a fallback, two weeks of use. The card is shown on
 * the plugin's own pages only (Overview, All Maps, Settings), to the users allowed to use the plugin, and it is not
 * shown again once it is answered.
 *
 * PRO (or any add-on) reports its moments with do_action('gmp_review_moment', $type, ['map' => $id]).
 *
 * The old review notice of the plugin ("rate_msg", options done_rate_msg / hide_rate_msg / start_usage) is counted:
 * a site that answered it is asked once more only after a real success, and its first use is the start.
 */
class gmpReviewGmp
{
  const REVIEW_URL = 'https://wordpress.org/support/plugin/google-maps-easy/reviews/#new-post';
  const SUPPORT_URL = 'https://supsystic.com/contact-us/';
  const OPTION = 'gmp_review';

  // No card in the first days after install, whatever happens
  const MIN_DAYS = 3;
  // Without any moment the card comes after two weeks of use
  const FALLBACK_DAYS = 14;
  const MAPS_MILESTONE = 3;
  const MARKERS_MILESTONE = 25;
  const SNOOZE_DAYS = 30;
  const MAX_SNOOZES = 3;

  /** Moments in the order the card picks them: the first one recorded wins */
  private $momentTypes = ['live', 'route', 'figure'];

  /** @var array|null */
  private $state;

  /** @var array|null|false False until the card is built for the current request */
  private $card = false;

  /** @var gmpReviewGmp|null */
  private static $instance;

  public static function getInstance()
  {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function init()
  {
    add_filter('do_shortcode_tag', [$this, 'onShortcodeOutput'], 10, 3);
    add_filter('render_block', [$this, 'onBlockOutput'], 10, 2);
    add_action('wp_after_insert_post', [$this, 'onPostSaved'], 10, 2);
    add_action('gmp_review_moment', [$this, 'recordMoment'], 10, 2);
    add_action('wp_ajax_gmp_review_respond', [$this, 'ajaxRespond']);
    if (is_admin()) {
      add_filter('plugin_row_meta', [$this, 'addPluginRowLink'], 10, 2);
      if (frameGmp::_()->isAdminPlugOptsPage()) {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        // The plugin layout passes its content through wp_kses (no SVG, no data attributes): the card is printed in
        // the footer and js/admin.design.js puts it at the top of the content panel
        add_action('admin_footer', [$this, 'printCard']);
      }
    }
  }

  /**
   * A map is rendered on a published page that a visitor (or the site owner) opens: the map is live.
   * Covers the shortcode and the Elementor widget, which renders the shortcode.
   */
  public function onShortcodeOutput($output, $tag, $attr)
  {
    if ($tag !== GMP_SHORTCODE || !is_string($output) || !is_array($attr) || empty($attr['id'])) {
      return $output;
    }
    $this->onMapShown((int) $attr['id']);
    return $output;
  }

  /** The block of the plugin draws the map without the shortcode */
  public function onBlockOutput($content, $block)
  {
    if (is_array($block) && isset($block['blockName']) && $block['blockName'] === 'supsystic/google-maps-easy' && !empty($block['attrs']['map_id'])) {
      $this->onMapShown((int) $block['attrs']['map_id']);
    }
    return $content;
  }

  private function onMapShown($mapId)
  {
    if ($mapId < 1 || $this->hasMoment('live')) {
      return;
    }
    if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST) || is_preview() || is_feed() || is_customize_preview() || isset($_GET['elementor-preview'])) {
      return;
    }
    if (!is_singular()) {
      return;
    }
    $post = get_queried_object();
    if ($post instanceof WP_Post && $post->post_status === 'publish' && !post_password_required($post) && $this->countMarkers($mapId) > 0) {
      $this->addMoment('live', ['map' => $mapId, 'post' => (int) $post->ID]);
    }
  }

  /** A post with a map block or shortcode is published: the map is live even before anyone opens the page */
  public function onPostSaved($postId, $post)
  {
    if (!$post instanceof WP_Post || $post->post_status !== 'publish' || $this->hasMoment('live')) {
      return;
    }
    if (wp_is_post_revision($postId) || wp_is_post_autosave($postId) || !is_post_type_viewable($post->post_type)) {
      return;
    }
    $mapId = $this->findMapIn((string) $post->post_content);
    if ($mapId && $this->countMarkers($mapId) > 0) {
      $this->addMoment('live', ['map' => $mapId, 'post' => (int) $postId]);
    }
  }

  /**
   * Handler of the "gmp_review_moment" action.
   * @param string $type route or figure
   * @param array $data ['map' => id]
   */
  public function recordMoment($type, $data = [])
  {
    if (!is_string($type) || $type === 'live' || !in_array($type, $this->momentTypes, true) || $this->hasMoment($type)) {
      return;
    }
    $this->addMoment($type, ['map' => is_array($data) && isset($data['map']) ? (int) $data['map'] : 0]);
  }

  /**
   * The card for the current user on the current page, or null when there is nothing to show.
   * @return array|null
   */
  public function getCard()
  {
    if ($this->card !== false) {
      return $this->card;
    }
    $this->card = null;

    $tab = frameGmp::_()->getModule('options') ? frameGmp::_()->getModule('options')->getActiveTab() : '';
    if (!in_array($tab, ['overview', 'gmap', 'settings'], true) || !apply_filters('gmp_review_request', true)) {
      return null;
    }
    $userId = get_current_user_id();
    if (!$userId) {
      return null;
    }
    $answer = $this->getAnswer($userId);
    if ($answer['status'] === 'rated' || $answer['status'] === 'dismissed') {
      return null;
    }
    if ($answer['status'] === 'snoozed' && $answer['until'] > time()) {
      return null;
    }
    $state = $this->getState();
    if (time() - $state['since'] < self::MIN_DAYS * DAY_IN_SECONDS) {
      return null;
    }
    $moment = $this->pickMoment($state);
    if (!$moment) {
      return null;
    }
    $this->card = array_merge($moment, [
      'reviewUrl' => self::REVIEW_URL,
      'supportUrl' => self::SUPPORT_URL,
      'ajaxUrl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('gmp_review'),
    ]);
    return $this->card;
  }

  public function enqueueAssets()
  {
    if (!$this->getCard()) {
      return;
    }
    $path = frameGmp::_()->getModule('supsystic_promo')->getModPath();
    $version = GMP_VERSION_PLUGIN . '-review-4';
    wp_enqueue_style('gmp-review', $path . 'css/admin.review.css', [], $version);
    wp_enqueue_script('gmp-review', $path . 'js/admin.review.js', [], $version, true);
  }

  public function printCard()
  {
    $card = $this->getCard();
    if (!$card) {
      return;
    }
    $view = frameGmp::_()->getModule('supsystic_promo')->getView();
    $view->assign('card', $card);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the template escapes its values
    echo $view->getContent('reviewCard');
  }

  /** AJAX: the answer of the current user to the card */
  public function ajaxRespond()
  {
    if (!check_ajax_referer('gmp_review', '_wpnonce', false) || !current_user_can(frameGmp::_()->getModule('adminmenu')->getMainCap())) {
      wp_send_json_error(null, 403);
    }
    $answer = isset($_POST['answer']) ? sanitize_key(wp_unslash($_POST['answer'])) : '';
    if (!$this->saveAnswer(get_current_user_id(), $answer)) {
      wp_send_json_error(null, 400);
    }
    wp_send_json_success();
  }

  /**
   * Saves the answer of a user. "rate" and "done" end the requests; "later", "support" and "close" put them off for a
   * month, and the third time (or the first time on a site that answered the old review notice) for good.
   */
  public function saveAnswer($userId, $answer)
  {
    $userId = (int) $userId;
    if (!$userId || !is_string($answer)) {
      return false;
    }
    $now = time();
    if ($answer === 'rate' || $answer === 'done') {
      update_user_meta($userId, self::OPTION, ['status' => 'rated', 'answer' => $answer, 'time' => $now]);
      return true;
    }
    if ($answer === 'later' || $answer === 'support' || $answer === 'close') {
      $snoozes = $this->getAnswer($userId)['snoozes'] + 1;
      $state = $this->getState();
      $final = $snoozes >= self::MAX_SNOOZES || $state['legacy'];
      update_user_meta($userId, self::OPTION, [
        'status' => $final ? 'dismissed' : 'snoozed',
        'answer' => $answer,
        'until' => $now + self::SNOOZE_DAYS * DAY_IN_SECONDS,
        'snoozes' => $snoozes,
        'time' => $now,
      ]);
      return true;
    }
    return false;
  }

  /** "Rate us" link in the plugin's row on the Plugins page */
  public function addPluginRowLink($links, $file)
  {
    if (!is_array($links) || $file !== plugin_basename(GMP_DIR . GMP_MAIN_FILE)) {
      return $links;
    }
    $links[] = sprintf(
      '<a href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s <span style="color:#ffb900;letter-spacing:1px;" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span></a>',
      esc_url(self::REVIEW_URL),
      esc_attr__('Rate Easy Google Maps on WordPress.org', GMP_LANG_CODE),
      esc_html__('Rate us', GMP_LANG_CODE),
    );
    return $links;
  }

  /** Site state: when the plugin started to be used, the recorded moments and whether the old notice was answered */
  public function getState()
  {
    if ($this->state !== null) {
      return $this->state;
    }
    $state = get_option(self::OPTION);
    if (!is_array($state) || !isset($state['since']) || !is_int($state['since'])) {
      $state = $this->createState();
      update_option(self::OPTION, $state, true);
    }
    $state['legacy'] = !empty($state['legacy']);
    $state['moments'] = isset($state['moments']) && is_array($state['moments']) ? $state['moments'] : [];
    $this->state = $state;
    return $this->state;
  }

  /** @return array ['status' => ''|'snoozed'|'rated'|'dismissed', 'until' => int, 'snoozes' => int] */
  public function getAnswer($userId)
  {
    $answer = get_user_meta((int) $userId, self::OPTION, true);
    $answer = is_array($answer) ? $answer : [];
    return [
      'status' => isset($answer['status']) && is_string($answer['status']) ? $answer['status'] : '',
      'until' => isset($answer['until']) ? (int) $answer['until'] : 0,
      'snoozes' => isset($answer['snoozes']) ? max(0, (int) $answer['snoozes']) : 0,
    ];
  }

  /**
   * The first state of a site. Existing sites keep their history: the start is the first map (or the first use the old
   * notice counted from), and an answered old notice is remembered.
   */
  private function createState()
  {
    global $wpdb;
    $now = time();
    $since = $now;
    $legacy = false;

    $opts = get_option(GMP_CODE . '_opts_data');
    $opts = is_array($opts) ? $opts : [];
    $old = function ($key) use ($opts) {
      return isset($opts[$key]['value']) ? $opts[$key]['value'] : null;
    };
    if ((int) $old('done_rate_msg') || (int) $old('hide_rate_msg')) {
      $legacy = true;
    }
    $startUsage = (int) $old('start_usage');
    if ($startUsage > 0) {
      $since = min($since, $startUsage);
    }
    $suppress = $wpdb->suppress_errors(true);
    $firstMap = $wpdb->get_var("SELECT MIN(create_date) FROM {$wpdb->prefix}gmp_maps");
    $wpdb->suppress_errors($suppress);
    if ($firstMap && ($firstTime = strtotime($firstMap)) && $firstTime > 0) {
      $since = min($since, $firstTime);
    }
    return ['since' => (int) $since, 'legacy' => $legacy, 'moments' => []];
  }

  private function pickMoment(array $state)
  {
    foreach ($this->momentTypes as $type) {
      if (empty($state['moments'][$type]) || !is_array($state['moments'][$type])) {
        continue;
      }
      $moment = $state['moments'][$type];
      $mapId = isset($moment['map']) ? (int) $moment['map'] : 0;
      $map = $this->getMapTitle($mapId);
      if ($map === null) {
        // The map was deleted since then
        continue;
      }
      $map = trim($map) !== '' ? trim($map) : '#' . $mapId;

      if ($type === 'live') {
        $post = isset($moment['post']) ? get_post((int) $moment['post']) : null;
        if (!$post instanceof WP_Post || $post->post_status !== 'publish') {
          continue;
        }
        $postTitle = get_the_title($post);
        return [
          'type' => 'live',
          'eyebrow' => __('Map published', GMP_LANG_CODE),
          'title' => $postTitle !== '' ? sprintf(__('“%1$s” is live on “%2$s”', GMP_LANG_CODE), $map, $postTitle) : sprintf(__('“%s” is live on your site', GMP_LANG_CODE), $map),
          'text' => __('Visitors can now find your places and open their markers.', GMP_LANG_CODE),
          'link' => get_permalink($post),
          'linkText' => __('View page', GMP_LANG_CODE),
        ];
      }
      if ($type === 'route') {
        return [
          'type' => 'route',
          'eyebrow' => __('Route built', GMP_LANG_CODE),
          'title' => sprintf(__('“%s” now shows your route', GMP_LANG_CODE), $map),
          'text' => __('Visitors see the way between your points right on the map.', GMP_LANG_CODE),
        ];
      }
      return [
        'type' => 'figure',
        'eyebrow' => __('Zones on the map', GMP_LANG_CODE),
        'title' => sprintf(__('“%s” now shows your zones and lines', GMP_LANG_CODE), $map),
        'text' => __('Figures make areas, routes and coverage clear at a glance.', GMP_LANG_CODE),
      ];
    }

    // A site that answered the old review notice is asked once more only after a real success
    if ($state['legacy']) {
      return null;
    }
    $maps = $this->countRows('gmp_maps');
    if ($maps >= self::MAPS_MILESTONE) {
      return [
        'type' => 'maps',
        'eyebrow' => __('Milestone', GMP_LANG_CODE),
        'title' => sprintf(__('You have built %d maps with Easy Google Maps', GMP_LANG_CODE), $maps),
        'text' => __('Nice work. Your places are easy to find.', GMP_LANG_CODE),
      ];
    }
    $markers = $this->countRows('gmp_markers');
    if ($markers >= self::MARKERS_MILESTONE) {
      return [
        'type' => 'markers',
        'eyebrow' => __('Milestone', GMP_LANG_CODE),
        'title' => sprintf(__('You have put %d markers on your maps', GMP_LANG_CODE), $markers),
        'text' => __('Nice work. Your maps tell visitors where to go.', GMP_LANG_CODE),
      ];
    }
    $days = (int) floor((time() - $state['since']) / DAY_IN_SECONDS);
    if ($maps >= 1 && $days >= self::FALLBACK_DAYS) {
      $weeks = (int) floor($days / 7);
      return [
        'type' => 'time',
        'eyebrow' => __('Thank you for using Easy Google Maps', GMP_LANG_CODE),
        'title' => $weeks < 9 ? sprintf(__('Easy Google Maps has been working on your site for %d weeks', GMP_LANG_CODE), $weeks) : __('Easy Google Maps has been working on your site for a while now', GMP_LANG_CODE),
        'text' => __('We hope it makes showing your places a pleasure.', GMP_LANG_CODE),
      ];
    }
    return null;
  }

  private function hasMoment($type)
  {
    $state = $this->getState();
    return !empty($state['moments'][$type]);
  }

  private function addMoment($type, array $data)
  {
    $state = $this->getState();
    $state['moments'][$type] = array_merge($data, ['time' => time()]);
    $this->state = $state;
    $save = $state;
    unset($save['legacy']);
    $save['legacy'] = $state['legacy'];
    update_option(self::OPTION, $save, true);
  }

  /** The first map in a map block or a map shortcode of the content */
  private function findMapIn($content)
  {
    if ($content === '' || (strpos($content, GMP_SHORTCODE) === false && strpos($content, 'wp:supsystic/google-maps-easy') === false)) {
      return 0;
    }
    if (preg_match('#<!--\s+wp:supsystic/google-maps-easy\s+(\{.*?\})\s+/?-->#', $content, $match)) {
      $attributes = json_decode($match[1], true);
      if (is_array($attributes) && !empty($attributes['map_id']) && (int) $attributes['map_id'] > 0) {
        return (int) $attributes['map_id'];
      }
    }
    if (preg_match_all('/' . get_shortcode_regex([GMP_SHORTCODE]) . '/', $content, $matches, PREG_SET_ORDER)) {
      foreach ($matches as $shortcode) {
        $attributes = shortcode_parse_atts($shortcode[3]);
        if (is_array($attributes) && !empty($attributes['id']) && (int) $attributes['id'] > 0) {
          return (int) $attributes['id'];
        }
      }
    }
    return 0;
  }

  /** @return string|null Null when there is no such map */
  private function getMapTitle($id)
  {
    global $wpdb;
    if ($id < 1) {
      return null;
    }
    $title = $wpdb->get_var($wpdb->prepare("SELECT title FROM {$wpdb->prefix}gmp_maps WHERE id = %d", $id));
    return $title === null ? null : (string) $title;
  }

  private function countMarkers($mapId)
  {
    global $wpdb;
    static $cache = [];
    if (!isset($cache[$mapId])) {
      $suppress = $wpdb->suppress_errors(true);
      $cache[$mapId] = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}gmp_markers WHERE map_id = %d", $mapId));
      $wpdb->suppress_errors($suppress);
    }
    return $cache[$mapId];
  }

  /** @param string $table gmp_maps or gmp_markers */
  private function countRows($table)
  {
    global $wpdb;
    $suppress = $wpdb->suppress_errors(true);
    $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}{$table}");
    $wpdb->suppress_errors($suppress);
    return $count;
  }
}
