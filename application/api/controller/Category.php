<?php
namespace app\api\controller;
use think\Controller;
class Category extends Controller
{
	private $obj;
	public function _initialize(){
		$this->obj = model('Category');
	}
    public function getCatesByParentId($id)
    {
		//$id = input('post.id');
		if(!$id){
			$this->error('ID不合法');
		}
		$categorys = $this->obj->getNormalCategory($id);
		if(!$categorys){
			return show(0,'error');	
		}
		return show(1,'success',$categorys);
    }	
}