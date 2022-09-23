<?php
namespace hcgrzh\validation;
require_once(__DIR__."/htmlpurifier/library/HTMLPurifier.auto.php");
class Hfilter{
	protected static $unCleanField=[];
	protected static $unRemoveField=[];
	protected static $thisObj=null;
	// filterXssArr 中过滤字段中 不进行过滤字段设置
	public static function unClearFilterFeild($field){
		self::$unCleanField=$field;
	}
	public static function unRemoveFilterFeild($field){
		self::$unRemoveField=$field;
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
		$str=self::RemoveXSS($str);
		return self::$thisObj->purify($str,$config);
	}
	//数组
	public static function filterXssArr($arr,$config=null){
		//print_r($arr);exit;
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
			foreach($arr as $k=>$v){
				if(is_string($v)){
					$arr[$k]=self::RemoveXSS($v);
				}
			}
			$arr=self::$thisObj->purifyArray($arr,$config);
		}
		if(!empty($arr)){
			foreach($arr as $k=>$v){
				$noarr[$k]=$v;
			}
		}
		//$arrend=array_merge($noarr,$arr);
		//return $arrend;
		return $noarr;
	}
	public static function removeXSSArr($arr){
		$noarr=[];
		if(!empty(self::$unRemoveField)){
			foreach($arr as $k=>$v){
				if(in_array($k,self::$unRemoveField)){
					$noarr[$k]=$v;
					unset($arr[$k]);
				}
			}
		}
		if(!empty($arr)){
			foreach($arr as $k=>$v){
				if(is_string($v)){
					$arr[$k]=self::RemoveXSS($v);
				}
			}
		}
		if(!empty($arr)){
			foreach($arr as $k=>$v){
				$noarr[$k]=$v;
			}
		}
		//$arrend=array_merge($noarr,$arr);
		//return $arrend;
		return $noarr;
	}
	/**
	 * @去除XSS（跨站脚本攻击）的函数
	 * @par $val 字符串参数，可能包含恶意的脚本代码如<script language="javascript">alert("hello world");</script>
	 * @return  处理后的字符串
	 * @Recoded By Androidyue
	 **/
	public static function RemoveXSS($val) {  
	   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
	   // this prevents some character re-spacing such as <java\0script>  
	   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs  
	   $val = preg_replace('/([\x00-\x08\x0b-\x0c\x0e-\x19])/', '', $val);
	   // straight replacements, the user should never need these since they're normal characters  
	   // this prevents like <IMG SRC=@avascript:alert('XSS')>  
	   $search = 'abcdefghijklmnopqrstuvwxyz'; 
	   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	   $search .= '1234567890!@#$%^&*()';
	   $search .= '~`";:?+/={}[]-_|\'\\';
	   for ($i = 0; $i < strlen($search); $i++) { 
	      // ;? matches the ;, which is optional 
	      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
	 
	      // @ @ search for the hex values 
	      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ; 
	      // @ @ 0{0,7} matches '0' zero to seven times  
	      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
	   }
	   // now the only remaining whitespace attacks are \t, \n, and \r 
	   // 20220714 上传配置base64 xml  错误   $ra1 已取消xml 
	   $ra1=array('javascript','script','vbscript');
	   //$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
	   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce',
	'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart',
	'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove',
	'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted',
	'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
	   $ra = array_merge($ra1, $ra2); 
	   $found = true; // keep replacing as long as the previous round replaced something 
	   while ($found == true) { 
	      $val_before = $val; 
	      for ($i = 0; $i < sizeof($ra); $i++) { 
	         $pattern = '/'; 
	         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
	            if ($j > 0) { 
	               $pattern .= '(';  
	               $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
	               $pattern .= '|';  
	               $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
	               $pattern .= ')*'; 
	            } 
	            $pattern .= $ra[$i][$j]; 
	         }
	         $pattern .= '/i';  
	         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag  
	         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
	         if ($val_before == $val) {  
	            // no replacements were made, so exit the loop  
	            $found = false;  
	         }  
	      }  
	   }  
	   return $val;  
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
