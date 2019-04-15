<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
class Base extends Controller
{
    //添加构造函数
    public function __construct(Request $request){
    	//执行父类的构造函数
    	parent::__construct($request);
    	//（1）验证是否登录的代码
    	$info = session('info');
    	$id = $info['id'];
    	if(empty($id)){
    		//未登录
    		$this->error('必须登录','/admin/login/login');
    	}
    	//(2)验证是否越权操作的代码；
    	if($id==1){
    		//超级管理员
    		return true;
    	}
    	//验证普通管理员权限；
    	//验证思路：取出当前操作的控制器名称和方法名称；
    	//根据管理员的id->role_id->  priv_ac(Type-index,Category-index)
    	//取出当前的操作；
    	$curr_priv = request()->controller().'-'.request()->action();
    	//取出当前管理员拥有的权限；
    	//取出当前管理员的角色数据
    	$role_id = $info['role_id'];
    	//取出当前角色的priv_ac字段；
    	$roledata = Db::name('role')->find($role_id);//是一维数组；
    	$has_priv = $roledata['priv_ac'];//取出当前角色的priv_ac字段；
    	if(request()->controller()!='Index'){
    		if(strpos($has_priv,$curr_priv)===false){
	    		//说明没有查找到，无权访问
	    		exit('你无权访问');
    		}
    	}
    }
}
