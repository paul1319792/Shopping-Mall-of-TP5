<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class Goods extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //会自动填充表里面的create_time和update_time字段信息；
    protected $autoWriteTimestamp = true;
    //配置与栏目模型的关系，一件商品属于一个栏目的，所以是一对一的；
    public  function category(){
    	return $this->hasOne('app\admin\model\Category','id','cat_id');
    }
}
