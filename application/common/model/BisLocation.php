<?php
namespace app\common\model;

use think\Model;

class BisLocation extends BaseModel
{
	//商家后台门店列表
	public function getLocationByBisId($bis_id){
		$data = [
			'bis_id' => $bis_id,
			'status' => ['<',2],   //正常店和待审店
			'status' => ['>',-1],
			 
		];
		$order = [
			'id' => 'asc',
		];
		$result = $this->where($data)
				->order($order)
				->paginate();
		return $result;
	}

	//根据状态不同获取门店
	public function getLocationByStatus($status=1){
		$data = [
			'status' => $status,   //正常店1，待审0，下架-1
			 
		];
		$order = [
			'id' => 'asc',
		];
		$result = $this->where($data)
				->order($order)
				->paginate();
		return $result;
	}
	public function getNormalLocationByBisId($bisId){
		$data = [
			'bis_id' => $bisId,
			'status' => 1,
		];
		$result = $this->where($data)
			->order('id','desc')
			->select();
		return 	$result;
	}
	public function getNormalLocationId($ids)
	{
		//$ids 是字符串不是数组
		$data = [
			'status' => 1,
			'id' => ['in',$ids],
		];
		$result = $this->where($data)->select();
		return $result;
	}
}