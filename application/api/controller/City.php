<?php
namespace app\api\controller;
use think\Controller;
class City extends Controller
{
	private $obj;
	public function _initialize(){
		$this->obj = model('City');
	}
    public function getCitysByParentId($id)
    {
		//$id = input('post.id');
		if(!$id){
			$this->error('ID不合法');
		}
		$citys = $this->obj->getNormalCategory($id);
		if(!$citys){
			return show(0,'error');	
		}
		return show(1,'success',$citys);
		/* 方法2
		$res = $this->obj->save(['listorder'=>$listorder],['id'=>$id]);
		if($res){
			$this->result($_SERVER['HTTP_REFERER'],1,'排序更新成功');
		}else{
			$this->result($_SERVER['HTTP_REFERER'],0,'排序更新失败');
		} */
    }	
}
