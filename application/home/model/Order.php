<?php

namespace app\home\model;

use think\Model;

class Order extends Model
{
    //获取器
    public function getPayTypeAttr($value)
    {
        $pay_type = ['alipay'=>'支付宝','wechat'=>'微信支付','cart'=>'银联'];
        return $pay_type[$value];
    }

    public function getPayStatusAttr($value)
    {
        $pay_status = [0=>'未付款',1=>'已付款'];
        return $pay_status[$value];
    }
}
