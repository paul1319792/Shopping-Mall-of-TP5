<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class Attribute extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //会自动填充表里面的create_time和update_time字段信息；
    protected $autoWriteTimestamp = true;
    //配置与商品类型模型的关系 一对一关系
    public function type(){
    	return $this->hasOne('app\admin\model\Type','id','type_id');
    }
}
