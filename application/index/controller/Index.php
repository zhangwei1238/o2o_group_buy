<?php
namespace app\index\controller;
use think\Controller;
class Index extends Base
{
    public function index()
    {
       
    	//获取广告位
    	$bigImages = model('Featured')->getNormalFeaturedsByType(0);
    	$smallImages = model('Featured')->getNormalFeaturedsByType(1);
    	//获取美食数据
    	$dealFoods = model('Deal')->getNormalDealByCategoryCityId(1,$this->city->id);
    	//获取四个子分类
    	$foodCates = model('Category')->getNormalRecommendCategoryByParentId(1,4);
    	//print_r($dealFoods);exit;
		return $this->fetch('',[
			'bigImages' => $bigImages,
			'smallImages' =>$smallImages,
			'foodCates' =>$foodCates,
			'dealFoods' => $dealFoods,
		]);
    }
}
