<?php

namespace app\home\model;

use think\Model;

class Cart extends Model
{
    public static function carlist()
    {
        $user = session('user_info');
        if($user){
            //已登录
            $data = self::where('user_id',$user['id'])->order('id','desc')->select();
        }else{
            //未登录
            $cart = cookie('cart')? unserialize(cookie('cart')):[];
            $data = [];
            foreach ($cart as $k=>$v){
                 $tmp = explode('-',$k);
                 $row = [
                      'id'=>'',
                      'goods_id'=>$tmp[0],
                      'goods_attr_ids'=>$tmp[1],
                      'number'=>$v
                 ];
                 $data[] = $row;
            }
        }
        return $data;
    }

    public static function addCart($goods_id,$goods_attr_ids,$number)
    {
        //给属性信息排序
        $goods_attr_ids = explode(',',$goods_attr_ids);
        sort($goods_attr_ids);
        $goods_attr_ids = implode(',',$goods_attr_ids);
        //获取用户信息
        $user = session('user_info');
        if($user){
            //登录状态下
            $row = [
                'user_id'=>$user['id'],
                'goods_id'=>$goods_id,
                'goods_attr_ids'=>$goods_attr_ids,
            ];
            //判断购物车中信息是否存在,存在则直接更新数量
            $info = self::where($row)->find();//dump($info);die;
            if($info){
                //存在更新number字段
                $number = $info['number']+$number;
                $info->number = $number;
                $info->save();
            }else{
                //新添加一条记录
                $row['number'] = $number;
                self::create($row,true);
            }
        }else{
            //未登录
            //从cookie中取出购物车数据判断是否存在
            $cart = cookie('cart') ? unserialize(cookie('cart')):[];//dump($cart);die;
            //拼接商品下标
            $key = $goods_id . '-' . $goods_attr_ids;
            if(isset($cart[$key])){
                //有相同记录
                $cart[$key] += $number;
            }else{
                //没有记录
                $cart[$key] = $number;
            }
            //将新的数组重新序列化到cookie中
            cookie('cart',serialize($cart),86400*7);
        }
            return true;
    }

    public static function cookieToDb()
    {
        $cart = unserialize(cookie('cart'));
        if($cart){
            foreach ($cart as $k=>$v){
                $k_arr = explode('-',$k);
                $goods_id = $k_arr[0];
                $goods_attr_ids=$k_arr[1];
                $number=$v;
                self::addCart($goods_id,$goods_attr_ids,$number);
            }
            cookie('cart',null);
        }
    }
}
