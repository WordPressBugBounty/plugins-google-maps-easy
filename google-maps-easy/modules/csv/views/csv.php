<?php
class csvViewGmp extends viewGmp
{
  public function getTabContent()
  {
    $options = frameGmp::_()->getModule('options')->getModel()->getAll();
    $csv_options = !empty($options['csv_options']) && !empty($options['csv_options']['value']) ? $options['csv_options']['value'] : [];
    $this->assign('delimiters', [';' => ';', ',' => ',']);
    $this->assign('options', $csv_options);
    // Figures and heatmap come with PRO: without it their buttons only led to a "requires the PRO version" page
    $this->assign('hasFigures', (bool) frameGmp::_()->getModule('shape'));
    $this->assign('hasHeatmap', (bool) frameGmp::_()->getModule('heatmap'));
    $this->assign('proLink', frameGmp::_()->getModule('supsystic_promo')->generateMainLink('utm_source=plugin&utm_medium=csv_import_export&utm_campaign=googlemaps'));
    return parent::getContent('csvTabContent');
  }
}
