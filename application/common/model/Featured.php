<?php
namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{
	//状态1或0的数据
 	public function getFeaturedsByType($type)
 	{
 		$data = [
 			'status' => ['neq',-1],
 			'type' => $type,
 		];
 		$result = $this->where($data)
 			->order(['id'=>'desc'])
 			->paginate();
 		return $result;
 	}
 	//状态为1的数据
 	public function getNormalFeaturedsByType($type)
 	{
 		$data = [
 			'status' => 1,
 			'type' => $type,
 		];
 		$result = $this->where($data)
 			->order(['id'=>'desc'])
 			->paginate();
 		return $result;
 	}
	
}