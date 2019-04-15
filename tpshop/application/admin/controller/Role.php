<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Role as RoleModel;
use think\Request;
use app\admin\model\Privilege;
use think\Validate;
class Role extends Base
{
    public function demo(){
    	$data = Privilege::where('id','in',[1,2,3,5,6,7])->where('level','neq','0')->column("concat(controller_name,'-',action_name) as priv_ac");
    	p($data);
    }
    //角色列表
    public function index(){
    	$data  = RoleModel::select();
    	return view('index',compact('data'));
    }
    //完成角色的修改
    public function update(Request $request,$id){
    	//展示出被修改的数据，
    	if($request->isGet()){
    		//取出顶级权限数据
    		$privA = Privilege::where('level','0')->select();
    		//取出1级权限数据
    		$privB = Privilege::where('level','1')->select();
    		//取出2级权限数据
    		$privC = Privilege::where('level','2')->select();
    		//取出当前被修改的角色
    		$info = RoleModel::find($id);
    		//取出原来的权限数据
    		$old_priv_ids = explode(',', $info->priv_ids);
    		return view('update',compact('privA','privB','privC','info','old_priv_ids'));
    	}else if($request->isPost()){
    		//接收提交的数据
    		$data = $request->post();
    		//数据验证
    		$rules = [
    			'role_name'=>'require|unique:role,role_name,'.$id,
    			'priv'=>'require|array'
    		];
    		$msg = [
    			'role_name.require'=>'角色名称不能为空',
    			'role_name.unique'=>'角色名称已经存在',
    			'priv.require'=>'权限数据不能为空',
    			'priv.array'=>'权限数据异常'
    		];

    		$validate  = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//通过验证
    			//直接修改
    			$priv  = implode(',',$data['priv']);
    			//查询权限数据
    			$priv_ac = Privilege::where('id','in',$data['priv'])
		    			->where('level','neq','0')
		    			->column("concat(controller_name,'-',action_name) as priv_ac");
		    	//把数组转换成 字符串；
    			$priv_ac = implode(',', $priv_ac);
    			RoleModel::where('id',$id)->update([
    				'role_name'=>$data['role_name'],
    				'priv_ids'=>$priv,
    				'priv_ac'=>$priv_ac
    			]);
    			return ['info'=>1];
    		}else {
    			//未通过验证
    			$error = implode(',', $validate->getError());
    			return ['info'=>0,'error'=>$error];
    		}
    	}
    }
}
