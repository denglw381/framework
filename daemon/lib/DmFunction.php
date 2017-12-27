<?php
/**
 * 配置文件读取函数
 */
function dmConfig($name="",$value=""){
        $argc = func_num_args();
	static $_config = array();
	if(is_array($name)){
	      $_config = array_merge($_config,$name);	
	   if(is_array($value)){
	      $_config = array_merge($_config,$value);	
	   }
	}else if($name!=="" && is_scalar($name)){
	   if($argc==1){
		 if(strpos($name,".")){
                    $keys = explode(".",$name); 
		    return $_config[$keys[0]][$keys[1]];
		 }else
		 return $_config[$name];
	   }else{
        	 if(strpos($name,".")){
                    $keys = explode(".",$name); 
		    $_config[$keys[0]][$keys[1]] = $value;
		 }else
                 $_config[$name] = $value;
		 return $_config[$name];
	   }
	}
	return $_config;
}


#错误处理器
function dmErrorHandler($errno, $errstr, $errfile, $errline)
{
/*
		if (!(error_reporting() & $errno)) {
				return;
		}   
*/
		switch ($errno) {
				case E_USER_ERROR:
						DmLog::error($errstr." ".$errfile." ".$errline);
						break;

				case E_USER_WARNING:
						DmLog::warn($errstr." ".$errfile." ".$errline);
						break;

				case E_USER_NOTICE:
						DmLog::notice($errstr." ".$errfile." ".$errline);
						break;

				default:
						DmLog::error($errstr." ".$errfile." ".$errline);
						break;
		}
		return true;
}

#异常抛出函数，简化异常操作
function dmException($exception){
	throw new DmException($exception);
}

#实例化队列操作函数
function dmTask($task){
	static	$pool = array();
	$key = $task;
	if(!(array_key_exists($key, $pool) && $pool[$key])){
		$config = dmConfig('task.'.$task);
		if(empty($config)) throw dmException('task '.$task. ' not exists!');
		$queue = dmConfig('queue_config.'.$config['queue']);
		$handle = dmConfig('queue_handle.'.$queue['type']);
		$config = $queue['config'];
		$handler = new $handle($config, false);
		$handler->setTask($task);
		$pool[$key] = $handler;
	}
	return $pool[$key];
}


