<?php
namespace app\common\model;

use think\Model;

class Deal extends BaseModel
{
	 
	public function getNormalDeals($data=[]){
		$data['status'] = 1;
		$order = ['id'=>'desc'];
		$result = $this->where($data)
			->order($order)
			->paginate();

		//echo $this->getLastSql();
		return $result;			
	}

	public function getNormalDealByCategoryCityId($catId,$cityId,$limit=10){	
		$data = [
			'end_time' => ['gt',time()],
			'category_id' => $catId,
			'city_id' => $cityId,
			'status' => 1,
		];
		$order = [
			'listorder' => "desc",
			'id' => "desc",
		];
		$result = $this->where($data)->order($order);
		if($limit){
			$result = $result->limit($limit);
		}
		$result = $result->select();
		return $result;
   	}  

   	public function getDealByConditions($data,$orders)
	{
		 
		$order = [];
		if (!empty($orders['order_sales'])) {
			$order['buy_count'] = 'desc';
		}
		if (!empty($orders['order_price'])) {
			$order['current_price'] = 'desc';
		}
		if (!empty($orders['order_time'])) {
			$order['create_time'] = 'desc';
		}
		$order['id'] = 'desc';
		//find_in_set(11,se_category_id);	
		//这一点比较重要 //如果一件商品属于三个子分类的情况,可以用这个拼接成ssql查询	
	 
		$datas[] = "end_time>".time();
		//可以使用linux crontab 定时任务或window 定时去扫描商品表，发现时间超过的就把status设为2
		$datas[] = "status=1";
		if(!empty($data['category_id'])){
			$datas[] = "category_id=".$data['category_id'];
		}

		if(!empty($data['se_category_id'])){
			$datas[] = "find_in_set(".$data['se_category_id'].",se_category_id)";
		}
		if(!empty($data['city_id'])){
			$datas[] = "city_id=".$data['city_id'];
		}
 
		$result = $this->where(implode(' AND ',$datas))
					->order($order)
					->paginate();
		echo $this->getLastSql(); 
		return $result;
	} 
	public function updateDealById($id,$buyCount)	
	{
		//更新购买量
		return $this->where(['id'=>$id])->setInc('buy_count',$buyCount);
	}
}