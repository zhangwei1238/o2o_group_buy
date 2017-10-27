<?php
namespace app\bis\controller;
use think\Controller;
class Deal extends Base
{
	
	public function index(){
		$bisId = $this->getLoginUser()->bis_id;
		$bisList = model('Deal')->where(['bis_id'=>$bisId])->paginate();
		return $this->fetch('',[
			'bisList' => $bisList
		]);
	}
	
	public function add(){
		$bisId = $this->getLoginUser()->bis_id;
		if(request()->isPost()){
			//走插入逻辑
			$data = input('post.');
			//严格校验提交的数据tp5 validate
			
			$location = model('BisLocation')->get($data['location_ids'][0]);
			$deals = [
				'bis_id' => $bisId,
				'name' => $data['name'],
				'image' => $data['image'],
				'category_id' => $data['category_id'],
				'se_category_id' =>empty($data['se_category_id'])?'':implode(',',$data['se_category_id']),
				'city_id' => $data['city_id'],
				'location_ids' => empty($data['location_ids'])?'':implode(',',$data['location_ids']),
				'start_time' => strtotime($data['start_time']),
				'end_time' => strtotime($data['end_time']),
				'total_count' => $data['total_count'],
				'origin_price' => $data['origin_price'],
				'current_price' => $data['current_price'],
				'coupons_start_time' => strtotime($data['coupons_start_time']),
				'coupons_end_time' => strtotime($data['coupons_end_time']),
				'notes' => $data['notes'],
				'description' => $data['description'],
				'bis_account_id' => $this->getLoginUser()->id,
				'xpoint' => $location->xpoint,
				'ypoint' => $location->ypoint,
	
			];
			//print_r($deals);
			$id = model('Deal')->add($deals);
			if($id){
				$this->success('添加成功',url('deal/index'));
			}else{
				$this->error('添加失败');
			}
		}else{
			//获取一级城市的数据
			$citys = model('City')->getFirstCategory();
			//获取一级分类的数据
			$categorys = model('Category')->getNormalCategory();
			return $this->fetch('',[
				'citys' => $citys,
				'categorys' => $categorys,	
				'bislocations' => model('BisLocation')->getNormalLocationByBisId($bisId),
			]);
		}
	}

	public function detail(){
		$id = input('get.id');
		$bisId = $this->getLoginUser()->bis_id;
		if(empty($id)){
			$this->error('ID错误');	
		}
		$citys = model('City')->getNormalCategory(['egt',0]);
		$categorys = model('Category')->getNormalCategory();
		$bislocations = model('BisLocation')->getNormalLocationByBisId($bisId);
		$deal = model('Deal')->get($id);
		$secat = model('Category')->get($deal->se_category_id);
		$cityArrs = [];
		foreach ($citys as $city) {
			$cityArrs[$city->id] = $city->name;
		}
		// var_dump($cityArrs);exit;
		if(!empty($deal)){
			return $this->fetch('',[
				'deal' => $deal,
				'citys' => $citys,
				'categorys' => $categorys,
				'bislocations' => $bislocations,
				'secat' => $secat,
				'cityArrs' => $cityArrs
			]);
		}else{
			$this->error('ID错误');
		}
	}
}