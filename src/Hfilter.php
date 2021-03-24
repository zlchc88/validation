<?php
namespace hcgrzh\validation;
require_once(__DIR__."/htmlpurifier/library/HTMLPurifier.auto.php");
class Hfilter{
	protected static $unCleanField=[];
	protected static $thisObj=null;
	// filterXssArr 中过滤字段中 不进行过滤字段设置
	public static function unClearFilterFeild($field){
		self::$unCleanField=$field;
	}
	public static function setconfig($configArgs=null){
		$config=\HTMLPurifier_Config::createDefault();
		$config->set('Core.Encoding','UTF-8');
		if(!is_null($configArgs)){
			foreach($configArgs as $k=>$v){
				$config->set($v[0],$v[1],$v[2]);
			}
		}
		self::$thisObj=new \HTMLPurifier($config);
	}
	//字符串
	public static function filterXss($str,$config=null){
		if(self::$thisObj===null){self::setconfig();}
		return self::$thisObj->purify($str,$config);
	}
	//数组
	public static function filterXssArr($arr,$config=null){
		$noarr=[];
		if(!empty(self::$unCleanField)){
			foreach($arr as $k=>$v){
				if(in_array($k,self::$unCleanField)){
					$noarr[$k]=$v;
					unset($arr[$k]);
				}
			}
		}
		if(self::$thisObj===null){self::setconfig();}
		if(!empty($arr)){
			$arr=self::$thisObj->purifyArray($arr,$config);
		}
		return array_merge($noarr,$arr);
	}
}
/*Hfilter::unClearFilterFeild([]);
if(isset($_POST) && !empty($_POST)){
	$_POST=Hfilter::filterXssArr($_POST);
}
if(isset($_GET) && !empty($_GET) ){
	$_GET=Hfilter::filterXssArr($_GET);
}*/
?>
