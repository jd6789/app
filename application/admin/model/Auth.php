<?php

namespace app\admin\model;

use think\Model;

class Auth extends Model
{
    public function getIsNavAttr($value)
   {
      $is_nav = [1=>'是',0=>'否'];
      return $is_nav[$value];
   }

}
