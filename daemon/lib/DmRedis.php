<?php
#redis队列处理任务
class DmRedis{
	var $taskObj;
	var $task;
	var $obj;
	var $config;
	var $queueNo;

	function __construct($config, $is_worker = true){
			$this->taskObj = new DmTask;
			$this->obj = new Redis;
			$this->config = $config;
			$this->connect();
			$this->queueNo = '__task_'.$config['queue_no'];
	}
	
	function work(){
		$data = $this->pop();
		$this->taskObj->process($data);
		return true;
	}

	function pop(){
		try{
			$data = json_decode($this->obj->lpop($this->queueNo), true);
			return $data;
		}catch(RedisException $e){
			DmLog::Exception($e->getMessage());
			$this->connect(); //断了重新连接
		}
		return false;
	}

	#根据配置来连接
	function connect(){
		$config = $this->config['host'];
		if(is_string($config)){
			$this->connectOne($config);
		}
		else if(is_array($config)){
			foreach($config as $conf){
				$this->connectOne($conf);
			}
		}
	}

	#连接单个job服务器
	function connectOne($config){
		$config = explode(':', $config);
		$host = $config[0];
		$port = $config[1]?$config[1]:6379;
		try{
			$this->obj->connect($host, $port);
		}catch(RedisException $e){
			DmLog::Exception($e->getMessage());
		}
	}


	#注册函数
	function registerFunction($tasks){
	}


	#设置任务
	function setTask($task){
			$this->task = $task;
	}

	#添加任务	
	function add($data, $pack = true){
			if($pack)
				$data = $this->taskObj->pack($this->task, $data);
			try{
				return $this->obj->lpush($this->queueNo, json_encode($data));	
			}catch(RedisException $e){
				DmLog::Exception($e->getMessage());
			}
	}

}

