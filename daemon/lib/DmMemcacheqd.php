<?php
#redis队列处理任务
class DmMemcacheqd{
	var $taskObj;
	var $task;
	var $obj;
	var $queueNo;
	var $config;

	function __construct($config, $is_worker = true){
			$this->taskObj = new DmTask;
			$this->obj = new Memcached; //使用memcached拓展,memcache拓展添数据有机率会失败，原因不明
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
		$data = $this->obj->get($this->queueNo);
		if(false === $data){
			$status = $this->obj->getStats();
			if(false === $status) $this->connect; //服务器丢失尝试重新连接
		}
		return json_decode($data, true);
	}

	#根据配置来连接
	function connect(){
		$config = $this->config['host'];
		if(is_string($config)){
			$this->connectOne($config);
		}
		else if(is_array($config)){
			foreach($config as $conf){ //从所有可用的主机中选择一台连接,防止一台服务器断掉造成服务终止
				$result = $this->connectOne($conf);
				if(true === $result) return;
			}
		}
	}

	#连接单个job服务器
	function connectOne($config){
		$config = explode(':', $config);
		$host = $config[0];
		$port = $config[1]?$config[1]:11211;
		return $this->obj->addServer($host, $port);
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
			return $this->obj->set($this->queueNo, json_encode($data), 0, 0);	
	}

}
