<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;
class Category extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //会自动填充表里面的create_time和update_time字段信息；
    protected $autoWriteTimestamp = true;
}
