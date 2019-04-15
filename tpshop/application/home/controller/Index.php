<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\admin\model\Category;
use app\admin\model\Goods;
use app\admin\model\Attribute;
use think\Db;
class Index extends Controller
{
    //前台首页
    public function index()
    {
        //载入栏目数据，
        //取出顶级栏目数据
        $cateA = Category::where('parent_id',0)->select();
        //取出非顶级栏目
        $cateB = Category::where('parent_id','>',0)->select();

        return view('index',compact('cateA','cateB'));
    }

    //栏目页面
    public function category(Request $request,$id)
    {
        //根据栏目的id,获取商品数据
        //获取子栏目的id；
        $ids = getChildId(Category::select(),$id);
        $ids[] = $id;//把自己也添加到该数组里面；
        //P($ids);
        $data = Goods::where('cat_id','in',$ids)->select();
        return view('category',compact('data'));
    }
    //商品详情页面
    public function detail(Request $request,$id){
       $info = Goods::find($id);///返回商品的基本数据
       //取出商品的相册数据
       $albumdata = Db::name('goods_album')->where('goods_id',$id)->select();
       //取出商品的属性信息
       //可以把商品类型的属性信给取出来；
       $attrdata =  Attribute::where('type_id',$info->goods_type_id)->select();
       //要把商品属性的值也给取出；
       $attrinfo = unserialize($info->goods_attr);
       //p($attrdata);exit;
       foreach($attrdata as $k=>$v){
            $attrdata[$k]['value'] = $attrinfo[$v->id];
       }
       //p($attrdata);
       return view('detail',compact('info','albumdata','attrdata'));
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
