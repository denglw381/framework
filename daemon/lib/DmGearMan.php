<?php
class DmGearMan{
	var $obj;
	var $is_worker = true;
	var $taskObj;
	var $has_resigter_function = false;
	var $config;
	private $task;
	function __construct($config, $is_worker = true){
			if($is_worker)
				$this->obj = new GearmanWorker();
			else 
				$this->obj = new GearmanClient();
			$this->is_worker = $is_worker;
			if($is_worker)
				$this->obj->addOptions(GEARMAN_WORKER_NON_BLOCKING); //非阻塞模式,方便停止进程
			$this->config = $config;
			$this->connect();
			$this->taskObj = new DmTask;
	}

#注册函数
	function registerFunction($tasks){
			if(false == $this->is_worker) return ;
			if(is_array($tasks)){
					foreach($tasks as $task){
							$this->obj->addFunction($task, array($this, 'handleTask'), $task); //注册通用的处理函数
					}
					if(count($tasks) > 0) $this->has_resigter_function = true;
			}
			return true;
	}

	#处理任务
	function handleTask(GearmanJob $job, $taskName){
				$task = $job->workload();
				$this->taskObj->process(json_decode($task, true));
				return GEARMAN_SUCCESS;
	}

	#根据配置来连接German
	function connect(){
		$config = $this->config;
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
		$port = $config[1]?$config[1]:4730;
		$this->obj->addServer($host, $port);
	}

	#开始工作
	function work(){
		if(false == $this->is_worker) return false;
		if($this->has_resigter_function)
		{
				if(@$this->obj->work() ||  $this->obj->returnCode() == GEARMAN_IO_WAIT || $this->obj->returnCode() == GEARMAN_NO_JOBS){
					if($this->obj->returnCode() == GEARMAN_NO_JOBS) sleep(2);
				}
				if ($this->obj->returnCode() == GEARMAN_NO_ACTIVE_FDS){ 
								$this->connect();
				} 
				return true;
		}
		else{
			sleep(2);
			return true;
		}
	}

	#设置任务
	function setTask($task){
			$this->task = $task;
	}

	#添加任务	
	function add($data, $pack = true){
			if($pack)
				$data = $this->taskObj->pack($this->task, $data);
			$this->obj->doBackground($this->task, json_encode($data));	
			return true;
	}

}

