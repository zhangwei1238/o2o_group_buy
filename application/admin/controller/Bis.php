<?php
namespace app\admin\controller;
use think\Controller;
class Bis extends Base
{
	private $obj;
	public function _initialize(){
		$this->obj = model('Bis');
	}
	
	/* 商家列表 */
	public function index(){
		$bisList = $this->obj->getBisByStatus(1);
		return $this->fetch('',[
			'bisList' => $bisList,
		]);
		
	}
	
	/* 入驻申请列表 */
    public function apply(){  
		$bisList = $this->obj->getBisByStatus();
		return $this->fetch('',[
			'bisList' => $bisList,
		]);
	}
	
	/* 删除的商家 */
	public function delList(){
		$bisList = $this->obj->getBisByStatus(-1);
		return $this->fetch('',[
			'bisList' => $bisList,
		]);
	}
	
	/* 商家详细信息*/
    public function detail(){   
		$id = input('get.id');
		if(empty($id)){
			return $this->error('ID错误');
		}
		//获取一级城市信息
		$firstCitys = model('City')->getNormalCategory();
		//获取一级分类信息
		$firstCates = model('Category')->getNormalCategory();
		//获取商户数据(不建议关联三张表查询，当数据量比较大时，影响性能)，直接查询三张表
		$bis = model('Bis')->get($id);
		$bisAccount = model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);
		$bisLocation = model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);
		$detail = $this->obj->get($id);
		return $this->fetch('',[
			'detail' => $detail,
			'firstCitys' => $firstCitys,
			'firstCates' => $firstCates,
			'bis' => $bis,
			'bisAccount' => $bisAccount,
			'bisLocation' => $bisLocation,
		]);
	}
	/* 修改商家状态 */
	public function status(){
		$data = input('get.');
		/* $validate = validate('Bis');
		if(!$validate->scene('status')->check($data)){
			$this->error($validate->getError());
		} */
		$res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
		$location = model('BisLocation')->save(['status'=>$data['status']],['bis_id'=>$data['id'],'is_main'=>1]);
		$account = model('BisAccount')->save(['status'=>$data['status']],['bis_id'=>$data['id'],'is_main'=>1]);
		if($res && $location && $account){
			$bis = $this->obj->get(['id'=>$data['id']]);
			$to = $bis['email'];
			$title = "o2o平台审核通知信息";
			if($bis['status']==1){
				$content = "尊敬的商家,恭喜您,审核通过";
			}elseif($bis['status']==2 ){
				$content = "尊敬的商家,您提交的信息不符合条件,审核失败";
			}elseif($bis['status']==-1){
				$content = "尊敬的商家,由于你的信息违法,你的账户信息已被删除";
			}
			if(!empty($content)){
				\phpmailer\Email::send($to,$title,$content);
			}
			$this->success('状态更新成功');
		}else{
			$this->error('状态更新失败');
			//失败时 需要把三条status恢复为原来的状态，此处暂时不做处理
		}
	}
	
	

}