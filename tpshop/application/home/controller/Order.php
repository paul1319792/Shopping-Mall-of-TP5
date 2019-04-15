<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Order as OrderModel;
use think\Db;
use app\home\model\Goods;
class Order extends Controller
{
    //执行下订单代码
    public function done(Request $request)
    {
        if($request->isPost()){
            $ph = fopen('./1.txt','w');//打开文件
            flock($ph,LOCK_EX );//添加文件锁
            //接收表单传递的数据
            $address = $request->post('address');//收货人地址
            $order_pay = $request->post('order_pay');//支付方式
            $user_id = session('user_id');//获取登录用户的id值
            $order_sn = 'sn_'.uniqid();//生成订单编号
            $cart = new \app\home\model\Cart();
            $total = $cart->getCartTotal();
            $order_price = $total['totalPrice'];//获取订单总金额；
            //订单入库操作
            $order = OrderModel::create([
                'user_id'=>$user_id,
                'order_sn'=>$order_sn,
                'address'=>$address,
                'order_pay'=>$order_pay,
                'order_price'=>$order_price
            ]); //返回值是一个对象，是当前新记录的对象（模型类）
            //入库订单与商品的关联表
            //入库的数据，从购物车里面获取
            $cartData = $cart->cartInfo();//获取购物车里面的数据
            //p($cartData);
            foreach($cartData as $v){
                Db::name('order_goods')->insertGetId([
                    'order_id'=>$order->id,
                    'goods_id'=>$v['goods_id'],
                    'goods_price'=>$v['info']['shop_price'],
                    'goods_number'=>$v['goods_buy_number'],
                    'goods_price_sum'=>$v['info']['shop_price'] * $v['goods_buy_number'],
                    'goods_attrs'=>$v['goods_attrs'],
                    'create_time'=>time()
                ]);
                Goods::where('id',$v['goods_id'])->setDec('goods_number',$v['goods_buy_number']);
            }
            flock($fp,LOCK_UN);//释放锁
            //入库完成；清空购物车，跳转到页面；
            $cart->clearCart();//清空购物车
            //跳转到下单完成，开始支付的页面；
            //   return redirect()  <==>  $this->redirect();
            $this->redirect(url('home/order/pay',['id'=>$order->id]));
            //return view('pay',compact());
        }
    }

    //展示订单完成的页面,开始支付的页面
    public function pay(Request $request)
    {
        //接收传递的订单id;
        $id = $request->param('id');
        //取出订单信息；
        $info = OrderModel::field("order_sn,order_price")->find($id);
       // P($info);exit;
        return view('pay',compact('info'));
    }

    //支付宝提交页面；
    public function makepay(Request $request)
    {
        //require_once dirname(dirname(__FILE__)).'/config.php';
        require_once EXTEND_PATH.'alipay/config.php';
        //require_once dirname(__FILE__).'/service/AlipayTradeService.php';
        require_once EXTEND_PATH.'alipay/pagepay/service/AlipayTradeService.php';
        require_once EXTEND_PATH.'alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($_POST['WIDout_trade_no']);
        //订单名称，必填
        $subject = trim($_POST['WIDsubject']);
        //付款金额，必填
        $total_amount = trim($_POST['WIDtotal_amount']);
        //商品描述，可空
        $body = trim($_POST['WIDbody']);
        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $aop = new \AlipayTradeService($config);
        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        //输出表单
        var_dump($response);
    }

    //支付完成后的处理
    public function callback()
    {
        require_once EXTEND_PATH.'alipay/config.php';
        //require_once dirname(__FILE__).'/service/AlipayTradeService.php';
        require_once EXTEND_PATH.'alipay/pagepay/service/AlipayTradeService.php';
        $arr=$_GET;
        //p($_GET);exit;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        if($result) {//验证成功
            //商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            //付款金额
            $total_amount = $_GET['total_amount'];
            //修改订单状态；
            OrderModel::where('order_sn',$out_trade_no)->update([
                'order_pay_money'=>$total_amount,
                'order_status'=>'1',
                'trade_no'=>$trade_no
                ]);

            return view('callback',compact('total_amount'));
            //echo "验证成功<br />支付宝交易号：".$trade_no;
        }
        else {
            //验证失败
            echo "验证失败";
        }
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
