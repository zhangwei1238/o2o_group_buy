<?php
namespace app\bis\controller;
use think\Controller;
class Register extends Controller
{
	public function index()
	{
		$firstCitys = model('City')->getFirstCategory();
		$firstCates = model('Category')->getNormalCategory();
		return $this->fetch('',[
			'firstCitys' => $firstCitys,
			'firstCates' => $firstCates,
		]);
	}
	public function add()
	{
		//需要做优化
		if(!request()->isPost()){
			$this->error('请求错误');
		}
		$data = input('post.');
	 
		//判断提交的用户是否存在
		$res = model('BisAccount')->get(['username'=>$data['username']]);
		if($res){
			$this->error('该用户已存在,请重新分配');
		}
		
		//商户信息校验
		$validate = validate('Bis');
		if(!$validate->scene('add')->check($data)){
			$this->error($validate->getError());
		}
		
		//商户信息入库
		//防止xss攻击 把字符串转换成html实体。 htmlentities（$data['name']）;

		$bisData = [
			'name' => $data['name'],
			'city_id' => $data['city_id'],
			'city_path' => empty($data['city_id'])?'':$data['city_id'].','.$data['se_city_id'],
			'logo' => $data['logo'],
			'license_logo' => $data['license_logo'],
			'description' => empty($data['description'])?'':$data['description'],
			'bank_info' => $data['bank_info'],
			'bank_name' => $data['bank_name'],
			'bank_user' => $data['bank_user'],
			'faren' => $data['faren'],
			'faren_tel' => $data['faren_tel'],
			'email' => $data['email'],
		];
		$bisId = model('Bis')->add($bisData);
		if(!$bisId){
			$this->error('申请失败');
		}
		//总店信息校验
		$validate = validate('BisLocation');
		if(!$validate->scene('add')->check($data)){
			model('Bis')->destroy($bisId);
			$this->error($validate->getError());
		}
		$data['cat']='';
		if(!empty($data['se_category_id'])){
			$data['cat'] = implode('|',$data['se_category_id']);
		}
		//总店信息入库
		//获取经纬度
		$lnglat = \Map::getLngLat($data['address']);
		if(empty($lnglat)||$lnglat['status']!=0||$lnglat['result']['precise']!=1){
			model('bis')->destroy($bisId);
			$this->error('无法获取数据,或匹配的地址不精确');
		}
		$bisLocationData = [
			'bis_id' => $bisId,
			'name' => $data['name'],
			'logo' => $data['logo'],
			'tel' => $data['tel'],
			'contact' => $data['contact'],
			'category_id' => $data['category_id'],
			'category_path' => $data['category_id'].','.$data['cat'],
			'city_id' => $data['city_id'],
			'city_path' => empty($data['city_id'])?'':$data['city_id'].','.empty($data['se_city_id'])?'':$data['se_city_id'],			
			'address' => $data['address'],
			'open_time' => $data['open_time'],
			'content' => empty($data['content'])?'':$data['content'],
			'is_main' => 1,           //代表总店相关信息
			'xpoint' => empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
			'ypoint' => empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],

		];
		$locationId = model('BisLocation')->add($bisLocationData);
		if(!$locationId){
			$this->error('申请失败');
			//删除上已插入的bis表信息
			model('Bis')->destroy($bisId);
		}
		
		//账户相关信息校验
		$validate = validate('BisAccount');
		if(!$validate->scene('add')->check($data)){
			model('Bis')->destroy($bisId);
			model('BisLocation')->destroy($bisId);
			$this->error($validate->getError());
		}
		//自动生成密码加盐字符串
		$data['code'] = mt_rand(100,10000);
		//账户相关信息入库
		$bisAccountData = [
			'bis_id' => $bisId,
			'username' => $data['username'],
			'password' => md5($data['password'].$data['code']),
			'code' => $data['code'],
			'is_main' => 1,           //代表总管理员
			
		];
		$accountId = model('BisAccount')->add($bisAccountData);
		if(!$accountId){
			$this->error('申请失败');
			//删除上已插入的Bis表和BisLocation信息
			model('Bis')->destroy($bisId);
			model('BisLocation')->destroy($bisId);
		}
		//成功后发送邮件
		$url = request()->domain().url('bis/register/waiting',['id'=>$bisId]);
		$title = "o2o入住申请通知";
		$content ="你提交的入住申请需等待平台方审核，你可以点击链接<a href='".$url."'target='_blank'>查看链接</a>查看审核状态";
		\phpmailer\Email::send($data['email'],$title,$content);
		
		$this->success('申请成功',url('register/waiting',['id'=>$bisId]));
	}
	
	public function waiting($id){
		if(empty($id)){
			$this->error('error');
		}
		$detail = model('Bis')->get($id);
		return $this->fetch('',[
			'detail' => $detail,
		]);
	}
}