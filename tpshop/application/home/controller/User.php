<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Validate;
use app\home\model\User as UserModel;
use loveteemo\qqconnect\QC;
class User extends Controller
{
    
	//qq登陆功能
    public function qqlogin(){
    	//用于显示登录界面的
    	$qc = new QC();
        return redirect($qc->qq_login());
    }
    //qq登录后的处理（回调地址）
    public function qqcallback(){
    	$Qc = new QC();
        $access_token =  $Qc->qq_callback();
        $openid = $Qc->get_openid();//获取open_id;
        //echo $openid;
        $Qc = new QC($access_token,$openid);
        $qq_user_info = $Qc->get_user_info();//获取登录用户的信息；
        $username = $qq_user_info['nickname'];
        //第一步：判断是否第一次登录，如果是第一次登录则保存到数据库里面，
        //如果后续登录，我们可以做一个更新操作，
        $info =  UserModel::where('openid',$openid)->find();
        if($info){
        	//说明给用户已经存在了，可以做一个更新操作
        	UserModel::where('openid',$openid)->update(['username'=>$username]);
        	//$user_id = $info->id;
        }else {
        	//入库操作
        	$info = UserModel::create([
        		'username'=>$username,
        		'openid'=>$openid
        	]);//返回值是一个对象（模型的对象，是添加的新记录）
        
        }
        $user_id = $info->id;
        //把数据存储到session里面
        session('user_id',$user_id);
    	session('user_name',$username);
        //完成登录之后的操作，并且跳转到首页关闭当前的小窗口
        //把登录的用户信息存储到session里面；
        echo "<script>
            opener.window.location.href = '/';
            window.close();
            </script>
		";
		//用在  window.open 打开小窗口，用于控制后面的大窗口。
		//opener.window.location.href = '/' 让小窗口后面的大窗口跳转到首页；
    }
    //展示注册页面
    public function register(Request $request){
    	if($request->isGet()){
    		//展示注册页面
    		return view('register');
    	}else if($request->isPost()){
    		//完成注册
    		//思路：（1）接收数据 (2)数据验证 （3）入库 （4）发送激活邮件
    		$data = $request->post();
    		//定义验证的规则
    		//confirm该验证规则，是验证两个字段是否相同
    		$rules = [
    			'username'=>'require|unique:user,username',
    			'user_email'=>'require|email|unique:user,user_email',
    			'password'=>'require|length:6|confirm',
    			'password_confirm'=>'require|length:6'
    		];
    		//定义错误提示
    		$msg = [
    			'username.require'=>'用户名不能为空',
    			'username.unique'=>'用户名已经存在',
    			'user_email.require'=>'邮箱不能为空',
    			'user_email.email'=>'邮箱格式不正确',
    			'user_email.unique'=>'邮箱已经存在了',
    			'password.require'=>'密码不能为空',
    			'password.length'=>'密码长度必须是6位',
    			'password.confirm'=>'两次密码输入不一致',
    			'password_confirm.require'=>'确认密码不能为空',
    			'password_confirm.length'=>'确认密码长度必须是6位'
    		];
    		//开始验证
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//完成注册验证，
    			//通过验证，了，完成入库
    			$data['password'] = md5($data['password']);
    			$info = UserModel::create($data,true);//返回值是当前添加新记录的对象
    			if($info){
    				//注册成功，入库成功了，发送激活邮件；
    				//设计激活邮件内容，尊敬XXX用户，XXXX网感谢您的注册，请单击激活账号；
    				//拼接发送邮件需要的参数；
    				//制作一个校验码
    				$verify_code = uniqid();
    				//把校验码存储到表里面；
    				UserModel::where('id',$info->id)->update(['verify_code'=>$verify_code]);
    				//制作一个激活的链接
    				$url = "http://www.tpshop.com/home/user/active/id/".$info->id.'/verify_code/'.$verify_code;
    				$content = "尊敬的".$data['username']."用户<br/>品优购商城，感谢您的注册，请单击<a href='".$url."'>激活</a>账号。";
    				//发送激活邮件
    				send_email($data['user_email'],'品优购商城账号激活',$content);
    				//加载一个注册完毕的视图；
    				return  view('reg_success');
    			}else {
    				$this->error('注册失败，请重新注册');
    			}




    		}else {
    			//验证失败
    			$error = implode(',', $validate->getError());
    			$this->error($error);
    		}
    	}
    	
    }

    //完成用户的激活
    public function active(Request $request){
    	//接收传递的数据
    	$id = $request->param('id');
    	$verify_code = $request->param('verify_code');
    	//验证，根据id,查找出表里面的verify_code值,与接收的$verify_code进行比较；
    	//如果已经激活，就无需再激活
    	$info = UserModel::find($id);
    	if($info){
    		//用户正常
    		if($info->is_active=='是'){
    		//说明已经激活，就无需激活了
    			$msg = '已经激活了，无需再激活';
    			$url = '/';
	    	}else {
	    		//未激活，验证校验码是否正确
	    		if($info->verify_code==$verify_code){
	    			//激活成功，
	    			UserModel::where('id',$id)->update(['is_active'=>'是']);
	    			//激活成功，
	    			$msg = '激活成功，赶紧登陆';
    				$url = '/home/user/login';
	    		}else {
	    			//激活失败，链接有误
	    			$msg = '激活失败，链接有误';
    				$url = '/';
	    		}
	    	}
    	}else {
    		//用户不存在，链接有误；
    		$msg = '激活失败，链接有误';
    		$url = '/';
    	}
    	return  view('reg_active',compact('msg','url'));
    	
    }

    public  function login(Request $request){
    	if($request->isGet()){
    		//展示登录的表单
    		return view('login');
    	}else if($request->isPost()){
    		//echo '123';exit;
    		//完成登录
    		//思路：接收数据，取出数据，验证是否已经激活，取出密码，进行验证，
    		$data = $request->post();
    		//p($data);exit;
    		//数据验证
    		$rules = [
    			'username'=>'require',
    			'password'=>'require|length:6'
    		];
    		//错误提示
    		$msg = [
    			'username.require'=>'用户名不能为空',
    			'password.require'=>'密码不能为空',
    			'password.length'=>'密码长度必须是6位'
    		];
    		$validate = new Validate($rules,$msg);
    		if($validate->batch()->check($data)){
    			//echo '11';exit;
    			//通过验证.取出数据
    			$info = UserModel::where('username',$data['username'])->find();
    			//var_dump($info);exit();
    			if($info){
    				//该用户存在，
    				//判断该用户是否被激活
    				if($info->is_active=='否'){
    					//未激活
    					$this->error('用户没有激活，无法登陆');
    				}
    				//判断用户名和秘密是否正确了
    				if($info->password == md5($data['password'])){
    					//用户名和密码正确的；
    					//把用户名信息存储到session里面
    					session('user_id',$info->id);
    					session('user_name',$data['username']);
                        $url = '/';
                        if(session("?back_url")){
                            $url = session('back_url');
                            session('back_url',null);
                        }
                        //做cookie的购物车数据，移动到数据库里面；
                        $cart = new \app\home\model\Cart();
                        $cart->cookie2db();
    					$this->redirect($url);
    				}
    			}
    			$this->error('用户名或密码错误');
    		}else {
    			//未通通过验证
    			$error = implode(',',$validate->getError());
    			$this->error($error);
    		}
    	}
    }


}
