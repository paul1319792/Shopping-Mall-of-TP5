<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Category as CategoryModel;
use think\Request;
use think\Validate;
class Category extends Base
{
    //栏目列表
    public function index(){
    	$data = getCategoryTree(CategoryModel::select());
    	return view('index',compact('data'));
    	//return view('ztree');
    }

    //添加栏目
    public function add(Request $request){
    	if($request->isGet()){
    		//显示添加栏目的表单页面
    		//取出栏目数据
    		$data = getCategoryTree(CategoryModel::select());
    		return view('add',compact('data'));
    	}else if($request->isPost()){
    		//完成栏目数据入库
    		//接收表单提交数据
    		$data = $request->post();
    		//定义验证规则
    		$rules = [
    			'cate_name'=>'require|unique:category,cate_name',
    			'parent_id'=>'require|number',
    			'cate_desc'=>'require|min:3'
    		];
    		//定义错误提示
    		$msg = [
    			'cate_name.require'=>'栏目名称不能为空',
    			'cate_name.unique'=>'栏目名称已经存在',
    			'parent_id.require'=>'父级栏目不能为空',
    			'parent_id.number'=>'父级栏目数据格式异常',
    			'cate_desc.require'=>'栏目描述不能为空',
    			'cate_desc.min'=>'栏目描述至少是3个字符'
    		];
    		//创建验证对象
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//验证通过,开始入库
    			$res = CategoryModel::create($data,true);
    			if($res){
    				return ['info'=>1];
    			}else {
    				return ['info'=>0,'error'=>'入库失败'];
    			}
    		}else {
    			//未通过验证
    			$error = implode(',',$validate->getError());
    			return ['info'=>0,'error'=>$error];
    		}
    	}
    }

    //完成栏目的修改
    public function update(Request $request,$id){
    	if($request->isGet()){
    		//显示出被修改的数据
    		//取出被修改的数据
    		$info =  CategoryModel::find($id);
    		//取出栏目数据
    		$data = getCategoryTree(CategoryModel::select());
    		return view('update',compact('info','data'));

    	}else if($request->isPost()){
    		//echo $id;
    		//echo $request->param('id');
    		$data = $request->post();
    		//定义验证规则
    		//unique:category,cate_name,'.$id,意思是在修改时，验证唯一性时，要排除掉自己
    		$rules = [
    			'cate_name'=>'require|unique:category,cate_name,'.$id,
    			'parent_id'=>'require|number',
    			'cate_desc'=>'require|min:3'
    		];
    		//定义错误提示
    		$msg = [
    			'cate_name.require'=>'栏目名称不能为空',
    			'cate_name.unique'=>'栏目名称已经存在',
    			'parent_id.require'=>'父级栏目不能为空',
    			'parent_id.number'=>'父级栏目数据格式异常',
    			'cate_desc.require'=>'栏目描述不能为空',
    			'cate_desc.min'=>'栏目描述至少是3个字符'
    		];
    		//创建验证对象
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//验证通过,开始入库
    			//还要判断父栏目是否是自己的子孙栏目，如果是，就不允许修改；
    			//查找出当前栏目的子孙栏目，

    			$ids = getChildId(CategoryModel::select(),$id);
    			//把当前栏目的id，添加到该数组里面
    			$ids[] = $id;
    			//获取提交的父id
    			//$parent_id = $request->post('parent_id');
    			if(in_array($data['parent_id'], $ids)){
    				return ['info'=>0,'error'=>'不能把自己的子孙栏目和自己当成父栏目'];
    			}
    			//CategoryModel::update(提交的数据,修改条件,为true表示过滤非数据表字段)
    			$res = CategoryModel::update($data,['id'=>$id],true);
    			if($res){
    				return ['info'=>1];
    			}else {
    				return ['info'=>0,'error'=>'修改失败'];
    			}
    		}else {
    			//未通过验证
    			$error = implode(',',$validate->getError());
    			return ['info'=>0,'error'=>$error];
    		}
    	}
    }
    //删除栏目数据的
    public function del(Request $request){
    	$id = $request->post('id');
    	//判断如果有子栏目就不能删除
    	$ids = getChildId(CategoryModel::select(),$id);
    	if($ids){
    		return ['info'=>0,'error'=>'先删除子栏目'];
    	}
    	$res = CategoryModel::destroy($id);
    	if($res){
    		return ['info'=>1];
    	}
    }

    //ztree数据
    public function getTreeCategory(){
    	$data = CategoryModel::select();
    	//循环数据，给id=1的记录，添加 open  =  true  显示时，展开数据;
    	foreach($data as $v){
    		if($v['id']==1){
    			$v['open']=true;
    		}
    	}
    	return $data;
    }
    //ztree方式删除栏目数据
    public function ztreedel(Request $request){
    	$id = $request->post('id');
    	$res = CategoryModel::destroy($id);
    	if($res){
    		return ['info'=>1];
    	}
    }
	//ztree方式修改栏目数据
    public function ztreeupdate(Request $request){
    	//接收传递的数据
    	$id = $request->post('id');
    	$cate_name = $request->post('cate_name');
    	$parent_id = $request->post('parent_id');
    	//判断当前操作时新增还是修改
    	if($id==0){
    		//新增操作
    		$info = CategoryModel::create(['cate_name'=>$cate_name,'parent_id'=>$parent_id]);
    		if($info){
    			return ['info'=>1,'id'=>$info->id];
    		}
    	}else{
    		//修改操作
    		$res = CategoryModel::where('id',$id)->update(['cate_name'=>$cate_name]);
	    	if($res){
	    		return ['info'=>1,'id'=>$id];
	    	}
    	}
    	
    }
}
