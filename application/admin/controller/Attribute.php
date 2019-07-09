<?php

namespace app\admin\controller;
use think\Request;

class Attribute extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $attribute = \app\admin\model\Attribute::select();
        foreach ($attribute as $k=>$v){
             $type = \app\admin\model\Type::find($v['type_id']);
             $attribute[$k]['type_name'] = $type['type_name'];
        }
        return view('index',['attribute'=>$attribute]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $type = \app\admin\model\Type::select();
        return view('create',['type'=>$type]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->param();
        \app\admin\model\Attribute::create($data,true);
        $this->success('操作成功','index');
    }

    //根据商品类型查询属性信息
    public function getAttr()
    {
        $type_id = request()->param('id');
        $data = \app\admin\model\Attribute::where('type_id',$type_id)->select();
        foreach ($data as &$v){
            $v['attr_values'] = explode(',',$v['attr_values']);//属性值转为数组
        }
        //返回数据
        $res = [
            'code'=>200,//状态码
            'msg'=>'success',//提示信息
            'data'=>$data
        ];
        return json($res);
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
