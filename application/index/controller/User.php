<?php
namespace app\index\controller;
use think\Controller;
class User extends Controller
{

    public function login()
    {
    	//获取session
    	$user = session('o2o_user','','o2o');
    	if($user && $user->id){
    		$this->redirect('index/index');
    	}
		return $this->fetch();
    }
	public function register()
    {
    	if(request()->isPost()){
    		$data = input('post.');
    		if(!captcha_check($data['verifycode'])){
    			$this->error('验证码错误');
    		}
    		//数据严格校验 validate
    		//包括username和email已存在的情况，也可以在下面的异常中处理
    		
    		if($data['password']!=$data['repassword']){
    			$this->error('两次输入的密码不一样');
    		}
    		//自动生成密码的加盐字符串
    		$data['code'] = mt_rand(100,10000);
    		$data['password'] = md5($data['password'].$data['code']);
    		try{
    			$res = model('User')->add($data);	
			}catch(\Exception $e){
				$this->error($e->getMessage());
			}
			//这里的判断不能放在try里面，因为success和error最终会抛出异常
			if($res){
    			$this->success('注册成功',url('user/login'));
    		}else{
    			$this->error('注册失败');
    		}
    	}else{
    		return $this->fetch();
    	}
		
    }

    public function logincheck(){
    	if(!request()->isPost()){
    		$this->error('提交不合法');
    	}
    	$data = input('post.');
    	//严格的数据校验
    	try{
    		$ret = model('User')->getUserByUsername($data['username']);
    	}catch(\Exception $e){
    		$this->error($e->getMessage());	
    	}

    	if(!$ret || $ret->status!=1){
    		$this->error('该用户不存在');
    	}
    	if (md5($data['password'].$ret->code)!=$ret->password) {
    		$this->error('用户名或密码错误');
    	}

    	$result = model('User')->updateById(['last_login_time'=>time()],$ret->id);
    	if(!$result){
    		$this->error('出现未知错误，请重新登录');
    	}

    	//保存用户信息
    	session('o2o_user',$ret,'o2o');
    	$this->success('登录成功',url('index/index'));

    }
    function logout(){
    	//清空session
    	session(null,'o2o');
    	$this->redirect('user/login');
    }
}
