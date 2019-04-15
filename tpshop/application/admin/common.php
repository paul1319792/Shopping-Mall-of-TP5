<?php
//定义一个函数，用于获取商品类型信息；
use app\admin\model\Type;
function get_type_info(){
	return Type::select();
}
//定义一个函数，用于层级方式显示栏目数据
//参数1，栏目数据 ，参数2 父栏目的id;
function getCategoryTree($data,$parent_id=0,$lev=0){
	//定义一个静态的数组 
	static $list = [];
	//循环栏目数据
	foreach($data as $v){
		if($v['parent_id']==$parent_id){
			$v['lev']=$lev;
			$list[] = $v;
			//自己调用自己 找当前栏目的下级栏目
			getCategoryTree($data,$v['id'],$lev+1);
		}
	}
	//返回静态数组
	return  $list;
}
//查找出当前栏目的子孙栏目id



/*function p($as){
	echo '<pre>';
	print_r($as);
}
*/