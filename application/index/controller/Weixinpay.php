<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\database\WxPayResults;
use wxpay\WxPayConfig;
use wxpay\NativePay;
use wxpay\WxPayApi;
use wxpay\database\WxPayNotify;
use weixin\PayNotifyCallBack;
class Weixinpay extends Controller
{
     public function notify()
     {
        //测试
        $weixinData = file_get_contents("php://input");
        try {
        	$resultObj = new WxPayResults();
        	$weixinData = $resultObj->Init($weixinData);
        } catch (\Exception $e) {
        	$resultObj->setData('return_code','FAIL');
        	$resultObj->setData('return_msg',$e->getMessage());
        	return $resultObj->toXml();
        }
		if($weixinData['return_code'] === 'FAIL' || $weixinData['return_code'] !== 'SUCCESS'){
			$resultObj->setData('return_code','FAIL');
			$resultObj->setData('return_msg','error');
			return $resultObj->toXml();
		}
		//根据订单号out_trade_no 来查询订单
		$outTradeNo = $weixinData['out_trade_no'];
		$order = model('Order')->get(['out_trade_no'=>$outTradeNo]);
		if(!$order || $order->pay_status==1){
			$resultObj->setData('return_code','SUCCESS');
			$resultObj->setData('return_msg','OK');
			return $resultObj->toXml();
		}
		//更新表 订单表 商品表 消费券的生成
		try {
          	
          		//消费券的生成
          	$coupons = [
				'sn' => $outTradeNo,
              	'password' => rand(10000,99999),
               	'deal_id' =>$order->deal_id,
                'order_id' =>$order->id,
                'user_id' =>$order->user_id,
          
			];
          $res = model('Coupons')->add($coupons);
          //发送邮件 最好不要在这里发送邮件
          $user = model('User')->find($order->user_id); 
          $to = $user->email;
           
          $title = 'o2o团购！';
          $content ='您的消费券为：'.$coupons['password'];
          \phpmailer\Email::send($to,$title,$content);
          
		  $orderRes = model('Order')->updateOrderByOutTradeNo($outTradeNo,$weixinData);
		  $dealRes = model('Deal')->updateDealById($order->deal_id,$order->deal_count);
			
		
		} catch (\Exception $e) {
			//说明 没有更新 我们还需要回调
			return false;
		}
		//说明 我们成功了 不需要回调了
		$resultObj->setData('return_code','SUCCESS');
		$resultObj->setData('return_msg','OK');
		return $resultObj->toXml();
		

		 
		
     }
     public function wxpayQCode($id)
     {
     	$notify = new NativePay();
     	$input = new WxPayUnifiedOrder();
		$input->setBody("测试 1分钱 支付");
		$input->setAttach("附加数据 测试 1分钱 支付");
		$input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
		$input->setTotalFee("1");
		$input->setTimeStart(date("YmdHis"));
		$input->setTimeExpire(date("YmdHis", time() + 600));
		$input->setGoodsTag("QRCode");
		$input->setNotifyUrl("/index.php/index/weixinpay/notify");
		$input->setTradeType("NATIVE");
		$input->setProductId($id);
		$result = $notify->GetPayUrl($input);
		if(empty($result["code_url"])){
			$url = '';
		}else{
			$url = $result["code_url"];
		}
		return '<img alt="扫码支付" src="/weixin/example/qrcode.php?data='.urlencode($url).'"style="width:300px;height:300px;"/>';
     }
}
