<?php
/**
 *  OPENAPID接口PHP版
 */
define("OPENAPI_KEY","mhs@90n%4u/se");
define("OPENAPI_KEY_FLAG","sign");

class SpOpenApi{
	var $key;  //安全校验码

	function __construct(){
		$this->key= OPENAPI_KEY;
	}

	private function buildMysign($sort_para) {
		$prestr = $this->createLinkstring($sort_para);
		$prestr = $prestr.$this->key;
		$mysgin = md5($prestr);
		return $mysgin;
	}

	function getUnsignData($arr){
		$data = $this->paraFilter($arr);
		$sign = $this->buildMysign($data);
		if($arr[OPENAPI_KEY_FLAG] == $sign){
			return $data;
		}else return false;
	}

	function getSignData($arr){
		$data = $this->paraFilter($arr);
		$sign = $this->buildMysign($data);
		$arr[OPENAPI_KEY_FLAG] = $sign;
		return $arr;
	}

	private function createLinkstring($para) {
		$arg  = "";
		$para=$this->argSort($para);
		while (list ($key, $val) = each ($para)) {
			$arg.=$key.$val;
		}
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		return $arg;
	}

	private function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == OPENAPI_KEY_FLAG || $key == 'file' || $val == '')continue;//过滤了file字段
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}

	private function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}
 }
/*
$api = new OpenApi;
$data = $api->getSignData(array('a'=>1,'b'=>2,'c'=>3,'d'=>4)); 
var_dump($data);
$data = $api->getUnSignData($data); 
var_dump($data);
*/
?>
    
