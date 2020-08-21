<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Login extends Controller
{
    /**
     * 用户登录
     */
    public function login()
    {
        //临时关闭模板布局
        $this->view->engine->layout(false);
        return view();
    }

    /**
     * 用户注册页面
     */
    public function register()
    {
        //临时关闭模板布局
        $this->view->engine->layout(false);
        return view();
    }

    //ajax请求发送验证码
    public function sendcode()
    {
        //接收参数
        $phone = request()->param('phone');
        //检测参数 略
        //生成验证码，组装短信内容
        $code = mt_rand(1000, 9999);
        //$msg = '【品优购商城】验证码为：' . $code . ',欢迎注册平台！';
        //发送短信
        //$res = sendmsg($phone, $msg);//测试阶段关闭
        $res = true;//开发测试阶段
        if($res === true){
            //发送成功 记录发送的验证码，用于后续验证,保持手机号和验证码的对应关系
            cache('register_code_' . $phone, $code);
            //$result = ['code' => 10000, 'msg' => '发送成功'];
            $result = ['code' => 10000, 'msg' => '发送成功', 'data' => $code];//开发测试阶段
        }else{
            //发送失败
            $result = ['code' => 10001, 'msg' => $res];
        }
        return json($result);
    }

    public function phone()
    {
        $data = request()->param();
        //检测参数
        $rule = [
            'phone'=> 'require|regex:/^1[3-9]\d{9}$/|unique:user',
            'code'=> 'require|regex:/^\d{4}$/',
            'password'=> 'require|confirm:repassword'
        ];
        $msg = [
            'phone.require' => '手机号不能为空',
            'phone.regex' => '手机号格式不正确',
            'phone.unique' => '手机号已被注册',
            'code.require' => '验证码不能为空',
            'code.regex' => '验证码格式不正确',
            'password.require' => '密码不能为空',
            'password.confirm' => '两次密码输入必须一致',
        ];
        $validate = new \think\Validate($rule,$msg);
        $res = $validate->check($data);
        if(!$res){
            $error = $validate->getError();
            $this->error($error);
        }
        //检测验证码
        $code = cache('register_code_' . $data['phone']);
        if($code != $data['code']){
             $this->error('验证码错误');
        }
        //数据入库
        $data['username'] = $data['phone'];
        $data['password'] = encpypt_password($data['password']);
        $data['is_check'] = 1;
        \app\home\model\User::create($data,true);
        //注册成功
        $this->success('注册成功','login',1);
    }

    public function dologin()
    {
        //接收数据
        $data = request()->param();
        //查user表
        $user = \app\home\model\User::where('username',$data['username'])->find();
        if(!$user){
            $this->error('用户名不存在');
        }
        if($user['password'] != encpypt_password($data['password'])){
            $this->error('密码错误');
        }
        session('user_info',$user->toArray());
        //cookie中购物车数据写入数据库
        \app\home\model\Cart::cookieToDb();
        $back_url = session('back_url') ? session('back_url'):'/';
        $this->success('登陆成功',$back_url,1);
    }

    //退出
    public function logout()
    {
         session(null);
         $this->redirect('home/login/login');
    }
    
    //小程序登录
    public function minapp(Request $request){
        $code = $request->get('code');
        $appid = 'wx8485b248692fb2ff';
        $screct = 'a93976cf110ba853749f5d2758b9f6b6';
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";
        $url = sprintf($url,$appid,$screct,$code);
        $res = http_request($url);
        return $res;
    }

    //qq登录回调
    public function qqcallback()
    {
         echo 'I am here';
    }
}
