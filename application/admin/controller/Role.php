<?php

namespace app\admin\controller;

use think\Request;

class Role extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list = \app\admin\model\Role::select();
        return view('index',['list'=>$list]);
    }

    /**
     * 显示角色赋值权限表单
     */
    public function setauth()
    {
        //查询角色信息
        $role_id = request()->param('id');
        $role = \app\admin\model\Role::find($role_id);
        $role_auth = explode(',',$role['role_auth_ids']);
        //查询所有的顶级权限
        $auth = \app\admin\model\Auth::where('pid',0)->select();
        //查询所有的子级权限
        $auth_son = \app\admin\model\Auth::where('pid','>',0)->select();
        return view('setauth',
            [   'role'=>$role,
                'auth'=>$auth,
                'auth_son'=>$auth_son,
                'role_auth'=>$role_auth
            ]);
    }

    /**
     * 给角色分配权限
     */
    public function saveAuth(Request $request)
    {
         $auth_info = $request->param('id');//array
         $role_auth_ids = implode(',',$auth_info);//权限id
         $role_id = $request->param('role_id');//角色id
         $model = new \app\admin\model\Role();
         $model->where('id',$role_id)->update(['role_auth_ids'=>$role_auth_ids]);
         $this->success('修改成功');
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
