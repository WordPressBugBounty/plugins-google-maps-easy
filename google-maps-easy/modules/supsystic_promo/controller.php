<?php
class supsystic_promoControllerGmp extends controllerGmp
{
  /**
   * Still live: also handles the dismiss/hide action for the API-key admin
   * notice (showUserApiKeyAdminNotice() in mod.php), not just promo notices.
   */
  public function addNoticeAction()
  {
    $res = new responseGmp();
    $code = reqGmp::getVar('code', 'post');
    $choice = reqGmp::getVar('choice', 'post');
    if (!empty($code) && !empty($choice)) {
      $optModel = frameGmp::_()->getModule('options')->getModel();
      switch ($choice) {
        case 'hide':
          $optModel->save('hide_' . $code, 1);
          break;
        case 'later':
          $optModel->save('later_' . $code, time());
          break;
        case 'done':
          $optModel->save('done_' . $code, 1);
          break;
      }
    }
    $res->ajaxExec();
  }
  public function getNoncedMethods()
  {
    return ['addNoticeAction'];
  }
  /**
   * @see controller::getPermissions();
   */
  public function getPermissions()
  {
    return [
      GMP_USERLEVELS => [
        GMP_ADMIN => ['addNoticeAction'],
      ],
    ];
  }
}
