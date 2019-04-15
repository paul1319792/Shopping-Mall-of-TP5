<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Validate;
use app\admin\model\Manager;
class Login extends Controller
{
    //完成显示登录页面，完成登录验证
    public function login(Request $request){
    	if($request->isGet()){
    		return view('login');
    	}else if($request->isPost()){
    		//完成登录验证
    		//接收传递的数据
    		$data = $request->post();
    		//数据验证
    		//定义验证规则，以及错误提示
    		$rules = [
    			'name'=>'require',
    			'password'=>'require|length:6'
    		];
    		$msg = [
    			'name.require'=>'管理员名称不能为空',
    			'password.require'=>'密码不能为空',
    			'password.length'=>'密码长度必须为6位'
    		];
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//登录验证，通过过，验证管理员名称与密码是否正确，
    			//如果正确把用户信息存储到session里面
    			//根据用户名取出密码与输入密码进行匹配
    			$info = Manager::field('id,name,password,role_id')->where('name',$data['name'])->find();
    			if($info){
    				//对象->toArray();可以把返回的对象数据，转换成数组
	    			if(password_verify($data['password'],$info->password)){
	    				//登录成功，管理员信息存储到session里面
	    				session('info',$info->toArray());
	    				//跳转到后台首页
	    				$this->success('登录成功','admin/index/index');
	    			}
    			}
    			$this->error('用户名或密码错误');
    		}else {
    			//验证未通过
    			$error = implode(',', $validate->getError());
    			$this->error($error);
    		}
    	}
    }
    //退出登录
    public function logout(){
    	session('info',null);
    	return $this->redirect('admin/login/login');
    }
}
