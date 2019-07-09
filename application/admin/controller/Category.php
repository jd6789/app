<?php

namespace app\admin\controller;
use think\Request;
class Category extends Base
{
    /**
     * 根据父分类查询子分类
     */
    public function getCateByPid()
    {
        $id = request()->param('id');
        //检测id参数
        if(!preg_match('/^\d+$/', $id)){
            //id参数格式错误
            $res = [
                'code' => 0,
                'msg' => 'error'
            ];
            return json($res);
        }
        //根据分类id查询子分类
        $data = \app\admin\model\Category::where('pid',$id)->select();
        //返回数据
        $res = [
            'code'=>200,//状态码
            'msg'=>'success',//提示信息
            'data'=>$data
        ];
        return json($res);
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
