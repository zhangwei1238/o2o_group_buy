<?php 
namespace app\admin\controller;
use think\Controller;
class Featured extends Base
{
	private $obj;
	public function _initialize(){
		$this->obj = model('Featured');
	}
   	
   	public function index(){
   		//获取推荐类别
   		 
   		$type = input('get.type',['neq',-1],'intval');

   		//获取列表数据
   		$featureds = $this->obj->getFeaturedsByType($type);
   		$types = config('featured.featured_type');

   		return $this->fetch('',[
   			'types' => $types,
   			'featureds' => $featureds,
   			'type' => $type,
   		]);
	   	
   	}

   	public function add(){
   		if(request()->isPost()){
   			//入库的逻辑
   			$data = input('post.');
   			//严格的数据校验 TP5 validate
   			$id = model('featured')->add($data);
   			if($id){
   				$this->success('添加成功');
   			}else{
   				$this->error('添加失败');
   			}
   		}else{
	   		//获取推荐位类别
	   		$types = config('featured.featured_type');
	   		return $this->fetch('',[
	   			'types' => $types
	   		]);
  	 	}
   	}

   	/*public function status(){
   		//获取值
   		$data = input('get.');
   		//校验数据validate
   		$res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
   		if($res){
   			$this->success('更新成功');
   		}else{
   			$this->error('更新失败');
   		}

   	}*/
}