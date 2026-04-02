<?php
class markerViewGmp extends viewGmp
{
  public function getListOperations($markerId)
  {
    $this->assign('marker', ['id' => $markerId]);
    return parent::getContent('markerListOperations');
  }
}
