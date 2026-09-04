<?php
namespace Elementor;

if (!defined('ABSPATH')) {
  exit();
}

class Widget_Google_Maps_Easy_Gmp extends Widget_Base
{
  public function get_name()
  {
    return 'easy_google_maps_by_supsystic';
  }

  public function get_title()
  {
    return esc_html__('Easy Google Maps by Supsystic', GMP_LANG_CODE);
  }

  public function get_icon()
  {
    return 'eicon-google-maps';
  }

  public function get_categories()
  {
    return ['basic', 'general'];
  }

  public function get_keywords()
  {
    return ['map', 'maps', 'google maps', 'easy google maps', 'supsystic'];
  }

  public function is_reload_preview_required()
  {
    return true;
  }

  protected function register_controls()
  {
    $this->start_controls_section('section_google_maps_easy', [
      'label' => esc_html__('Map', GMP_LANG_CODE),
    ]);

    $this->add_control('map_id', [
      'label' => esc_html__('Select Map', GMP_LANG_CODE),
      'type' => Controls_Manager::SELECT,
      'options' => $this->getMapsOptions(),
      'default' => $this->getDefaultMapId(),
    ]);

    $this->add_control('width', [
      'label' => esc_html__('Width', GMP_LANG_CODE),
      'type' => Controls_Manager::TEXT,
      'placeholder' => '100%',
    ]);

    $this->add_control('height', [
      'label' => esc_html__('Height', GMP_LANG_CODE),
      'type' => Controls_Manager::NUMBER,
      'min' => 50,
      'step' => 10,
    ]);

    $this->add_control('align', [
      'label' => esc_html__('Alignment', GMP_LANG_CODE),
      'type' => Controls_Manager::SELECT,
      'options' => [
        '' => esc_html__('Default', GMP_LANG_CODE),
        'left' => esc_html__('Left', GMP_LANG_CODE),
        'right' => esc_html__('Right', GMP_LANG_CODE),
        'none' => esc_html__('None', GMP_LANG_CODE),
      ],
      'default' => '',
    ]);

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();
    $mapId = !empty($settings['map_id']) ? (int) $settings['map_id'] : 0;

    if (!$mapId) {
      if (isset(Plugin::$instance->editor) && Plugin::$instance->editor->is_edit_mode()) {
        echo '<div class="elementor-alert elementor-alert-info">' . esc_html__('Select an Easy Google Maps map to display it here.', GMP_LANG_CODE) . '</div>';
      }
      return;
    }

    $params = ['id' => $mapId];
    foreach (['width', 'height', 'align'] as $key) {
      if (isset($settings[$key]) && $settings[$key] !== '') {
        $params[$key] = sanitize_text_field($settings[$key]);
      }
    }

    echo \frameGmp::_()->getModule('gmap')->drawMapFromShortcode($params);
  }

  private function getMapsOptions()
  {
    $mapsModule = \frameGmp::_()->getModule('gmap');
    return $mapsModule ? $mapsModule->getMapsOptionsForSelect() : ['' => esc_html__('Select a map', GMP_LANG_CODE)];
  }

  private function getDefaultMapId()
  {
    $mapsModule = \frameGmp::_()->getModule('gmap');
    return $mapsModule ? $mapsModule->getDefaultMapId() : '';
  }
}
