<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/12
 * Time: 19:45
 */

namespace app\home\controller;

use think\Controller;
use think\cache\driver\Redis;

class Mredis extends Controller
{
    public function redis()
    {
        //$cate = \app\home\model\Category::find(3);
        $redis=new Redis();
        //$redis->set('cate',$cate);
        //dump($redis->lpush('list1','zhanshen'));
//        dump($redis->llen('list1'));
//        dump($redis->lrange('list1',0,-1));
//        dump($redis->lpop('list1'));
    }

    public function miaosha()
    {
        $redis=new Redis();
        $goods_number = 20; //定义库存
        $goods_list = 'goods_list'; //定义链表
        $user_id = session('user_info.id');
        //当前人数少于20的时候，加入到队列中
        if($redis->llen($goods_list) < $goods_number){
            $redis->lpush($goods_list,$user_id);
            //抢购成功

        }else{
            echo '当前人数超过20人，秒杀结束';
        }

    }
}
