<?php
namespace app\bis\controller;
use think\Controller;
class Login extends Controller
{
	public function index()
	{
		if(request()->isPost()){
			//登录的逻辑
			//获取相关的数据
			$data = input('post.');
			//通过用户名 获取 用户相关的信息
			//判定用户名和密码信息的格式
			$validate = validate('BisAccount');
			if(!$validate->scene('add')->check($data)){
				$this->error($validate->getError());
			}
			$ret = model('BisAccount')->get(['username'=>$data['username']]);
			if(!$ret || $ret->status != 1){
				$this->error('该用户不存在或者用户未被审核通过');
			}
			if(md5($data['password'].$ret->code)!= $ret->password){
				$this->error('密码不正确');		
			}
			
			model('BisAccount')->updateById(['last_login_time'=>time()],$ret->id);
			
			//保存用户信息 ,bis是作用域
			session('bisAccount',$ret,'bis');
			return $this->success('登录成功',url('index/index'));
		}else{
			$account = session('bisAccount','','bis');
			//$account->id 可写可不写
			if($account && $account->id){
				return $this->redirect(url('index/index'));
			}
			return $this->fetch();
		}
		
	}
	
	//退出登录
	public function logout(){
		//清除bis作用于下的session
		session(null,'bis');
		//跳出
		$this->redirect(url('login/index'));
		
	}
	
}