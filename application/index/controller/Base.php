<?php
namespace app\index\controller;
use think\Controller;
class Base extends Controller
{
	//city和account都是对象
	public $city = '';
	public $account = '';
    public function _initialize(){
    	//获取城市数据
    	$citys = model('City')->getNormalCitys();
    	//获取用户数据
    	
    	$this->getCity($citys);
        $cats = $this->getRecommendCats();
    	$this->assign('citys',$citys);
    	$this->assign('city',$this->city);
        $this->assign('cats',$cats);
        $this->assign('controller',strtolower(request()->controller()));
    	$this->assign('user',$this->getLoginUser());
        $this->assign('title','O2O团购网');
    }

    public function getCity($citys)
    {
    	foreach ($citys as $city) {
    		//将对象city转成数组city
    		$city = $city->toArray();
    		if($city['is_default']==1){
    			$defaultuname = $city['uname'];
    			break;
    		}
    	}
    	$defaultuname = $defaultuname ? $defaultuname : 'bozhou';
    	if(session('cityuname','','o2o') && !input('get.city')){
    		$cityuname = session('cityuname','','o2o');
    	}else{
    		$cityuname = input('get.city',$defaultuname,'trim');
    		session('cityuname',$cityuname,'o2o');
    	}
    	$this->city = model('City')->where(['uname'=>$cityuname])->find();
    }

    public function getLoginUser(){
    	if(!$this->account){
    		$this->account = session('o2o_user','','o2o');
    	}
    	return $this->account;
    }

    //首页分类的商品分类数据 5条
    public function getRecommendCats(){
        $parentIds = $sedCatArr = $recomCats = [];
        //获取一级分类
        $cats = model('Category')->getNormalRecommendCategoryByParentId();
        
        //获取一级分类的id
        foreach ($cats as $cat) {
            $parentIds[] = $cat->id;
        }

        //获取二级分类
        $sedCats = model('Category')->getNormalCategoryIdByParentId($parentIds);

        //print_r($sedCats);exit;
        foreach($sedCats as $sedCat){
            $sedCatArr[$sedCat->parent_id][] = [
                    'id' => $sedCat->id,
                    'name' => $sedCat->name
            ];
        }    
        foreach($cats as $cat){
            /*recomCats 代表一级分类和二级数据，第一个参数时一级分类name，第二个参数是此一级分类下的二级分类数据*/
            $recomCats[$cat->id] = [$cat->name,empty($sedCatArr[$cat->id])?[]:$sedCatArr[$cat->id],
            ];
        }
        //print_r($recomCats);exit;
        return $recomCats;
    }
}
