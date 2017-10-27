<?php
namespace app\common\model;

use think\Model;

class Coupons extends Model
{
	protected $autoWriteTimestamp = true;
	public function add($data){
		$data['status'] = 0;
		 
		return $this->save($data);
	}
}