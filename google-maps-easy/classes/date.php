<?php
#[AllowDynamicProperties]
class dateGmp
{
  public static function _($time = null)
  {
    if (is_null($time)) {
      $time = time();
    }
    return date(GMP_DATE_FORMAT_HIS, $time);
  }
}
