<?php
namespace app\common\model;

use think\Model;

class BisAccount extends BaseModel
{
	//更新账户登录的最新时间
	public function updateById($data,$id){
		//allowField 过滤data数据中非数据表的数据
	    return $this->allowField(true)->save($data,['id'=>$id]);	
	} 
	
}