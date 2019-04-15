<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Manager;
use app\admin\model\Privilege;
use think\Db;
class Index extends Base
{
    //加载首页
    public function demo(){
        send_email('1973001898@qq.com','关于太阳系主任的选举',"<a href='http://www.baidu.com'>每人明天携带选举费100元</a>");
        //$info = Db::name('role')->find(2);
        //p($info);
        //echo request()->action();
        /*$a = "a，b，c";
        echo $a;
        echo '<hr>';
        echo  str_replace('，', ',', $a);*/
    }

    public function index()
    {
        //取出登录管理员的权限
    	//获取管理员的id
    	$info = session('info');
    	//如果是超级管理员，则直接从权限表里面取即可；
    	$admin_id  = $info['id'];//获取管理员的id;
    	if($admin_id==1){
    		//超级管理员
    		//取出等级权限
			$privA = Privilege::where('level','0')->select();
			//取出1级权限
			$privB = Privilege::where('level','1')->select();
    	}else {
    		//普通管理员
    		$priv_ids = Manager::alias('m')
			    		->join('__ROLE__ r','m.role_id=r.id')
			    		->where('m.id',$admin_id)
			    		->value('priv_ids');
			//var_dump($priv_ids);
			$priv_ids = explode(',',$priv_ids);
			//根据权限的id,获取权限数据
			//取出等级权限
			$privA = Privilege::where('id','in',$priv_ids)->where('level','0')->select();
			//取出1级权限
			$privB = Privilege::where('id','in',$priv_ids)->where('level','1')->select();
			
    	}
        //视图完成遍历
        return view('index',compact('privA','privB'));//加载的页面是admin/view/index/index.html
    }
    //加载welcome页面
    public function welcome(){
    	return view('welcome');
    }
}
