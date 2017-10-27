<?php 
namespace app\index\controller;
use think\Controller;
/**
* 				
*/
class Order extends Base
{
	public function index()
	{
		// dump(input('get.'));
		$user = $this->getLoginUser();
		if(!$user){
			$this->error('请登录','user/login');
		}
		$id = input('get.id',0,'intval');
		if(!$id){
			$this->error('ID不合法');	
		}
		$dealCount = input('get.deal_count',0,'intval');
		$totalPrice = input('get.deal_price');
		$deal = model('Deal')->find($id);
		if(!$deal ||$deal->status!=1){
			$this->error('商品不存在');
		}
		//判断订单的来路
		//echo $_SERVER['HTTP_REFERER'];exit;
		if(empty($_SERVER['HTTP_REFERER'])){
			$this->error('请求不合法');
		}
		


		//组装入库数据
		$orderSn = setOrderSn(); 
		$data = [
			'out_trade_no' => $orderSn,   //订单编号
			'user_id' => $user->id,		//用户id
			'username' => $user->username, //用户名
			'payment_id' => 1,//支付方式默认1
			'deal_id' => $id,
			'deal_count' =>$dealCount,
			'total_price' =>$totalPrice,
			'referer' =>	$_SERVER['HTTP_REFERER'],

		];
		//严谨的数据的插入更新都需要做一个try catch
		try{
			$orderId = model('Order')->add($data);
		}catch(\Exception $e){
			$this->error('订单处理失败');
		}	
		$this->redirect('pay/index',['id'=>$orderId]);

	}
	
	public function confirm()
	{ 
		if(!$this->getLoginUser()){
			$this->error('请登录','user/login');
		}
		$id = input('get.id',0,'intval');
		if(!$id){
			$this->error('ID不合法');
		}
		$count = input('get.count',1,'intval');
		$deal = model('Deal')->find($id);
		if(!$deal || $deal->status!=1){
			$this->error('商品不存在');
		}
		$deal = $deal->toArray();
		//var_dump($deal);exit;
		return $this->fetch('',[
			'controller' => 'pay',
			'deal' => $deal,
			'count' => $count,
		]);
	}


}