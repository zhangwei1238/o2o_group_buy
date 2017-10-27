<?php
namespace app\index\controller;
use think\Controller;
/**
* 					
*/
class Detail extends Base
{
	//动态加载css和更改title 在base 传递controller里；
	//优化 可以再具体的方法中 再次传递 controller的值，覆盖前面的不需要的
	
	public function index($id)
	{
		if(!intval($id)){
			$this->error('ID不合法');
		}
		//根据id查询商品数据
		$deal = model('Deal')->get($id);
		$bis = model('Bis')->get($deal->bis_id);
		if(!$deal || $deal->status != 1){
			$this->error('该商品不存在');
		}
		//获取分类信息
		$category = model('Category')->get($deal->category_id);
		//获取分店信息
		$locations = model('BisLocation')->getNormalLocationId($deal->location_ids);
		// print_r($locations);exit;
		$flag = 0;
		if($deal->start_time > time()){
			$flag = 1 ;
			$dtime = $deal->start_time-time();
			$timedate = '';
			$d = floor($dtime/(3600*24)); //floor向下取整
			if($d){
				$timedate .= $d.'天';
			}
			$h = floor($dtime%(3600*24)/3600);
			if($h){
				$timedate .= $h.'小时';
			}
			$m = floor($dtime%(3600*24)%3600/60);
			if($m){
				$timedate .= $m.'分';
			}
			$this->assign('timedate',$timedate);
		}
		return $this->fetch('',[
			'title' => $deal->name,
			'category' => $category,
			'locations' => $locations,
			'deal' => $deal,
			'overplus' => $deal->total_count-$deal->buy_count,
			'flag' => $flag,
			'mapstr' => $locations[0]['xpoint'].','.$locations[0]['ypoint'],
			'bis' => $bis,
		]);
	}
}