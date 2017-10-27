<?php
namespace app\admin\validate;
use think\Validate;
class Category extends Validate{
	protected $rule = [
		['name','require|max:20','分类名必须传递|分类名不能超过20个字符'],
		['parent_id','number'],
		['id','number'],
		['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
		['listorder','number'],
	];
	// 验证场景 scene = ['edit'=>'name1,name2,...']
    protected $scene = [
		'add' => 'name,parent_id,id',
		'listorder' => 'id,listorder',
		'status' => 'id,status',
	];
}