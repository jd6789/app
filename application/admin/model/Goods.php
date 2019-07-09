<?php

namespace app\admin\model;
use think\Model;
use think\model\concern\SoftDelete;
class Goods extends Model
{
     use SoftDelete;
     protected $table = 'tpshop_goods';
     //设置软删除字段名称
     protected $deleteTime = 'delete_time';

}