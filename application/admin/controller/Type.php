<?php

namespace app\admin\controller;
use think\Request;

class Type extends Base
{
    /**
     * 显示资源列表
     */
    public function index()
    {
        $type = \app\admin\model\Type::select();
        return view('index',['type'=>$type]);
    }

    /**
     * 显示添加类型表单页.
     */
    public function add()
    {
        return view();
    }

    /**
     * 保存新建的资源
     */
    public function save(Request $request)
    {
         $data = $request->param();
         \app\admin\model\Type::create($data);
         $this->success('操作成功','index');
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
    public function delete()
    {
        $id = request()->param('id');
        \app\admin\model\Type::destroy($id);
        $this->success('操作成功');
    }
}
