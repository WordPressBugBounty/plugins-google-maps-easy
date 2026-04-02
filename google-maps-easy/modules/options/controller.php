<?php
class optionsControllerGmp extends controllerGmp
{
  public function saveGroup()
  {
    $res = new responseGmp();
    if ($this->getModel()->saveGroup(reqGmp::get('post'))) {
      $res->addMessage(__('Done', GMP_LANG_CODE));
    } else {
      $res->pushError($this->getModel('options')->getErrors());
    }
    return $res->ajaxExec();
  }
  public function getNoncedMethods()
  {
    return ['saveGroup'];
  }
  public function getPermissions()
  {
    return [
      GMP_USERLEVELS => [
        GMP_ADMIN => ['saveGroup'],
      ],
    ];
  }
}
