<?php
namespace hcgrzh\validation;
class Validation{
	//是否空值
	public static function isEmpty($value):bool{
		$rule='/^[\\s]*$/';
		return preg_match($rule,$value)===1;
	}
	//匹配正整数
	public static function isInt($value):bool{
		$rule='/^\d+$/';
		return preg_match($rule,$value)===1;
	}
	//匹配整数
	public static function isIntAll($value):bool{
		$rule='/^[-\+]?\d+$/';
		return preg_match($rule,$value)===1;
	}
	//匹配英文
	public static function isEnlish($value):bool{
		$rule='/^[A-Za-z]+$/';
		return preg_match($rule,$value)===1;
	}
	//匹配英文+数字
	public static function isEnlishNumber($value):bool{
		$rule='/^[a-zA-Z0-9]+$/';
		return preg_match($rule,$value)===1;
	}
	public static function isEnNumChar($value,$specialCharacters=''):bool{
		$rule="/^[A-Za-z0-9$specialCharacters]+$/";
		return preg_match($rule,$value)===1;
	}
	//密码由大写、小写、数字、@._等字符组成
	public static function isPass($value,$sartlen=8,$endlen=20,$specialCharacters='@._'):bool{
		$rule="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[$specialCharacters])[A-Za-z0-9$specialCharacters]{8,20}$/";
		return preg_match($rule,$value)===1;
	}
	//存在中文
	public static function isChinese($value):bool{
		$rule='/[\x7f-\xff]/';
		return preg_match($rule,$value)===1;
	}
	//全是中文
	public static function isChineseAll($value):bool{
		$rule='/^[\x{4e00}-\x{9fa5}]+$/u';
		return preg_match($rule,$value)===1;
	}
	//匹配身份证
	public static function isIdCard($value):bool{
		$rule='/^(\d{18,18}|\d{15,15}|\d{17,17}x)$/';
		return preg_match($rule,$value)===1;
	}
	//匹配价格
	public static function isPrice($value,$numfloat=2):bool{
		$rule="/^\d+\.?\d{0,$numfloat}$/";
		return preg_match($rule,$value)===1;
	}
	//匹配精度
	public static function isDouble():bool{
		$rule='/^[-\+]?\d+(\.\d+)?$/';
		return preg_match($rule,$value)===1;
	}
	//固定号码验证
	public static function isTell($value):bool{
		$rule='/^([0-9]{3,4}-)?[0-9]{7,8}$/';
		return preg_match($rule,$value)===1;
	}
	//手机号码
	public static function isMobile($value):bool{
		$rule='/^1[3|4|5|7|8|9][0-9]\d{8}';
		return preg_match($rule,$value)===1;
	}
	//邮箱
	public static function isEmail($value):bool{
		$rule='/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
		return preg_match($rule,$value)===1;
	}
	//是否存在http
	public static function isHttp($value):bool{
		if(stripos($value,'http')===false){return false;}else{return true;}
	}
	//是否存在https
	public static function isHttps($value):bool{
		if(stripos($value,'https')===false){return false;}else{return true;}
	}
	//是否存在http 或 https
	public static function isHttpAll($value):bool{
		if(stripos($value,'https')===false && stripos($value,'http')===false){return false;}else{return true;}
	}
	//是否url
	public static function isUrl($value):bool{
		$rule='/^http(s?):\/\//';
		return preg_match($rule,$value)===1;
	}
}
?>