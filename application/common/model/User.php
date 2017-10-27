<?php
namespace app\common\model;

use think\Model;

class User extends BaseModel
{
	public function add($data=[]){
		//如果提交的数据不是数组
		if(!is_array($data)){
			exception('提交的数据不是数组');
		}
		$data['status'] = 1;
		return $this->allowField(true)->save($data);
	}

 	public function getUserByUsername($username){
 		if(!$username){
 			exception('用户名不合法');
 		}

 		$data = ['username'=>$username];
 		return $this->where($data)->find();
 	}	
}