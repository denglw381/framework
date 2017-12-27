<?php
class SpApc{
	function bin_dump(array $file = NULL, array $var = NULL){
		return apc_bin_dump($file, $var);
	}

	function cache_info(){
		return apc_cache_info();
	}
}

$test = new SpApc;
//$file = array(dirname(__FILE__).'/OpenApi.class.php');
//require_once(dirname(__FILE__).'/OpenApi.class.php');
//$return = $test->bin_dump(NULL, NULL);
//file_put_contents('apc_data.bin', $return);
var_dump($test->cache_info());
?>
