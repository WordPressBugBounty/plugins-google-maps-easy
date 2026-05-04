<?php
class markerGmp extends moduleGmp
{
  public function __construct($d)
  {
    parent::__construct($d);
    dispatcherGmp::addFilter('gApiUrlParams', [$this, 'addMapApiUrlParams']);
  }
  public function init()
  {
    //dispatcherGmp::addFilter('adminOptionsTabs', array($this, 'addOptionsTab'));
    //dispatcherGmp::addAction('tplHeaderBegin',array($this,'showFavico'));
    //dispatcherGmp::addAction('tplBodyEnd',array($this, 'GoogleAnalitics'));
    //dispatcherGmp::addAction('in_admin_footer',array($this, 'showPluginFooter'));
  }
  /*public function addOptionsTab($tabs){
		if(frameGmp::_()->isAdminPlugPage()){
//			frameGmp::_()->addScript('adminMetaOptions',$this->getModPath().'js/admin.marker.js',array(),false,true);
		}
		return $tabs;
	}*/
  /*public function connectAssets() {
		frameGmp::_()->addScript('marker', $this->getModPath(). 'js/marker.js');
	}*/
  public function getAnimationList()
  {
    return [
      0 => __('None', GMP_LANG_CODE),
      1 => __('Drop', GMP_LANG_CODE), //DROP
      2 => __('Bounce', GMP_LANG_CODE), //BOUNCE
    ];
  }
  public function addMapApiUrlParams($mapParams)
  {
    if (!isset($mapParams['libraries'])) {
      $mapParams['libraries'] = '';
    }
    if (empty($mapParams['libraries'])) {
      $mapParams['libraries'] = 'marker';
    } else {
      $mapParams['libraries'] .= ',marker';
    }
    return $mapParams;
  }
}
