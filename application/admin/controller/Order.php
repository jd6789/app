<?php

namespace app\admin\controller;

use think\Request;

class Order extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        //查询所有订单
        $order = \app\admin\model\Order::order('id','desc')->select();
        return view('index',['order'=>$order]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示订单详情
     */
    public function read($id)
    {
        $order = \app\admin\model\Order::alias('t1')
            ->join('tpshop_order_goods t2','t1.id=t2.order_id','left')
            ->field('t1.order_sn,t1.order_sn,t1.consignee_address,t2.id,t2.goods_id,t2.goods_name,t2.goods_logo,t2.goods_price,t2.goods_attr_ids,t2.number')
            ->where('t1.id',$id)
            ->select();

        foreach($order as $k=>$v){
            $goods_attr = \app\admin\model\Attribute::alias('t3')
                ->join('tpshop_goods_attr t4','t3.id=t4.attr_id','left')
                ->field('t3.attr_name,t4.attr_value')
                ->where('t4.id','in',$v['goods_attr_ids'])
                ->select();
            $order[$k]['attr'] = $goods_attr;
        }
        //dump($order);die;
        //展示物流信息
        $type = '';
        $postid = '';
        $url = "https://www.kuaidi100.com/query?type=".$type."&postid=".$postid;
        $res = curl_request($url,false,[],true);
        $logistics = [];
        if($res){
            $arr = json_decode($res,true);
            if($arr['status'] == 200){
                $logistics = $arr['data'];
            }
        }
        return view('read',['order'=>$order,'logistics'=>$logistics]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
