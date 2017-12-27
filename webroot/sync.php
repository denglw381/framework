<?php
error_reporting(~(E_WARNING | E_NOTICE));
define("OPENAPI_KEY","mhs@90n%4u/se");
define("OPENAPI_KEY_FLAG","sign");
define('ACCESS_IP', '192.168.2.80');
$config = array(
		array(
			'host'=>'',
			'user'=>'',
			'password'=>'',
			'webroot'=>'',
		),
		array(

		),
		array(

		)
);

class SyncWeb{
	function run(){
		$api = new OpenApi;
		if(false === $api->getUnsignData($_POST)) exit(0);
		$operate = $_POST["operate"];
		$filename = $_POST['webdir']."/".$_POST["filename"];
		if(empty($operate) || empty($filename)) exit(0);
		$this->$operate($filename);
	}

	function add($filename = null){
		if (@move_uploaded_file($_FILES['file']['tmp_name'], $filename) || @copy($_FILES['file']['tmp_name'], $filename)) {
			echo 1;
		} else {
			echo -1;
		}
	}

	function delete($filename){
		if(file_exists($filename)){
			if(@unlink($filename)){
				echo 1;
			}else{
				echo -1;
			}
		}
		else echo 1;
	}

	function mkdir($dir){
		if(!is_dir($dir)){
			if(@mkdir($dir)){
				echo 1;
			}else{
				echo -1;
			}
		}
		else echo 1;
	}
	#删除文件夹
	function rmdir($dir){
		if(is_dir($dir)){
			if($this->deldir($dir)){
				echo 1;
			}else{
				echo -1;
			}
		}
		else echo 1;
	}	
	#删除文件夹
	private function deldir($dir) {
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(is_dir($fullpath)) {
					deldir($fullpath);
				}
				else @unlink($fullpath);
			}
		}
		@rmdir($dir);
		closedir($dh);
		return true;
	}

}

$sw = new SyncWeb();
$sw->run();


class OpenApi{
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
			if($key == OPENAPI_KEY_FLAG || $key == 'file' || $val == '')continue;
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

