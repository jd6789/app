<?php

namespace app\home\controller;

use think\Request;

class Cart extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        //cookie('cart',null);
        //dump(unserialize(cookie('cart')));die;
        $data = \app\home\model\Cart::carlist();//dump($data);die;
        foreach ($data as &$v){
            $v['goods'] = \app\home\model\Goods::where('id',$v['goods_id'])->find();
            $goodsattr = \app\admin\model\GoodsAttr::where('id','in',$v['goods_attr_ids'])->select();
            $v['attr']= $goodsattr;
            foreach ($v['attr'] as $key=>&$value){
                  $attr = \app\admin\model\Attribute::find($value['attr_id']);
                  $value['attr_name'] = $attr['attr_name'];
            }
        }
        //dump($data);die;
        return view('index',['data'=>$data]);
    }

    /**
     * 修改购物车数量
     */
    public function changeNum()
    {
        $data = request()->param();
        //检测参数
        $user = session('user_info');
        if($user){
            //已登录
            \app\home\model\Cart::where(['user_id'=>$user['id'],'goods_id'=>$data['goods_id'],'goods_attr_ids'=>$data['goods_attr_ids']])->update(['number'=>$data['number']]);
        }else{
            //未登录
            $cart = unserialize(cookie('cart'));
            $key = $data['goods_id'] .'-'.$data['goods_attr_ids'];
            $cart[$key] = $data['number'];
            cookie('cart',serialize($cart),86400*7);
        }
        $result = [
            'code'=> 200,
            'msg'=> 'success'
        ];
        return json($result);
    }

    /**
     * 添加购物车
     */
    public function addcart(Request $request)
    {
        if($request->isGet()){
            //禁止get请求
            $this->redirect('/');
        }
        $data = $request->param();
        \app\home\model\Cart::addCart($data['goods_id'],$data['goods_attr_ids'],$data['number']);
        //查询商品表
        $goods = \app\home\model\Goods::find($data['goods_id']);
        //通过attr_id查询属性表
        $n = $data['number'];
        $goodsattr = \app\admin\model\GoodsAttr::where('id','in',$data['goods_attr_ids'])->select();
        foreach ($goodsattr as $key=>&$value){
            $attr = \app\admin\model\Attribute::find($value['attr_id']);
            $value['attr_name'] = $attr['attr_name'];
        }
        return view('addcart',['goods'=>$goods,'n'=>$n,'goodsattr'=>$goodsattr]);
    }


    /**
     * 删除指定资源
     */
    public function delete()
    {
        $data = request()->param();
        //检验参数
        $user = session('user_info');
        if($user){
            //已登录
            $where = [
                'user_id'=>$user['id'],
                'goods_id'=>$data['goods_id'],
                'goods_attr_ids'=>$data['goods_attr_ids']
            ];
            \app\home\model\Cart::destroy($where);
        }else{
            //未登录
            $cart = unserialize(cookie('cart'));
            $key = $data['goods_id'] .'-'.$data['goods_attr_ids'];
            if(array_key_exists($key,$cart)){
                //数组中删除
                unset($cart[$key]);
            }
            //把新的数组重新存到cookie中
            cookie('cart',serialize($cart),86400*7);
        }
            $result = [
                'code'=> 200,
                'msg'=> 'success'
            ];
            return json($result);
    }
}
