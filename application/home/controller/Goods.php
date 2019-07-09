<?php

namespace app\home\controller;

use think\Request;

class Goods extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        $cate_id = request()->param('id');
        $cate = \app\home\model\Category::find($cate_id);
        $goods = \app\home\model\Goods::where('cate_id',$cate_id)->order('id desc')->paginate(10);//分页
        return view('index',['goods'=>$goods,'cate'=>$cate]);
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
     * 显示商品详情
     */
    public function read()
    {
        $id = request()->param('id');
        //查询商品表
        $goods = \app\home\model\Goods::find($id);
        //查询相册表
        $goodspics = \app\admin\model\Goodspics::where('goods_id',$id)->select();
        //查询商品类型对应的所有属性
        $attribute = \app\admin\model\Attribute::where('type_id',$goods['type_id'])->select();
        //查询商品的属性值
        $goods_attr = \app\admin\model\GoodsAttr::where('goods_id',$id)->select();
        $goodsattr = [];
        foreach ($goods_attr as $v){
            $goodsattr[$v['attr_id']][] = $v->toArray();
        }
        return view('read',['goods'=>$goods,'goodspics'=>$goodspics,'attribute'=>$attribute,'goodsattr'=>$goodsattr]);
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

}
