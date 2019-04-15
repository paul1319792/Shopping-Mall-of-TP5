<?php

namespace app\home\model;

use think\Model;

class Cart extends Model
{
    //添加数据到购物车
    //第一个参数：商品的id,
	//第二个参数：是商品的属性信息，是一个数组  数组的格式 [ 3 => 金色,7 => 256G ]
	//第三个参数：是购买数量
   public function addCart($goods_id,$goods_attrs,$goods_buy_number){
   		//制作uid
   		$uid = md5($goods_id.serialize($goods_attrs));
   		//判断是否登录，
   		if(session('?user_id')){
   			//已经登录
   			$user_id = session('user_id');//获取登录用户的id
   			//判断购物车表里面是否有该商品，如果有，就只修改购买数量，如果没有呢就添加
   			$info = self::where('user_id',$user_id)->where('uid',$uid)->find();
   			if($info){
   				//说明该商品已经存在 ，就只修改购买数量
   				//->setInc('字段名称',相加的值)//
   				self::where('user_id',$user_id)->where('uid',$uid)->setInc('goods_buy_number',$goods_buy_number);
   			}else {
   				//说明该商品没有 ,执行添加操作
   				self::create([
   					'goods_id'=>$goods_id,
   					'user_id'=>$user_id,
   					'goods_attrs'=>serialize($goods_attrs),
   					'goods_buy_number'=>$goods_buy_number,
   					'uid'=>$uid
   				]);
   			}
   		}else{
   			//未登录,执行cookie操作
   			//从cookie里面取出数据
   			$data = cookie('?cart')?unserialize(cookie('cart')):[];
   			//判断当前商品是否存在
   			if(isset($data[$uid])){
   				//该商品已经存在，则修改购买数量
   				$data[$uid]['goods_buy_number']+=$goods_buy_number;
   			}else {
   				//该商品不存在,则添加
   				$data[$uid]=[
   					'goods_id'=>$goods_id,
   					'goods_attrs'=>serialize($goods_attrs),
   					'goods_buy_number'=>$goods_buy_number,
   					'uid'=>$uid
   				];
   			}
   			//setcookie('key',$value)
   			//重新把数据给存储起来
   			cookie('cart',serialize($data),3600*24);
   		}
   }

   //购物车列表方法
   public function cartInfo(){
   		//是否登录，
	   	if(session('?user_id')){
	   		//已经登录
	   		$user_id = session('user_id');
	   		$data = self::where('user_id',$user_id)->select()->toArray();
	   	}else {
	   		//未登录
	   		$data = cookie('?cart')?unserialize(cookie('cart')):[];
	   	}
	   	//定义一个空数组，用于存储组合的数据
	   //	p($data);exit;
	   	$cartData = [];
	    //p($data);
	   	foreach($data as $v){
	   		//$v['info']就存储当前商品的信息
	   		$v['info'] = getGoodsInfo($v['goods_id']);
	   		$attrs = '';
	   		$goods_attrs = unserialize($v['goods_attrs']);
	   		//var_dump($goods_attrs);
	   		foreach($goods_attrs as $kk=>$vv){
	   			$attrs.= getAttributeName($kk).':'.$vv.'<br/>';
	   			//echo $vv;
	   		}
	   		$v['attrs']=$attrs;
	   		$cartData[] = $v;
	   	}
	   	//p($cartData);exit;
	   	return $cartData;

   }

   //定义一个购物车商品数量和商品总价格的方法
   public function getCartTotal(){
   		//获取购物车数据
   		$data = $this->cartInfo();
   		//定义两个变量，用于存储商品数量和商品的总价格
   		$totalCount = 0;//总数量
   		$totalPrice = 0;//总价格
   		if($data){
   			foreach($data as $v){
   				$totalCount+=$v['goods_buy_number'];
   				$totalPrice+=$v['goods_buy_number'] * $v['info']['shop_price'];
   			}
   		}
   		return [
   			'totalCount' =>$totalCount,
   			'totalPrice' =>$totalPrice
   		];

   }

   //删除购物车数据
   public function delCart($uid){
   		//判断是否登陆，
   		if(session('?user_id')){
	   		//已经登录
	   		$user_id = session('user_id');
	   	    self::where('user_id',$user_id)->where('uid',$uid)->delete();
	   	}else {
	   		//未登录
	   		$data = cookie('?cart')?unserialize(cookie('cart')):[];
	   		unset($data[$uid]);
	   		//重新把数据给存储起来
   			cookie('cart',serialize($data),3600*24);
	   	}
   }
   //修改购物车的方法（修改购买数量）
   //参数1：商品的uid号
   //参数2：新增的购买数量
   public function updateCart($uid,$goods_buy_number){
   		//判断，是否已经登录，如果已经登录，则修改数据表，如果未登录，则修改cookie的值
   		if(session('?user_id')){
	   		//已经登录
	   		$user_id = session('user_id');
	   	    self::where('user_id',$user_id)
		   	    ->where('uid',$uid)
		   	    ->setInc('goods_buy_number',$goods_buy_number);
	   	}else {
	   		//未登录
	   		$data = cookie('?cart')?unserialize(cookie('cart')):[];
	   		$data[$uid]['goods_buy_number'] = $data[$uid]['goods_buy_number'] + $goods_buy_number;

	   		//重新把数据给存储起来
   			cookie('cart',serialize($data),3600*24);
	   	}
   }

   //把cookie里面的数据移动到数据库
   public function cookie2db(){
   		//从cookie里面取出数据
   		$data = cookie('?cart')?unserialize(cookie('cart')):[];
   		if($data){
   			//有数据，才移动,
   			foreach($data as $k=>$v){
   				//判断数据表里面是否有该商品，如果有，则修改数量，如果没有，则添加
   				$user_id = session('user_id');//获取登录用户的id
   			    //判断购物车表里面是否有该商品，如果有，就只修改购买数量，如果没有呢就添加
	   			$info = self::where('user_id',$user_id)->where('uid',$k)->find();
	   			if($info){
	   				//说明该商品已经存在 ，就只修改购买数量
	   				//->setInc('字段名称',相加的值)//
	   				self::where('user_id',$user_id)->where('uid',$k)->setInc('goods_buy_number',$v['goods_buy_number']);
	   			}else {
	   				//说明该商品没有 ,执行添加操作
	   				self::create([
	   					'goods_id'=>$v['goods_id'],
	   					'user_id'=>$user_id,
	   					'goods_attrs'=>$v['goods_attrs'],
	   					'goods_buy_number'=>$v['goods_buy_number'],
	   					'uid'=>$k
	   				]);
	   			}
   			}
   		}
   		cookie('cart',null);

   }

   //清空购物车数据
   public function clearCart(){
     //取出登录用户的id,
      $user_id = session('user_id');
      self::where('user_id',$user_id)->delete();
   }
}
