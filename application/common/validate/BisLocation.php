<?php
namespace app\common\validate;
use think\Validate;
class BisLocation extends Validate
{
	protected $rule = [
		['tel' ,'require|max:20','总店电话不能为空|电话长度不能超过20'     ],
		['contact' , 'require', '联系人不能为空'              ],
		['address', 'require',  '地址不能为空'               ],
		['open_time' , 'require', '营业时间不能为空'              ],
	];                                        
	protected $scene = [
		'add' => ['name','tel','contact','address','open_time'],
	];
}