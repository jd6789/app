<?php

namespace app\admin\controller;
use think\Request;

class Auth extends Base
{
    /**
     * 显示权限列表
     */
    public function index()
    {
        $list = \app\admin\model\Auth::select();
        $list = getTree($list);
        return view('index',['list'=>$list]);
    }

    /**
     * 显示创建权限表单页
     */
    public function add()
    {
        //查询顶级权限
        $top_auth = \app\admin\model\Auth::where('pid',0)->select();
        return view('add',['top_auth'=>$top_auth]);
    }

    /**
     * 添加权限入库
     */
    public function save(Request $request)
    {
         $data = $request->param();
        //定义验证规则
        $rule = [
            'auth_name'=>'require'
        ];
        $msg = [
            'auth_name.require'=>'权限名称不能为空'
        ];
        //不是顶级权限必须输入控制器和方法
        if($data['pid'] != 0){
            $rule['auth_c'] = 'require';
            $rule['auth_a'] = 'require';
            $msg['auth_c.require'] = '控制器名称不能为空';
            $msg['auth_a.require'] = '方法名称不能为空';
        }
        $validate = new \think\Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            $error = $validate->getError();
            $this->error($error);
        }
        \app\admin\model\Auth::create($data,true);
        $this->success('添加成功','index');
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
