<?php

namespace app\admin\model;
use think\Model;
use think\model\concern\SoftDelete;
class Goods extends Model
{
     use SoftDelete;
     protected $table = 'tpshop_goods';
	 //protected $name = 'goods';
     //设置软删除字段名称
     protected $deleteTime = 'delete_time';

//     public function getCreateTimeAttr($value)
//     {
//          return date("Y-m-d h:i",$value);
//     }
}