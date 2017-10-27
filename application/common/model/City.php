<?php
namespace app\common\model;

use think\Model;

class City extends Model
{
	protected $autoWriteTimestamp = true;
	public function add($data){
		$data['status'] = 1;
		$data['uname'] = wordsToPin($data['name']);
		return $this->save($data);
	}

	/* 获取待审和正常一级分类 */
	public function getFirstCategory($parentId=0){
		$data = [
			'parent_id' => $parentId,
			'status' => ['neq',-1],
		];
		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];
		$res = $this->where($data)->order($order)->paginate();
		//echo $this->getLastsql();
		return $res;
	}
	/* 获得正常一级或二级分类 */
	public function getNormalCategory($id=0){
		$data = [
			'status' => 1,
			'parent_id' => $id,
		];
		$order = [
			'id' => 'desc',
		];
		$res = $this->where($data)->order($order)->select();
		return $res;
	}
	public function getNormalCitys(){
		$data = [
			'parent_id' => ['neq',0],
			'status' => 1,
		];
		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];
		$res = $this->where($data)->order($order)->select();
		//echo $this->getLastsql();
		return $res;
	}
}