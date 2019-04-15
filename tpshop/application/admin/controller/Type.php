<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\Type as TypeModel;

use think\Validate;
class Type extends Base{
    //商品类型列表
    public function index()
    {
        $data = TypeModel::select();
        return view('index',compact('data'));
    }

    //添加商品类型
    public function add(Request $request){
        if($request->isGet()){
            //展示表单页面
            return view('add');
        }else if($request->isPost()){
            //表单入库
            //接收表单提交数据
            $data = $request->post();
            //定义验证规则
            //unique:表名,字段名称 唯一性验证，查找表
            $rules = [
                'type_name'=>'require|unique:type,type_name',
                'type_desc'=>'require|min:3'
            ];
            //定义错误提示
            $msg = [
                'type_name.require'=>'类型名称不能为空',
                'type_name.unique'=>'类型名称已经存在',
                'type_desc.require'=>'类型描述不能为空',
                'type_desc.min'=>'类型描述至少是3个字符'
            ];
            //创建验证类的对象
            $validate = new Validate($rules,$msg);
            if($validate->batch()->check($data)){
                //入库
                $res = TypeModel::create($data,true);
                if($res){
                    return ['info'=>1];
                }else {
                    return ['info'=>0,'error'=>'入库失败'];
                }
            }else {
                //没有通过验证规则，返回错误提示
                $error = implode(',', $validate->getError());
                return ['info'=>0,'error'=>$error];
            }

        }
    }

    
}
