<?php  
namespace app\index\controller;
use think\Controller;
/**
* 								
*/
class Lists extends Base
{
	
	public function index()
	{
		//思路 首先获取一级分类
		$categorys = model('Category')->getNormalCategory();

		$firstCateIds = [];
		foreach ($categorys as $category) {
			$firstCateIds[] = $category->id;
		}
		$id = input('get.id',0,'intval');
		$data = [];
		//判断id是 无 一级 二级
		if (in_array($id, $firstCateIds)) { //一级
			$categoryParentId = $id;
			$data['category_id'] = $id;
		}elseif($id){   //二级
			//获取二级分类的数据
			$category = model('Category')->get($id);
			if(!$category || $category->status!=1){
				$this->error('ID不合法');
			}
			$categoryParentId = $category->parent_id; 
			$data['se_category_id'] = $id;
		}else{  // 0 
			$categoryParentId = 0;
		}
		$sedCategorys = [];
		//获取父类下的所有子分类
		if($categoryParentId){
			$sedCategorys = model('Category')->getNormalCategory($categoryParentId);
		}
		$orders = [];
		//数据排序获取的逻辑
		$order_sales = input('order_sales','');
		$order_price = input('order_price','');
		$order_time = input('order_time','');
		if(!empty($order_sales)){
			$orderflag = 'order_sales';
			$orders['order_sales'] = $order_sales;
		}elseif(!empty($order_price)){
			$orderflag = 'order_price';
			$orders['order_price'] = $order_price;
		}elseif (!empty($order_time)) {
			$orderflag = 'order_time';
			$orders['order_time'] = $order_time;
		}else{
			$orderflag = '';
		}
		$data['city_id'] = $this->city->id;
		//根据上面查询商品列表数据
		print_r($orders);
		$deals = model('Deal')->getDealByConditions($data,$orders);

		return $this->fetch('',[
			'categorys' => $categorys,
			'sedCategorys' => $sedCategorys,
			'id' => $id,
			'categoryParentId' => $categoryParentId,
			'orderflag' => $orderflag,
			'deals' => $deals,
		]);
	}
}
