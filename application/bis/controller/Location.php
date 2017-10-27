<?php
namespace app\bis\controller;
use think\Controller;
class Location extends Base
{
	//门店列表页
	public function index(){
		$bis_id = $this->getLoginUser()->bis_id;
		$location = model('BisLocation')->getLocationByBisId($bis_id);
		return $this->fetch('',[
			'location' => $location ,
		]);
	}
	
	public function add(){
		if(request()->isPost()){
			//添加门店信息
			//总店信息校验
			$data = input('post.');

			$validate = validate('BisLocation');
			if(!$validate->scene('add')->check($data)){
				$this->error($validate->getError());
			}
			$data['cat']='';
			if(!empty($data['se_category_id'])){
				$data['cat'] = implode('|',$data['se_category_id']);
			}

			$bisId = $this->getLoginUser()->bis_id;
			//获取经纬度
			$lnglat = \Map::getLngLat($data['address']);
			if(empty($lnglat)||$lnglat['status']!=0||$lnglat['result']['precise']!=1){
				model('Bis')->destroy($bisId);
				$this->error('无法获取数据,或匹配的地址不精确');
			}
			
			$bisLocationData = [
				'bis_id' => $bisId,
				'name' => $data['name'],
				'logo' => $data['logo'],
				'tel' => $data['tel'],
				'contact' => $data['contact'],
				'category_id' => $data['category_id'],
				'category_path' => $data['category_id'].','.$data['cat'],
				'city_id' => $data['city_id'],
				'city_path' => empty($data['city_id'])?'':$data['city_id'].','.(empty($data['se_city_id'])?'':$data['se_city_id']),			
				'address' => $data['address'],
				'open_time' => $data['open_time'],
				'content' => empty($data['content'])?'':$data['content'],
				'is_main' => 0,           //代表分店相关信息
				'xpoint' => empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
				'ypoint' => empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],

			];
			$locationId = model('BisLocation')->add($bisLocationData);
			if($locationId){
				$this->success('分店添加成功');
			}else{
				$this->error('分店添加失败');
			}
		}else{
			$citys = model('City')->getNormalCategory();
			$categorys = model('Category')->getNormalCategory();
			return $this->fetch('',[
				'citys' => $citys,
				'categorys' => $categorys,
			]);
		}
	}
	
	public function detail(){
		$id = input('get.id');
		if(empty($id)){
			return $this->error('ID错误');
		}
		$location = model('BisLocation')->get($id);
		$citys = model('City')->getNormalCategory();
		$categorys = model('Category')->getNormalCategory();
		return $this->fetch('',[
			'citys' => $citys,
			'categorys' => $categorys,
			'location' => $location,
		]);
	}
	
	public function status(){
		$data = input('get.');
		$res = model('BisLocation')->save(['status'=>$data['status']],['id'=>$data['id']]);
		if($res){
			$this->success('状态更新成功');
		}else{
			$this->error('状态更新失败');
		}
	}
}