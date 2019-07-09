<?php

namespace app\admin\controller;
use think\Controller;
use app\admin\model\Manager;

class Login extends Controller
{
    public  function  login(){
        if(request()->isGet()){
            //展示登录页
            //临时关闭模板布局
            $this->view->engine->layout(false);
            return view();
        }else{
            //提交表单
            //接收参数
            $data = request()->param();
            //校验验证码
            if(!captcha_check($data['code'])){
                $this->error('验证码错误');
            }
            //查询用户表
            $password = encpypt_password($data['password']);
            $where = [
                 'username'=>$data['username'],
                 'password'=>$password
            ];
            $manager = new Manager();
            $info = $manager::where($where)->find();//模型对象
            if(!$info){
                $this->error('用户名或密码错误');
            }
            session('manager_info',$info->toArray());
            //成功跳转后台首页
            $this->success('登陆成功','index/index','',1);
        }
    }

    //退出
    public function logout(){
        session('manager_info',null);
        $this->redirect('login');
    }
}