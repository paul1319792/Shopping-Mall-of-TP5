<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Cart;
use app\admin\model\Goods;
class Shop extends Controller
{
    public function demo(){
        //cookie('cart',null);exit;
        session('user_id',null);
        session('user_name',null);
       /*$data =  Goods::select();
       foreach($data as $v){
            echo $v->id.'---'.$v->shop_price.'<br/>';
       }*/
      // p($data);
    }
    //添加商品到购物车
    public function add(Request $request)
    {
        $data  =  $request->post();
        //p($data);exit;
        $goods_id = $data['goods_id'];//获取goods_id;
        $goods_attrs  = $data['attr'];//获取属性信息
        $goods_buy_number = $data['goods_buy_number'];
        //取出商品数据
        $goodsInfo = Goods::field('id,goods_name,goods_thumb')->find($goods_id);
        //拼接属性信息
        //p($goods_attrs);
        $attrs = '';
        foreach($goods_attrs as $k=>$v){
            $attrs.=getAttributeName($k).':'.$v.' ';
        }
        //p($attrs);exit;
        //调用购物车模型的添加购物车的方法，完成添加操作；
        $cart = new Cart();
        $cart->addCart($goods_id,$goods_attrs,$goods_buy_number);
        return view('add_show',compact('goodsInfo','attrs','goods_buy_number'));
    }

    //购物车列表页面
    public function cartinfo()
    {
        $cart = new Cart();
        $cartData = $cart->cartInfo();//购物车数据
        //p($cartData);exit();
        $total = $cart->getCartTotal();//购物车商品数量和总价
        return view('cartinfo',compact('cartData','total'));
    }

    //定义删除购物车数据的方法；
    public function delcart(Request $request)
    {
        //接收传递的数据
        $uid = $request->param('uid');
        $cart = new Cart();
        $cart->delCart($uid);
        return $this->redirect('home/shop/cartinfo');
    }

    //定义修改购物车的方法
    public function updatecart(Request $request)
    {
        //接收传递的uid数据
        $uid = $request->param('uid');
        $cart = new Cart();
        $cart->updateCart($uid,1);
        return ['info'=>1];
    }

    //结算页面
    public function checkout()
    {
        //判断购物车里面是否有商品，如果没有就不向下走了
        $cart = new Cart();
        $cartData = $cart->cartInfo();
        if(empty($cartData)){
            //空购物车数据
            $this->error('购物车空，无法进行结算');
        }
        //判断是否登录，只有登录后，才能进入结算页面，
        if(!session("?user_id")){
            //未登录，则就跳转到登录页面，
            //登录完成后，再跳回到当前页面， 使用session保存当前页面地址
            session('back_url','home/shop/checkout');
            return $this->redirect('home/user/login');
        }
        //取出收货人的信息
        $user_id = session('user_id');
        $consigneeData = \app\home\model\Consignee::where('user_id',$user_id)->select()->toArray();
        //p($consigneeData);exit;
        return view('checkout',compact('cartData','consigneeData'));

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
