<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\cache\driver\Redis;
class Base extends Controller
{
     public function __construct()
     {
         parent::__construct();
         //查询所有的商品分类信息
         $list = \app\home\model\Category::where('is_show',1)->select();
         $list = new \think\Collection($list);
         $category = $list->toArray();//对象转换为数组
         $category = get_cate_tree($category);
         //dump($category);die;
         $this->assign('category',$category);

     }
}
