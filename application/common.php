<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if(!function_exists('encpypt_password')){
    function encpypt_password($password){
         $salt = 'adwfsdver3gffbfgb9we';
         return  md5(md5($password).$salt);
    }
}

if (!function_exists('remove_xss')) {
    //使用htmlpurifier防范xss攻击
    function remove_xss($string){
        //相对index.php入口文件，引入HTMLPurifier.auto.php核心文件
        require_once './plugins/htmlpurifier/HTMLPurifier.auto.php';
        // 生成配置对象
        $cfg = HTMLPurifier_Config::createDefault();
        // 以下就是配置：
        $cfg -> set('Core.Encoding', 'UTF-8');
        // 设置允许使用的HTML标签
        $cfg -> set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,br,p[style],span[style],img[width|height|alt|src]');
        // 设置允许出现的CSS样式属性
        $cfg -> set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置a标签上是否允许使用target="_blank"
        $cfg -> set('HTML.TargetBlank', TRUE);
        // 使用配置生成过滤用的对象
        $obj = new HTMLPurifier($cfg);
        // 过滤字符串
        return $obj -> purify($string);
    }
}
if (!function_exists('getTree')) {
    //递归方法实现无限极分类
    function getTree($list,$pid=0,$level=0) {
        static $tree = array();
        foreach($list as $row) {
            if($row['pid']==$pid) {
                $row['level'] = $level;
                $tree[] = $row;
                getTree($list, $row['id'], $level + 1);
            }
        }
        return $tree;
    }
}

if(!function_exists('get_cate_tree')){
    //递归方式实现 无限极分类树
    function get_cate_tree($list, $pid=0){
        $tree = array();
        foreach($list as $row) {
            if($row['pid']==$pid) {
                $row['son'] = get_cate_tree($list, $row['id']);
                $tree[] = $row;
            }
        }
        return $tree;
    }
}

if(!function_exists('curl_request')){
    //使用curl函数库调用接口
    function curl_request($url, $post = false, $params = [], $https = false){
        //①使用curl_init初始化请求会话
        $ch = curl_init($url);
        //②使用curl_setopt设置请求一些选项
        if($post){
            //设置请求方式、请求参数
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        if($https){
            //https协议，禁止curl从服务器端验证本地证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        //③使用curl_exec执行，发送请求
        //设置 让curl_exec 直接返回接口的结果数据
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        //④使用curl_close关闭请求会话
        curl_close($ch);
        return $res;
    }
}
if(!function_exists('sendmsg')){
    //发送短信
    function sendmsg($phone, $msg)
    {
        //获取接口地址
        $gateway = config('msg.gateway');
        $appkey = config('msg.appkey');
        //拼接url  发送get请求
        $url = $gateway . '?appkey=' . $appkey . '&mobile=' . $phone . '&content=' . $msg;
        //发送请求
        $res = curl_request($url, false, [], true);
        //解析返回结果
        if(!$res){
            return '服务器异常，请求发送失败';
        }
        $arr = json_decode($res, true);
        if(isset($arr['code']) && $arr['code'] == 10000){
            //短信发送成功
            return true;
        }else{
            return $arr['msg'];
//            return '短信发送失败';
        }
    }
}

if(!function_exists('encpypt_phone'))
{
     function encpypt_phone($phone)
     {
           return substr($phone,0,3) .'****'.substr($phone,7);
     }
}
