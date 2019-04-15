<?php

namespace app\home\model;

use think\Model;

class Order extends Model
{
    //会自动填充表里面的create_time和update_time字段信息；
    protected $autoWriteTimestamp = true;
}
