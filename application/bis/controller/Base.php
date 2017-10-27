<?php
namespace app\bis\controller;
use think\Controller;
class Base extends Controller
{
	public $account;
	public function _initialize(){
		//判断用户是否登录
		$isLogin = $this->isLogin();
		if(!$isLogin){
			return $this->redirect(url('login/index'));
		}
	}
	
	//判断是否登录
	public function isLogin(){
		//获取session值
		$user = $this->getLoginUser();
		if($user && $user->id){
			return true;
		}else{
			return false;
		}
	}
	
	public function getLoginUser(){
		if(!$this->account){
			$this->account = session('bisAccount','','bis');
		}
		return $this->account;
	}
	
}