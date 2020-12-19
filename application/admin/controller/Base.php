<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\admin\model\Auth;
use app\admin\model\Role;
class Base extends Controller
{
    //构造方法
    public function __construct()
    {
        parent::__construct();
        //登录判断
        if(!session('manager_info')){
            //没有登录，跳转到登录页
            $this->redirect('admin/login/login');
        }
        //权限验证
        $this->checkAuth();
        //获取所拥有的权限列表展示
        $this->getAuth();
    }

    //查询当前管理员的权限信息
    public function getAuth(){
        //从session中查询当前管理员的角色id
        $manager_info = session('manager_info');
        $role_id = $manager_info['role_id'];
        $auth = new Auth();
        $role = new Role();
        //超级管理员查询所有权限
        if($role_id == 1){
            //查询所有的顶级权限
            $auth_top = $auth->where(['pid'=>0,'is_nav'=>1])->select();
            //查询所有的子级权限
            //$auth_son = Db::query("SELECT * FROM `tpshop_auth` WHERE `pid` > 0 AND `is_nav` = 1");
            $auth_son = $auth->where('pid','>',0)->where('is_nav',1)->select();
        }else{
            //普通管理员查询所拥有的权限
            $role_info =$role->find($role_id);
            $role_auth_ids = $role_info['role_auth_ids'];
            //查询所有的顶级权限
            $auth_top = $auth->where(['id'=>['in',$role_auth_ids],'is_nav'=>1,'pid'=>0])->select();
            //查询所有的子级权限
            //$auth_son = Db::query("SELECT * FROM `tpshop_auth` WHERE `pid` > 0 AND `is_nav` = 1 AND `id` in ($role_auth_ids)");
            $auth_son = $auth->where('pid','>',0)->where('is_nav',1)->where('id','in',$role_auth_ids)->select();
        }
            $this->assign('auth_top',$auth_top);
            $this->assign('auth_son',$auth_son);
    }

    //权限检测
    public function checkAuth()
    {
        //查询当前管理员的角色信息
        $role_id = session('manager_info.role_id');
        if($role_id == 1){
            //超级管理员不验证权限
            return true;
        }
        $controller = request()->controller();//首字母大写
        $action = request()->action();//首字母小写(判断和查询数据库不区分大小写)
        if($controller == 'Index' && $action == 'index'){
             //首页不验证权限
             return true;
        }
        //查询当前角色拥有的role_auth_ids
        $role = Role::find($role_id);
        $role_auth_ids = explode(',',$role['role_auth_ids']);
        //查询当前访问的权限id
        $auth = Auth::where(['auth_c'=>$controller,'auth_a'=>$action])->find();
        $auth_id = $auth['id'];
        //判断权限id是否在role_auth_ids中
        if(!in_array($auth_id,$role_auth_ids)){
             $this->error('没有权限');
        }
        return true;
    }
}