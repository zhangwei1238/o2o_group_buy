<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
    	//echo THINK_VERSION;exit;
		return $this->fetch(); 
	}
	public function welcome(){
		return "欢迎来到o2o主后台首页";
	}
}
