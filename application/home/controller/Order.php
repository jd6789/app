<?php

namespace app\home\controller;

use think\Request;

class Order extends Base
{
    /**
     * 显示订单列表
     */
    public function index()
    {
        //判断是否登录
        if(!session('user_info')){
            //跳转到登录页
            $back_url = 'home/order/index';
            //将登陆成功后跳回的地址存入session
            session('back_url',$back_url);
            $this->redirect('home/login/login');
        }
        //查询所有订单
        $order = \app\home\model\Order::where('user_id',session('user_info.id'))->field('id,order_sn,order_amount,consignee_name,pay_status,pay_type,create_time')->select();
        //查询订单下的商品
        foreach ($order as $k=>$v){
            $order[$k]['goods'] = \app\home\model\OrderGoods::where('order_id',$v['id'])->field('goods_name,goods_logo,number')->select();
        }
        //dump($order);die;
        return view('index',['order'=>$order]);
    }

    /**
     * 显示提交订单页面
     */
    public function create()
    {
        //判断是否登录
        if(!session('user_info')){
            //跳转到登录页
            $back_url = 'home/cart/index';
            //将登陆成功后跳回的地址存入session
            session('back_url',$back_url);
            $this->redirect('home/login/login');
        }
        //查询收货地址
        $address = \app\home\model\Address::where('user_id',session('user_info.id'))->select();
        //配置项中获取支付方式
        $pay_type = config('pay_type');
        //接收参数
        $cart_ids = request()->param('cart_ids');
        $cart = \app\home\model\Cart::where('id','in',$cart_ids)->select();
        $totle_number  = 0;
        $totle_price  = 0;
        foreach ($cart as $k=>&$v){
                $v['goods'] = \app\home\model\Goods::find($v['goods_id']);
                $totle_number += $v['number'];
                $totle_price += $v['number']*(int)($v['goods']['goods_price']);
        }
        //dump($totle_price);die;
        return view('create',['address'=>$address,'pay_type'=>$pay_type,'cart'=>$cart,'totle_number'=>$totle_number,'totle_price'=>$totle_price]);
    }

    /**
     * 生成订单入库
     */
    public function save(Request $request)
    {
        $data = $request->param();//dump($data);die;
        //参数检验
        //生成订单编号
        $order_sn = mt_rand(100000,999999).time();
        //购物车表和商品表连表查询
        $cart_data = \app\home\model\Cart::alias('t1')
            ->join('tpshop_goods t2','t1.goods_id=t2.id','left')
            ->field('t1.*,t2.goods_name,t2.goods_logo,t2.goods_price')
            ->where('t1.id','in',$data['cart_ids'])
            ->select();
        //dump($cart_data);die;
        $order_amount = 0;
        foreach ($cart_data as $v){
            $order_amount += $v['number'] * (int)($v['goods_price']);
        }
        $user_id = session('user_info.id');
        $address = \app\home\model\Address::find($data['address_id']);
        $row = [
            'order_sn'=>$order_sn,
            'order_amount'=>$order_amount,
            'user_id'=>$user_id,
            'consignee_name'=>$address['consignee'],
            'consignee_phone'=>$address['phone'],
            'consignee_address'=>$address['address'],
            'shipping_type'=>'shunfeng',
            'pay_type'=>$data['pay_type']
        ];
        //添加到订单表
        $order = \app\home\model\Order::create($row,true);//返回一位数组，包含订单id
        $order_goods = [];
        foreach ($cart_data as $v){
             $row = [
                 'order_id' => $order['id'],
                 'goods_id' => $v['goods_id'],
                 'number' => $v['number'],
                 'goods_attr_ids' => $v['goods_attr_ids'],
                 'goods_logo' => $v['goods_logo'],
                 'goods_name' => $v['goods_name'],
                 'goods_price' => $v['goods_price']
             ];
            $order_goods[] = $row;
        }
        $order_goods_model = new \app\home\model\OrderGoods();
        $order_goods_model->saveAll($order_goods);
        //删除购物车已提交订单的记录
        \app\home\model\Cart::destroy($data['cart_ids']);
        //跳转到支付
        switch ($data['pay_type'])
        {
            case 'alipay':
                //支付宝
                //PRG模式  模拟表单提交
                $html = "<form id='alipayment' action='/plugins/alipay/pagepay/pagepay.php' method='post' style='display:none'>
<input id='WIDout_trade_no' name='WIDout_trade_no' value='$order_sn' /> 
<input id='WIDsubject' name='WIDsubject' value='品优购商城订单'/>
<input id='WIDtotal_amount' name='WIDtotal_amount' value='$order_amount' />
<input id='WIDbody' name='WIDbody' value='品优购商城的商品，没有货可发'/>
</form><script>document.getElementById('alipayment').submit();</script>";
                echo $html;
            break;
            case 'wechat':
                //微信
            break;
            case 'cart':
                //银联
            break;
        }
    }


    //同步跳转,显示支付成功页(给用户)
    public function callback()
    {
        $data = request()->param();
        //验证签名  验签
        require_once("/plugins/alipay/config.php");
        require_once '/plugins/alipay/pagepay/service/AlipayTradeService.php';
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($data);//true或者false
        if($result){
            $order_sn = $data['out_trade_no'];
            $order = \app\home\model\Order::where('order_sn', $order_sn)->find();
            //验证成功，跳转到支付成功页
            return view('paysuccess', ['total_amount' => $data['total_amount'], 'order_id' => $order['id']]);
        }else{
            //验证失败
            return view('payfail', ['error' => '支付失败，请稍后再试']);
        }
    }

    //异步通知地址  post请求  本地测试 并不会真正调用(给商家做订单处理)
    public function notify()
    {
        //接收参数
        $data = request()->param();
        //验证签名  验签
        require_once("/plugins/alipay/config.php");
        require_once '/plugins/alipay/pagepay/service/AlipayTradeService.php';
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($data);
        if($result){
            //验证成功  修改订单状态
            if($data['trade_status'] == 'TRADE_FINISHED') {
                echo 'success';die;
            }else if ($data['trade_status'] == 'TRADE_SUCCESS') {
                //检测 订单金额 是否正确 略
                //修改订单状态为 已付款
                $order_sn = $data['out_trade_no'];
                \app\home\model\Order::update(['pay_status' => 1], ['order_sn' => $order_sn]);
                //将接收到的参数 记录下来 存到支付表（略）
                echo 'success';die;
            }
        }else{
            //验证失败
            echo 'fail';die;
        }
    }


    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }
}
