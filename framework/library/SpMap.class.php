<?php
//静态池类
class SpPools{
	static $pools = array(
		'keys' => array(),
		'msgs' => array()
	);//静态池
	/** 
	 *php5.3以后版本方可使用  
	 */
	static function __callStatic($name,$arguments){
		if(substr($name, 0, 3) == 'key'){
		}else if(substr($name, 0, 3) == 'msg'){
		}	
	}

	static function key($name = '', $arguments = array()){
		if(count($arguments) > 0){
			self::$pools['keys'][$name] = $arguments[0];
		}else if(count($arguments) == 0){
			return self::$pools['keys'][$name];	
		}
	}

	static function msg($name = '' , $arguments = ''){
		if(count($arguments) > 0){
			self::$pools['keys'][$name][] = $arguments[0];
		}else if(count($arguments) == 0){
			return self::$pools['keys'][$name];	
		}
	}
}

$arr = 333333;
$arr1 = array();
$str = 'a_b_c_d_e_f_g';
$str1 = explode('_',$str);
/*
foreach($str1 as $s){
	$arr[$s]= array();
}
var_dump($arr);
exit;
while($queue->count()>0) {
	$str = $queue->dequeue();
	echo $str;
}
 */
function getArr($arr, $args, $is_array = true, $recursive = false){
	$result = array();
	if(count($args) > 0){
		$str = array_pop($args);
		$result[$str] = $arr;
		$arr = getArr($result, $args, $is_array, true);
	}else{
		//$arr = $value;	
	}
	return $arr;
}

$data = (getArr($arr,$str1));
var_dump($data);
reset($str1);
function getArr2($arr, $args, $is_array = true, $recursive = false){
	foreach($args as $a){
		$arr = $arr[$a];
	}
	return $arr;
}
var_dump(getArr2($data, $str1));
eval('$str[1][2][3][4][5][6][7][8][9][10]=10;');
