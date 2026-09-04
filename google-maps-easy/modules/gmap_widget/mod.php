<?php
class gmap_widgetGmp extends moduleGmp
{
  public function init()
  {
    parent::init();
    add_action('widgets_init', [$this, 'registerWidget']);
  }
  public function registerWidget()
  {
    return register_widget('gmpMapsWidget');
  }
}
/**
 * Maps widget class
 */
class gmpMapsWidget extends WP_Widget
{
  public function __construct()
  {
    $widgetOps = [
      'classname' => 'gmpMapsWidget',
      'description' => __('Displays an Easy Google Maps by Supsystic map.', GMP_LANG_CODE),
    ];
    parent::__construct('gmpMapsWidget', __('Easy Google Maps by Supsystic', GMP_LANG_CODE), $widgetOps);
  }
  public function widget($args, $instance)
  {
    frameGmp::_()->getModule('gmap_widget')->getView()->displayWidget($instance);
  }
  public function form($instance)
  {
    frameGmp::_()->getModule('gmap_widget')->getView()->displayForm($instance, $this);
  }
  public function update($new_instance, $old_instance)
  {
    //frameGmp::_()->getModule('supsystic_promo')->getModel()->saveUsageStat('map.widget.update');
    $instance = [];
    $instance['id'] = !empty($new_instance['id']) ? absint($new_instance['id']) : 0;

    foreach (['width', 'height', 'map_center', 'zoom'] as $key) {
      if (isset($new_instance[$key]) && $new_instance[$key] !== '') {
        $instance[$key] = sanitize_text_field(wp_unslash($new_instance[$key]));
      }
    }

    if (!empty($new_instance['align']) && in_array($new_instance['align'], ['left', 'right', 'none'], true)) {
      $instance['align'] = $new_instance['align'];
    }

    $instance['display_as_img'] = !empty($new_instance['display_as_img']) ? 1 : 0;
    foreach (['img_width', 'img_height'] as $key) {
      if (isset($new_instance[$key]) && $new_instance[$key] !== '') {
        $instance[$key] = absint($new_instance[$key]);
      }
    }

    return $instance;
  }
}
