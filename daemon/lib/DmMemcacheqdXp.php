<?php
#redis队列处理任务
class DmMemcacheqdXp{
	var $taskObj;
	var $task;
	var $obj;
	var $queueNo;

	function __construct($config, $is_worker = true){
			$this->taskObj = new DmTask;
			$this->obj = new Memcache;
			$this->connect($config['host']);
			$this->queueNo = $config['queue_no'];
	}
	
	function work(){
		$data = $this->pop();
		$this->taskObj->process($data);
		return true;
	}

	function pop(){
		return json_decode($this->obj->get('queue_get_'.$this->queueNo), true);
	}

	#根据配置来连接
	function connect($config){
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
		$port = $config[1]?$config[1]:11211;
		$this->obj->connect($host, $port);
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
			return $this->obj->add('queue_radd_'.$this->queueNo.'_!', json_encode($data), 0, 0);	
	}

}
/*
class DmMemcacheqd{
	function work(){

	}
}
*/
