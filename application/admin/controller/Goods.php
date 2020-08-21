<?php

namespace app\admin\controller;
use think\Request;
use think\Validate;
use app\admin\model\Category;
use think\Db;
class Goods extends Base
{
    public function index()
    {
        //$list = \app\admin\model\Goods::order('id desc')->paginate(10);
		$list = Db::name('goods')->order('id desc')->paginate(10);
        return view('index',['list'=>$list]);
    }

    public function create()
    {
        $category = new Category();
        //查询所有的一级分类
        $cate = $category->where('pid',0)->select();
        //查询所有商品类型
        $type = \app\admin\model\Type::select();
        return view('create',['cate'=>$cate,'type'=>$type]);
    }

    public function save(Request $request)
    {
        $data = $request->param();//dump($data);die;
        $data['goods_introduce'] = $request->param('goods_introduce','','remove_xss');//防止xss攻击
        //定义验证规则
        $rule = [
            'goods_name'=>'require',
            'goods_price'=>'require|float|gt:0',
            'goods_number'=>'require|integer|gt:0',
            'cate_id'=>'require|integer|gt:0',
            'type_id'=>'require|integer|gt:0'
        ];
        //定义提示信息
        $msg = [
            'goods_name.require'=>'商品名称必须填写',
            'goods_price.require'=>'商品价格必须填写',
            'goods_price.float'=>'商品价格必须是数字',
            'goods_price.gt'=>'商品价格必须大于0',
            'goods_number.require'=>'商品数量必须填写',
            'goods_number.integer'=>'商品数量必须是整数',
            'goods_number.gt'=>'商品数量必须大于0',
            'cate_id.require'=>'商品分类必须选择',
            'type_id.require'=>'商品类型必须选择',
        ];
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            $error = $validate->getError();
            $this->error($error);
        }
        $data['goods_logo'] = $this->upload_logo();
        //添加入库
        $res = \app\admin\model\Goods::create($data,true);//dump($res);die;
        $this->upload_pics($res['id']);
        //商品属性入库
        foreach ($data['attr_value'] as $k=>$v){
            foreach ($v as $value){
                 $row = [
                       'goods_id'=>$res['id'],
                       'attr_id'=>$k,
                       'attr_value'=>$value
                 ];
                 $goodsattr[] = $row;
            }
        }
        $goodsattr_model = new \app\admin\model\GoodsAttr();
        $goodsattr_model->saveAll($goodsattr);
        $this->success('添加成功','index');
    }

    public function edit(Request $request)
    {
        if($request->isGet()){
            $goods_id = $request->param('id');
            //查询当前商品信息
            $info = \app\admin\model\Goods::find($goods_id);//dump($info);die;
            //查询所有的一级分类信息
            $cate_one_all = Category::where('pid',0)->select();
            //查询商品所属的三级分类信息（pid 就是所属的二级分类id） $cate_three['pid']
            $cate_three = \app\admin\model\Category::find($info['cate_id']);
            //查询商品所属的二级分类信息（pid 就是所属的一级分类id） $cate_two['pid']
            $cate_two = \app\admin\model\Category::find($cate_three['pid']);
            //查询商品所属一级分类下所有的二级分类
            $cate_two_all = \app\admin\model\Category::where('pid', $cate_two['pid'])->select();
            //查询商品所属二级分类下所有的三级分类
            $cate_three_all = \app\admin\model\Category::where('pid', $cate_three['pid'])->select();
            //查询商品相册
            $goodspics = \app\admin\model\Goodspics::where('goods_id',$goods_id)->select();
            $type = \app\admin\model\Type::select();
            return view('edit', [
                'info' => $info,
                'cate_one_all' => $cate_one_all,
                'cate_two_all' => $cate_two_all,
                'cate_three_all' => $cate_three_all,
                'cate_two' => $cate_two,
                'goodspics'=> $goodspics,
                'type'=> $type
            ]);
        }else{
            $data = $request->param();//接收数据
            $data['goods_introduce'] = $request->param('goods_introduce','','remove_xss');//防止xss攻击
            $goods_id = $data['id'];
            //定义验证规则
            $rule = [
                'goods_name'=>'require',
                'goods_price'=>'require|float|gt:0',
                'goods_number'=>'require|integer|gt:0',
                'cate_id' => 'require'
            ];
            //定义提示信息
            $msg = [
                'goods_name.require'=>'商品名称必须填写',
                'goods_price.require'=>'商品价格必须填写',
                'goods_price.float'=>'商品价格必须是数字',
                'goods_price.gt'=>'商品价格必须大于0',
                'goods_number.require'=>'商品数量必须填写',
                'goods_number.integer'=>'商品数量必须是整数',
                'goods_number.gt'=>'商品数量必须大于0',
                'cate_id.require'=>'商品分类必须选择',
            ];
            $validate = new Validate($rule,$msg);
            $result   = $validate->check($data);
            if(!$result){
                $error = $validate->getError();
                $this->error($error);
            }
//            $file = $request->file('logo');//dump($file);die;
//            //判断有没有上传图片
//            if($file){
//                $data['goods_logo'] = $this->upload_logo();//缩略图路径
//            }
            //修改入库
            \app\admin\model\Goods::update($data,['id'=>$goods_id],true);
            $this->upload_pics($goods_id);
            $this->success('修改成功','index');
        }

    }

    private function upload_logo(){
            //接收文件信息 得到文件对象
            $file = request()->file('goods_logo');
            if(empty($file)){
                $this->error('商品logo图片必须上传');
            }
            //将文件移动到指定的目录下
            $info = $file->validate(
                ['size' => 5 * 1024 * 1024, 'ext' => 'jpg,png,gif,jpeg'])
                ->move('D:\project2\shop\public' . DIRECTORY_SEPARATOR . 'uploads'
            );
            //判断处理结果
            if($info){
                //上传成功 ,获取到上传后文件的保存路径
                $goods_logo = DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR . $info->getSaveName();
                //生成缩略图
                //打开原始图片
                $image = \think\Image::open('.' . $goods_logo);
                $image->thumb(210, 240)->save('.' . $goods_logo);
                return $goods_logo;
            }else{
                //上传失败 获取到错误信息，报错
                $error = $file->getError();
                $this->error($error);
            }
    }

    public function upload_pics($goods_id){
        $files = request()->file('goods_pics');
        $goods_pics = [];
        foreach ($files as $file){
            //将上传的图片移动到指定目录
            $info = $file->validate(
                ['size' => 5 * 1024 * 1024, 'ext' => 'jpg,png,gif,jpeg'])
                ->move('D:\project2\shop\public' . DIRECTORY_SEPARATOR . 'uploads'
                );
            if($info){
                //上传成功,获取图片路径 /upload/20160510/42a79759f284b767dfcb2a0197904287.jpg
                $pic_path = DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR . $info->getSaveName();
                $pic_path_arr = explode(DIRECTORY_SEPARATOR,$info->getSaveName());
                //缩略图的保存路径/upload/20160510/thumb_800_42a79759f284b767dfcb2a0197904287.jpg
                $pics_big = DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR .$pic_path_arr[0] .DIRECTORY_SEPARATOR .'thumb_800_'.$pic_path_arr[1];
                $pics_sma = DIRECTORY_SEPARATOR . 'uploads' .DIRECTORY_SEPARATOR .$pic_path_arr[0].DIRECTORY_SEPARATOR .'thumb_400_'.$pic_path_arr[1];
                //生成两种尺寸的缩略图
                $image = \think\Image::open('.' . $pic_path);
                $image->thumb(600,600)->save('.' . $pics_big);
                $image->thumb(400,400)->save('.' . $pics_sma);
                //组装二维数组批量添加
                $row = [
                    'goods_id'=>$goods_id,
                    'pics_big'=>$pics_big,
                    'pics_sma'=>$pics_sma
                ];
                $goods_pics[] = $row;
            }
      }
               $goods_pics_model = new \app\admin\model\Goodspics();
               $goods_pics_model->saveAll($goods_pics);
    }
    
    public function del()
    {
        $goods_id = request()->param('id');
        \app\admin\model\Goods::destroy($goods_id);
        $this->success('删除成功');
    }

    public function delPics()
    {
        $id = request()->param('id');
        $info = \app\admin\model\Goodspics::destroy($id);
        if($info){
            //返回数据
            $res = [
                'code'=>200,//状态码
                'msg'=>'success',//提示信息
            ];
            return json($res);
        }
    }
}