<?php
namespace app\admin\controller;
use think\Controller;
class City extends Base
{
	private $obj;
	public function _initialize(){
		$this->obj = model('City');
	}
    public function index(){
		
		$parentId = input('get.parent_id',0,'intval');
		$categorys = $this->obj->getFirstCategory($parentId);
		return $this->fetch('',[
			'categorys' => $categorys,
		]);
	}
	public function map(){
		$map = \Map::staticimage("安徽省亳州市");
		return $map;
	}
	
	public function add(){
		$categorys = $this->obj->getNormalCategory();
		return $this->fetch('',[
			'categorys' => $categorys,
		]);
	}
	public function save(){
		$data = input('post.');
		if(!request()->isPost()){
			$this->error('请求失败');
		}
		$validate = validate('City');
		if(!$validate->scene('add')->check($data)){
			$this->error($validate->getError());
		}
		if(!empty($data['id'])){
			return $this->update($data);
		}
		$res = $this->obj->add($data);
		if($res){
			$this->success('城市新增成功');
		}else{
			$this->error('城市新增失败');
		}
	}
	public function update($data){
		$res = $this->obj->sava($data,[
			'id' => intval($data['id']),
		]);
		if($res){
			$this->success('更新成功');
		}else{
			$this->error('更新失败');
		}
	}
	public function edit($id){
		if(intval($id)<1){
			$this->error('参数不合法');
		}
		$category = $this->obj->get($id);
		$categorys= $this->obj->getNormalCategory();
		return $this->fetch('',[
			'category' => $category,
			'categorys' => $categorys,
		]);
	}
	/*public function status(){
		$data = input('get.');
		$validate = validate('City');
		if(!$validate->scene('status')->check($data)){
			return $this->error($validate->getError());
		}
		$res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
		echo $this->obj->getLastsql();
		if($res){
			$this->success('状态更新成功');
		}else{
			$this->error('状态更新失败');
		}
	}*/
	/*public function listorder($id,$listorder){
		$res = $this->obj->save(['listorder'=>$listorder],['id'=>$id]);
		if($res){
			$this->result($_SERVER['HTTP_REFERER'],1,'排序更新成功');
		}else{
			$this->result($_SERVER['HTTP_REFERER'],0,'排序更新失败');
		}
	}*/
}