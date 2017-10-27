<?php
namespace app\common\model;

use think\Model;
/*BaseModel   公用的model层 */
class BaseModel extends Model
{
	protected $autoWriteTimestamp = true;
	/* 添加分类操作 */
	public function add($data){
		$data['status'] = 0;
		$this->save($data);
		//返回主键id
		return $this->id;
	}
	public function updateById($data,$id){
		return $this->allowField(true)->save($data,['id'=>$id]);
	}
	
}