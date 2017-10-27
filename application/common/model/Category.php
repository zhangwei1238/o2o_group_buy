<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
	protected $autoWriteTimestamp = true;
	/* 添加分类操作 */
	public function add($data){
		$data['status'] = 1;
		//$data['create_time'] = time();
		return $this->save($data);
	}
	/* 查询status=1即正常的一级栏目*/
	public function getNormalCategory($id=0){
		$data = [
			'status' => 1,
			'parent_id' => $id,
		];
		$order = [
			'id' => 'desc',
		];
		$result = $this->where($data)
			->order($order)
			->select();
		return $result;
	}
	/* 查询status=0或1的一级栏目 */
	public function getFirstCategory($parentId){
		$data = [
			'parent_id' => $parentId,
			'status' => ['neq',-1],
		];
		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];
		$result = $this->where($data)
			->order($order)
			->paginate();
		//echo $this->getLastsql();
		return $result;
		
	}

	//得到首页分类5条
	public function getNormalRecommendCategoryByParentId($parentId=0,$limit=5)	
	{
		$data = [
			'parent_id' => $parentId,
			'status' => 1
		];
		$order = [
			'listorder' => 'desc',
			'id' => 'desc'
		];
		//理解这种操作
		$result = model('Category')->where($data)->order($order);
		if($limit){
			$result = $result->limit($limit);
		}
		return $result->select();

	}
	public function getNormalCategoryIdByParentId($ids)	
	{
		$data = [
			'parent_id' => ['in',implode(',',$ids)],
			'status' => 1
		];
		$order = [
			'listorder' => 'desc',
			'id' => 'desc'
		];
		 
		$result = model('Category')->where($data)->order($order)->select();
		//echo $this->getLastSql();
		return $result;
	}
	
}