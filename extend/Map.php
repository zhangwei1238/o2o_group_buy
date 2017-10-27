<?php
/**
 *百度地图相关业务封装
 */
class Map{
	
	/**
	*根据地址来获取经纬度
	* \Map::getLngLat("安徽省亳州市");
	*/
	
	public static function getLngLat($address){
		//http://api.map.baidu.com/geocoder/v2/?callback=renderOption&output=json&address=百度大厦&city=北京市&ak=您的ak
		if(!$address){  
			return '';
		}
		$data = [
			'address' => $address,
			'ak' => config('map.ak'),
			'output' => 'json',
		];
		$url = config('map.baidu_map_url').config('map.geocoder').'?'.http_build_query($data);
		//$result = file_get_contents($url);
		//curl
		$result = doCurl($url);
		if($result){
			return json_decode($result,true);
		}else{
			return [];
		} 
	}
	/* 根据经纬度或地址获取百度地图 */
	//http://api.map.baidu.com/staticimage/v2
	public static function staticimage($center){
		if(!$center){
			return '';
		}
		$data = [
			'ak' => config('map.ak'),
			'width' => config('map.width'),
			'height' => config('map.height'),
			'center' => $center,
			'makers' => $center,
		];
		$url = config('map.baidu_map_url').config('map.staticimage').'?'.http_build_query($data);
		//$result = file_get_contents($url);
		//curl
		$result = doCurl($url);
		return $result; 
		/* 
		header('Content-type:image/PNG');
		echo $result;
		此处的$result为png图片资源，输出的方法有三种，
		一种是如上，第二种是写一个方法返回，通过调用src=":url('city/map')",
		第三种是函数返回url,放在image的src中，不建议使用，建议使用第二种
		
		*/
	}
}