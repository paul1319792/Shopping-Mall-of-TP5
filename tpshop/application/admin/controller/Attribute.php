<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Attribute as AttributeModel;

use think\Validate;
class Attribute extends Base
{
    //属性列表
    public function index(Request $request){
    	//接收传递过来的类型id
    	$type_id = $request->param('type_id');
    	$data = AttributeModel::with('type')->where('type_id',$type_id)->select();
    	return view('index',compact('data'));
    }
    //添加属性
    public function add(Request $request){
    	if($request->isGet()){
    		//加载添加属性的页面
    		return view('add');
    	}else if($request->isPost()){
    		//接收表单提交的数据
    		$data = $request->post();
    		//定义验证规则
    		$rules = [
    			'attr_name'=>'require',
    			'type_id'=>'require|number|egt:1',
    			'attr_type'=>'require|in:0,1',
    			'attr_input_type'=>'require|in:0,1'
    		];
    		//定义错误提示
    		$msg = [
    			'attr_name.require'=>'属性名称不能为空',
    			'type_id.require'=>'商品类型不能为空',
    			'type_id.number'=>'商品类型数据异常',
    			'attr_type.require'=>'属性类型不能为空',
    			'attr_type.in'=>'属性类型不合法',
    			'attr_input_type.require'=>'录入方式不能为空',
    			'attr_input_type.in'=>'录入方式数据不合法'
    		];
    		$validate  = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//通过验证
                $data['attr_value'] = str_replace('，',',',$data['attr_value']);
    			$res = AttributeModel::create($data,true);
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
    //onchange事件触发的函数；
    public function getAttr(Request $request){
    	//接收传递的数据
    	$type_id = $request->post('type_id');
    	//根据类型获取属性数据
    	$data = AttributeModel::where('type_id',$type_id)->select();
    	return view('getAttr',compact('data'));
    }

    //返回商品类型的属性信息，并生成表单
    public function getAttributeByType(Request $request){
        //接收传递的数据
        $type_id = $request->param('type_id');
        //根据类型获取属性数据
        $data = AttributeModel::where('type_id',$type_id)->select();
        return view('getAttributeByType',compact('data'));
    }
}
