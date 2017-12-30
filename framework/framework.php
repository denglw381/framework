<?php
define('SP_VERSION', '3.1.89'); // 当前框架版本
define("DS",DIRECTORY_SEPARATOR);

date_default_timezone_set('Asia/Chongqing');

require dirname(__FILE__).'/../daemon/lib/DmLib.php';

// 载入核心函数库
require(SP_PATH."/spFunctions.php");

spConfig(array(        
	'default_module'  => 'protected',//默认模块
	'default_controller' => 'main', // 默认的控制器名称
	'default_action' => 'index',  // 默认的动作名称
	'url_controller' => 'c',  // 请求时使用的控制器变量标识
	'url_action' => 'a',  // 请求时使用的动作变量标识
	'url_module' => 'm', //请求时模块变量标记
));

$__module	= isset($_REQUEST[spConfig('url_module')])?$_REQUEST[spConfig('url_module')]:spConfig('default_module');

//define("APP_PATH", APP_APPLICATION_PATH.DS.$__module);
// 定义系统路径
if(!defined('SP_PATH')) define('SP_PATH', dirname(__FILE__).'/../framework');
if(!defined('APP_PATH')) define('APP_PATH', dirname(__FILE__).'/../application');

define("APP_MODEL",			    SP_PATH.DS."model");
define("APP_CONF",			    APP_PATH.DS."config");
define("APP_FUN",			    APP_PATH.DS."function");
define("APP_CONTROLLER_PATH",   APP_PATH.DS."controller");
define("PROTECTED_PATH",		dirname(__FILE__).DS.'..');
define("RUNTIME_PATH",			PROTECTED_PATH.DS."_runtime");
define("MODEL_PATH",			PROTECTED_PATH.DS."model");
define("SERVICE_PATH",			PROTECTED_PATH.DS."service");
define("UPLOAD_PATH",			PROTECTED_PATH.DS."uploads");
define("CONFIG_PATH",			PROTECTED_PATH.DS."config");
define("VIEW_PATH",			    PROTECTED_PATH.DS."view");


// 载入配置文件
spConfig(require(SP_PATH."/spConfig.php"),$spConfig);
if(file_exists(APP_CONF."/config.php")) spConfig(require(APP_CONF."/config.php"));

// 根据配置文件进行一些全局变量的定义
if('debug' == spConfig('mode')){
	define("SP_DEBUG",TRUE); // 当前正在调试模式下
}else{
	define("SP_DEBUG",FALSE); // 当前正在部署模式下
}
// 如果是调试模式，打开警告输出
if (SP_DEBUG) {
  	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
} else {
	error_reporting(0);
}

// 自动开启SESSION
if(spConfig('auto_session'))@session_start();

// 载入核心MVC架构文件
import(spConfig("sp_core_path")."/spController.php", FALSE, TRUE);
import(spConfig("sp_core_path")."/spModule.php", FALSE, TRUE);
import(spConfig("sp_core_path")."/spModel.php", FALSE, TRUE);
import(spConfig("sp_core_path")."/spService.php", FALSE, TRUE);
import(spConfig("sp_core_path")."/spView.php", FALSE, TRUE);

//载入LOG文件
import(SP_PATH."/library/SpLog.class.php", FALSE, TRUE);

//register_shutdown_function(array("SpLog","save"));
//记录关闭函数
register_shutdown_function("spRecordLog");

//记录用户输入数据
spRecordRequest(['request'=>$_POST, 'input'=>file_get_contents('php://input')]);

//设置错误处理函数
set_error_handler("spErrorHandler");
// 当在二级目录中使用SpeedPHP框架时，自动获取当前访问的文件名
if('' == spConfig('url.url_path_base')){
	if(basename($_SERVER['SCRIPT_NAME']) === basename($_SERVER['SCRIPT_FILENAME']))
		spConfig('url.url_path_base', $_SERVER['SCRIPT_NAME']);
	elseif (basename($_SERVER['PHP_SELF']) === basename($_SERVER['SCRIPT_FILENAME']))
		spConfig('url.url_path_base', $_SERVER['PHP_SELF']);
	elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === basename($_SERVER['SCRIPT_FILENAME']))
		spConfig('url.url_path_base', $_SERVER['ORIG_SCRIPT_NAME']);
}

// 在使用PATH_INFO的情况下，对路由进行预处理
if(TRUE == spConfig('url.url_path_info') && !empty($_SERVER['PATH_INFO'])){
	$url_args = explode("/", $_SERVER['PATH_INFO']);$url_sort = array();
	for($u = 1; $u < count($url_args); $u++){
		if($u == 1)$url_sort[spConfig("url_controller")] = $url_args[$u];
		elseif($u == 2)$url_sort[spConfig("url_action")] = $url_args[$u];
		else {$url_sort[$url_args[$u]] = isset($url_args[$u+1]) ? $url_args[$u+1] : "";$u+=1;}}
	if("POST" == strtoupper($_SERVER['REQUEST_METHOD'])){$_REQUEST = $_POST =  $_POST + $url_sort;
	}else{$_REQUEST = $_GET = $_GET + $url_sort;}
}

// 构造执行路由
$__controller	= isset($_REQUEST[spConfig("url_controller")])?$_REQUEST[spConfig("url_controller")]:spConfig("default_controller");
$__action	= isset($_REQUEST[spConfig("url_action")])?$_REQUEST[spConfig("url_action")]:spConfig("default_action");

spConfig(spConfig('url_module'),$__module);
spConfig(spConfig('url_controller'),$__controller);
spConfig(spConfig('url_action'),$__action);


//调试sql语句
if($_GET["r"]==1) spConfig("showsqlinpage",TRUE);
spConfig('open_api.key', sha1((strtotime(date('Y-m-d',time()))>>5).spConfig('open_api.key')));
spConfig('svn_transport_tmp_dir', spConfig('svn_transport_tmp_dir').'/'.getUniqid());
// 自动执行用户代码
if(TRUE == spConfig('auto_sp_run')) spRun();
//记录log
if(spConfig('auto_model')){
			file_put_contents(SP_PATH.DS."lock.php","<?php ?>");
			import(SP_PATH."/library/AutoModel.class.php",false,true);
			AutoModel::makeAllModels();
}
if(file_exists(APP_FUN.DS.'function.php')) require_once APP_FUN.DS.'function.php';

/**
 *自动加载数据
 */
spl_autoload_register(
    function($classname){
        if(strtolower(substr($classname, -10)) == 'controller'){
                include APP_PATH.DS.spConfig(spConfig('url_module')).DS.'controller'.DS.$classname.'.php'; 
        }else{
                $path = SP_PATH.'/Extensions/'.$classname.'.php';
                if(is_file($path)) require $path; 
        }
    }
);
