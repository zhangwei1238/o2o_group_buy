<?php
namespace app\common\validate;
use think\Validate;
class Bis extends Validate
{
	protected $rule = [
		['name','require|max:25','商户名称不能为空|商户名称不能超过25个字符'],
		['email','require|email','Email不能为空|Email格式不正确'            ],
		['logo','require','请上传缩略图Logo'           ],
		['city_id','require', '请选择所属城市'       ],
		['bank_info','require','请输入银行卡号信息'      ],
		['bank_name','require','请输入银行名称'      ],
		['bank_user','require','请输入银行卡所有者姓名'      ],
		['faren' ,'require', '请输入商户法人名称'         ],
		['faren_tel' , 'require', '请输入商户法人电话号码'     ],
	];
	protected $scene = [
		'add' => ['name','email','logo','city_id','bank_info','bank_name','bank_user','faren','faren_tel'],
		
	];
}