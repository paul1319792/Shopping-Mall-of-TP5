<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Goods as GoodsModel;
use think\Request;
use app\admin\model\Category as CategoryModel;
use think\Validate;
use think\Db;
class Goods extends Base
{
   	//测试代码
    public function demo(){
   		return password_hash('123456',PASSWORD_DEFAULT);
        //$res  = password_verify('123456','$2y$10$oGj9MgyGlinGpYYGSTcM0uvK0/2grzQ5LfYyHYFhM8aS1pZlHecRW');
       //var_dump($res);
   	}

    //商品列表
    public function index(){
    	$data = GoodsModel::with('category')->select();
    	return view('index',compact('data'));
    }

    //添加商品
    public function add(Request $request){
    	if($request->isGet()){
    		//展示修改的表单
    		//取出栏目的数据
    		$catedata = getCategoryTree(CategoryModel::select());
    		return view('add',compact('catedata'));
    	}else if($request->isPost()){
    		//接收提交的数据
    		$data = $request->post();
    		//定义验证规则
    		$rules = [
    			'goods_name'=>'require|unique:goods,goods_name',
    			'cat_id'=>'require|number',
    			'shop_price'=>'require|float',
    			'market_price'=>'require|float',
    			'goods_number'=>'require|number',
    			'is_best'=>'require|in:0,1',
    			'goods_desc'=>'require|min:5'
    		];
    		//定义错误提示
    		$msg = [
    			'goods_name.require'=>'栏目名称不能为空',
    			'goods_name.unique'=>'商品名称已经存在',
    			'cat_id.require'=>'所属栏目不能为空',
    			'cat_id.number'=>'所属栏目数据格式异常',
    			'shop_price.require'=>'本店价格不能为空',
    			'market_price.require'=>'市场价格不能为空',
    			'is_best.in'=>'精品数据异常',
    			'goods_desc.min'=>'商品简短描述至少是5个字符'
    		];
    		//创建验证对象
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//验证通过,开始入库
    			//对富文本编辑器，提交的数据，采用removeXss函数过滤。
    			$data['goods_intro'] = $request->param('goods_intro','','removeXss');
                //p($data);exit;
                $data['goods_attr'] = serialize($data['attr']);
    			$res = GoodsModel::create($data,true);
    			if($res){
    				return ['info'=>1];
    			}else {
    				return ['info'=>0,'error'=>'入库失败'];
    			}
    		}else {
    			//未通过验证
    			$error = implode(',',$validate->getError());
    			return ['info'=>0,'error'=>$error];
    		}
    	}
    }

    //完成图片上传的操作
    public function upimage(Request $request){
    	config('default_return_type','json');//在当前方法里面返回值的类型是json格式；
    	//处理上传的文件；
    	$file = $request->file('file');
    	//开始上传
    	$path = './uploads/goods/'.date('Y/m/d/');
    	$info = $file->rule('uniqid')->move($path);
    	if($info){
    		//说明已经上传成功了
    		$filename  = $info->getSaveName();//获取文件名称
    		//制作两张缩略图
    		$image = \think\Image::open($path.$filename);
    		//制作中图缩略图
    		$goods_img = $path.'img_'.$filename;
    		$image->thumb(400, 400)->save($goods_img);
    		//制作下缩略图
    		$goods_thumb = $path.'thumb_'.$filename;
    		$image->thumb(215, 250)->save($goods_thumb);

    		//要返回图的路径
    		return [
    			'goods_ori'=>ltrim($path.$filename,'.'),
    			'goods_img'=>ltrim($goods_img,'.'),
    			'goods_thumb'=>ltrim($goods_thumb,'.')
    		];
    	}
    }

    //相册管理
    public function album(Request $request,$id){
       //展示添加相册的表单  //完成相册上传（多文件上传）
        if($request->isGet()){
            //展示添加相册的表单
            return view('album');
        }else if($request->isPost()){
            //echo '1';exit;   
            //完成相册的添加
            //获取上传的文件
            $files = $request->file('goods_album');
            foreach($files as $file){
                //上传文件的存储路径
                $path = './uploads/album/'.date('Y/m/d/');
                $info = $file->rule('uniqid')->move($path);
                if($info){
                    //上传成功；
                    //获取上传的文件名称 (上传文件具体的存储位置)
                    $filename = $info->getSaveName();
                    $goods_ori =  $path.$filename;
                    //生成缩略图；
                    //制作两张缩略图
                    $image = \think\Image::open($goods_ori);
                    //制作中图缩略图
                    $goods_img = $path.'img_'.$filename;
                    $image->thumb(400, 400)->save($goods_img);
                    //制作下缩略图
                    $goods_thumb = $path.'thumb_'.$filename;
                    $image->thumb(56, 56)->save($goods_thumb);
                    //直接入相册表操作
                    Db::name('goods_album')->insertGetId([
                        'goods_id'=>$id,
                        'album_ori'=>ltrim($goods_ori,'.'),
                        'album_thumb'=>ltrim($goods_thumb,'.'),
                        'album_img'=>ltrim($goods_img,'.')
                    ]);
                }
            }
            //上传完毕；，窗口要关闭；
            echo '<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>';
            echo '<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui.admin/js/H-ui.admin.js"></script>';
            echo "<script>alert('success!');layer_close()</script>";
        }
    }
}
