<?php
namespace app\common\validate;
use think\Validate;
class BisAccount extends Validate
{
	protected $rule = [
		['username','require|max:15','账号名称不能为空|账号名称不能超过15个字符'],
		['password', 'require','密码不能为空'],
	];
	protected $scene = [
		'add' => ['username','password'],
	];
}