<?php
namespace app\admin\controller;
use think\Controller;
class Category extends Base
{
	private $obj;
	public function _initialize(){
		$this->obj = model('Category');
	}
    public function index()
    {   
		$parentId = input('get.parent_id',0,'intval');
		$categorys = $this->obj->getFirstCategory($parentId);
		return $this->fetch('',[
			'categorys' => $categorys,
		]); 
	}
	public function add(){
		$categorys = $this->obj->getNormalCategory();
		
		return $this->fetch('',[
			'categorys' => $categorys,
			
		]);
	}
	/* 分类添加和更新操作 */
	public function save(){
		//print_r($_POST);
		//print_r(input('post.'));推荐使用后两种
		//print_r(request()->post());
		/*做一下严格判定*/
		if(!request()->isPost()){
			$this->error('请求失败');
		}
		$data = input('post.');
		/* 验证数据 */
		$validate = validate('Category');
		if(!$validate->scene('add')->check($data)){
			$this->error($validate->getError());
		}
		/* 编辑更新操作 */
		if(!empty($data['id'])){
			return $this->update($data);
		}
		/* 首次添加操作 */
		$res = $this->obj->add($data);
		if($res){
			$this->success('栏目新增成功');
		}else{
			$this->error('栏目新增失败');
		}
	}
	/*编辑界面*/
	public function edit($id){
		if(intval($id)<1){
			$this->error('参数不合法');
		}
		$category = $this->obj->get($id);
		$categorys = $this->obj->getNormalCategory();
		
		return $this->fetch('',[
			'categorys' => $categorys,
			'category' => $category,
		]);
	}
	public function update($data){
	    $res = $this->obj->save($data,['id'=>intval($data['id'])]);
		if($res){
			$this->success('分类更新成功');
		}else{
			$this->error('分类更新失败');
		}
	}
	/* 排序逻辑 */
	/*public function listorder($id,$listorder){
		$res = $this->obj->save(['listorder'=>$listorder],['id'=>$id]);
		if($res){
			$this->result($_SERVER['HTTP_REFERER'],1,'排序更新成功');
		}else{
			$this->result($_SERVER['HTTP_REFERER'],0,'排序更新失败');
		}
	}*/
	/* 修改状态 */
	/*public function status(){
		$data = input('get.');
		$validate = validate('Category');
		if(!$validate->scene('status')->check($data)){
			$this->error($validate->getError());
		}
		$res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
		if($res){
			$this->success('状态更新成功');
		}else{
			$this->error('状态更新失败');
		}
	}*/
}