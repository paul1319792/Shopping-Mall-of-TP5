<?php
use mailer\tp5\Mailer;
//定义发送邮件的函数；
//参数$to是收件人邮箱；
//参数$title是发送邮箱的标题
//参数$content是发送的内容
function send_email($to,$title,$content){
	$mailer = Mailer::instance();//创建一个对象；
	$mailer->to($to)
	    ->subject($title)
	    ->html($content)
	    ->send();
}



// 应用公共文件
function removeXss($val){
    static $obj=null;
    if($obj===null){
        require './static/admin/HTMLPurifier/HTMLPurifier.includes.php';
        $obj  = new HTMLPurifier();
    }
    return $obj->purify($val);
}

function getChildId($data,$id){
    static $list = [];
    //循环栏目数据
    foreach($data as $v){
        if($v['parent_id']==$id){
            
            $list[] = $v['id'];
            //自己调用自己 找当前栏目的下级栏目
            getChildId($data,$v['id']);
        }
    }
    //返回静态数组
    return  $list;
}
function p($as){
    echo '<pre>';
    print_r($as);
}
//定义一个函数，根据属性的id,取出属性的名称；
function getAttributeName($id){
    return \app\admin\model\Attribute::where('id',$id)->value('attr_name');
}
//定义一个函数，根据商品的id，获取详情信息；
function getGoodsInfo($id){
    return \app\admin\model\Goods::field('id,goods_name,shop_price,goods_thumb')
                                ->where('id',$id)
                                ->find()
                                ->toArray();
}