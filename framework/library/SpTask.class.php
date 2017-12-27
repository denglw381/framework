<?php
define('SP_TASK_PATH', dirname(__FILE__).'/method');
class SpTask{
	var $taskDir = NULL;			
	var $queue;
	static $instance = NULL;

	function __construct(){
		$class	= SPQUEUE;
		$this->queue = $class::getInstance();
	}
	
	static function getInstance(){
		if(self::$instance instanceof self){
		}else{
			self::$instance = new self;
		}
		return self::$instance;
	}

	function run(){
		$data = $this->queue->pop();
		$class = self::import($data['data']);
		$class->run($data['data']);
	}

	function set($data, $queueNo = 1){
		$this->queue->push($data, $queueNo);	
	}

	static function import($method){
		static $pools = array();
		if(!array_key_exists($method, $pools)){
			if($file_exists($path = SP_TASK_PATH.'/'.$method.'.class.php')){
				require $path;
				$pools[$method] = new $method;
			}	
		}
		return $pools[$method];
	}
}


