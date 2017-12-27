<?php
/*
 *同步信息
 */
function syncMsg($msg = '', $id = 0, $flag = 'msg'){
	static $message = array();
	if($msg === '')
		return $message;
	else{
		switch($flag){
		case 'key':
			$message['key'][$id]	= $msg;
			break;
		case 'msg':
			$message['msg'][$id][]	= $msg;
			break;
		}
	}
}
