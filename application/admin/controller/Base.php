<?php 
namespace app\admin\controller;
use think\Controller;
class Base extends Controller
{
	/**
	 * 数据状态修改逻辑      
	 * @return null null
	 */
	public function status(){
	   //获取值
		$data = input('get.');
		if(empty($data['id'])){
			$this->error('ID不合法');
		}
		if(!is_numeric($data['status'])){
			$this->error('状态不合法');
		}
		//校验数据validate 暂不校验
		
		//获取控制器
		$model = request()->controller();

		$res = model($model)->save(['status'=>$data['status']],['id'=>$data['id']]);
		if($res){
			$this->success('更新成功');
		}else{
			$this->error('更新失败');
		}	
	}

   /**
    * 数据列表排序逻辑
    * @param  int $id        数据的id
    * @param  int $listorder 排序值
    * @return null            null
    */
	public function listorder($id,$listorder){
      //获取控制器
      $model = request()->controller();
      //数据的严格校验 validate
      //数据入库
      $res = model($model)->save(['listorder'=>$listorder],['id'=>$id]);
      if($res){
         $this->result($_SERVER['HTTP_REFERER'],1,'排序更新成功');
      }else{
         $this->result($_SERVER['HTTP_REFERER'],0,'排序更新失败');
      }
   }
}
