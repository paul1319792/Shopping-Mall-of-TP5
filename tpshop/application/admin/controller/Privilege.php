<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Privilege as PrivilegeModel;
use think\Request;
use think\Validate;
class Privilege extends Base
{
    //权限列表
    public function index(){
    	$data = getCategoryTree(PrivilegeModel::select());
    	return view('index',compact('data'));
    }
    //添加权限
    public function add(Request $request){
    	if($request->isGet()){
    		//展示添加权限的表单
    		//取出上级权限
    		$data = getCategoryTree(PrivilegeModel::where('level','<',2)->select());
    		return view('add',compact('data'));
    	}else if($request->isPost()){
    		//完成数据入库
    		//接收返回的数据
    		$data = $request->post();
    		//定义数据验证
    		$rules = [
    			'priv_name'=>'require',
    			'parent_id'=>'require|number|egt:0',
    		];
    		//定义错误提示
    		$msg = [
    			'priv_name.require'=>'权限名称不能为空',
    			'parent_id.require'=>'父级权限不能为空',
    			'parent_id.number'=>'父级权限数据异常',
    			'parent_id.egt'=>'父级权限数据超出范围'
    		];
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//通过验证
    			$res  = PrivilegeModel::create($data,true);
    			if($res){
    				return ['info'=>1];
    			}else {
    				return ['info'=>0];
    			}
    		}else {
    			//未通过验证
    			$error = implode(',',$validate->getError());
    			return ['info'=>0,'error'=>$error];
    		}

    	}
    }

    //设置级别的
    public function setlevel(Request $request){
        //接收权限的id
        $id = $request->post('id');
        //获取权限等级
        $level =  PrivilegeModel::where('id',$id)->value('level');
        return ['info'=>$level+1];
    }
}
