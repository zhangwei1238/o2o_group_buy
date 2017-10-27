<?php
namespace app\common\model;

use think\Model;

class Bis extends BaseModel
{
	/* 通过状态获取商家数据 */
	public function getBisByStatus($status=0){
		$data = [
			'status' => $status,
		];
		$order = [
			'id' => 'desc',
		];
		$result = $this->where($data)
					->order($order)
					->paginate();
		return $result;
	}
	
}