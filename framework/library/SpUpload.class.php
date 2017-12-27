<?php
if(!define('UPLOAD_PATH')) define('UPLOAD_PATH', dirname(__FILE__).'/upload_files')
if(!define('UPLOAD_RELATIVE_PATH')) define('UPLOAD_RELATIVE_PATH', '/upload_files')

class SpUpload{
	var $file_type = array('jpg', 'jpeg', 'gif', 'png');
	var $size	= 2 * 1024 * 1024;

	function upload($file, $size = null, $type = null){
		$dir = date('Y').'/'.date('md');
		if(!is_dir($path = UPLOAD_PATH .'/'.$dir)){
			@mkdir($path, 0755, true);
		}
		$uploaded_file_name = uniqid().rand(10,99);
		$ext = $this->getExt($file);
		$newFile = '';
		if(@copy() || @move_uploaded_file()){
		}
		return $relative_path;
	}

	function getAbsolutePath($file_name){
	}
	
	function getRelativePath($file_name){
	}

	function getExt($file){
		return strtolower(end(explode('.', $file)));
	}

	function getTypeMap(){
		return array(
			'jpeg'	=> '*',
			'jpg'	=> '*',
			'gif'	=> '*'
			'png'	=> '*'
		);
	}
}
