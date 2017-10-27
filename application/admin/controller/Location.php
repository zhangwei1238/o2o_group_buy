<?php
namespace app\admin\controller;
use think\Controller;
class Location extends Controller
{
	private $obj;
	public function _initialize(){
		$this->obj = model('BisLocation');
	}
	
	/* 门店列表 */
	public function index(){
		$bisList = $this->obj->getLocationByStatus();
		return $this->fetch('',[
			'bisList' => $bisList,
		]);
		
	}
	
	/* 门店申请列表 */
    public function apply(){  
		$bisList = $this->obj->getLocationByStatus(0);
		return $this->fetch('',[
			'bisList' => $bisList,
		]);
	}
	

	
	/* 门店详细信息*/
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
	/* 修改门店状态 */
	/*public function status(){

		$data = input('get.');
		$validate = validate('Bis');
		if(!$validate->scene('status')->check($data)){
			$this->error($validate->getError());
		} 
		$res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
		
		if($res){
			
			$this->success('状态更新成功');
		}else{
			$this->error('状态更新失败');
			//失败时 需要把三条status恢复为原来的状态，此处暂时不做处理
		}
	}*/
	
	
	

}