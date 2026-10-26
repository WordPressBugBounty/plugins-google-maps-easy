<?php
class gmap_widgetViewGmp extends viewGmp
{
  public function displayWidget($instance)
  {
    if (isset($instance['id']) && $instance['id']) {
      foreach ($instance as $key => $val) {
        if (empty($instance[$key])) {
          unset($instance[$key]);
        }
      }
      // "250" is pixels, as the widget always used; "250px" / "50%" are passed on (gmapViewGmp::applyShortcodeHtmlParams).
      // The unit check here always found "px" (strpos() never returns -1), so "50%" did not work.
      if (isset($instance['width']) && is_numeric(trim((string) $instance['width']))) {
        $instance['width'] = trim((string) $instance['width']) . 'px';
      }
      echo frameGmp::_()->getModule('gmap')->drawMapFromShortcode($instance);
    }
  }
  public function displayForm($data, $widget)
  {
    frameGmp::_()->addStyle('gmap_widget', $this->getModule()->getModPath() . 'css/gmap_widget.css');

    $mapsModule = frameGmp::_()->getModule('gmap');
    $mapsOpts = $mapsModule ? $mapsModule->getMapsOptionsForSelect() : ['' => __('Select a map', GMP_LANG_CODE)];
    $this->assign('mapsOpts', $mapsOpts);
    $this->displayWidgetForm($data, $widget);
  }
}
