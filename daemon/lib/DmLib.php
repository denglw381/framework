<?php
#使用此daemon必须加载此库
if(!defined('DM_PATH'))
	define('DM_PATH',			dirname(__FILE__)."/..");
define('DM_LIB_PATH',		DM_PATH."/lib");
//define('DM_CLIENT',			true); //是否作为一个客户端
require dirname(__FILE__).'/DmFunction.php';
dmConfig(require DM_PATH.'/config/main.php');
dmConfig(require DM_PATH.'/config/queue.php');
dmConfig(require DM_PATH.'/config/frame.php');
dmConfig(require DM_PATH.'/config/task.php');
//set_error_handler("dmErrorHandler"); //各个系统有自己的默认错误处理函数，所以先屏蔽掉
spl_autoload_register(
	function($name){
		if(file_exists(DM_LIB_PATH.'/'.$name.'.php'))  require DM_LIB_PATH.'/'.$name.'.php';
	}	
);

