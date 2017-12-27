<?php
#配置类
class DmConfig{
	var $config; //配置

	function __construct(){
		$this->config = dmConfig();
	}

	#配置检查
	function check(){
			$pass = true;
			//检查每个任务是否有进程能进行处理
			$tasks = dmConfig('task');
			$proccess = $this->getProccess();
			foreach($tasks as $name=>$task){
				$has_proccess = false;
				foreach($proccess as $pro){
					if(($task['framework'] == $pro['framework']) && ($task['queue'] == $pro['queue'])){
						$has_proccess = true; break;
					}
				}
				if(false === $has_proccess){
					echo 'task "'.$name.'" don\'t have proccess please check config !!!'.chr(10);
					$pass = false;
				}
			}
			return $pass;
	}

	#获取进程
	function getProccess(){
			$result = array();
			$queues = dmConfig('queue_config');
			$i = 0;
			foreach($queues as $name=>$queue){
				foreach($queue['process'] as $framework=>$num){
					unset($queue['process']);
					while($num > 0){
						$result[$i]['frameworkQueue'] = $framework.'_'.$name;
						$result[$i]['queue']  = $name;
						$result[$i]['framework']  = $framework; // dmConfig('framework.'.$framework);
						$i++; $num--;
					}
				}
			}
			return $result;
	}

	#框架－队列任务
	function getFrameworkQueueTask($frameworkQueue = ''){
			$result = array();
			$tasks = dmConfig('task');
			foreach($tasks as $key=>$task){
				$result[$task['framework'].'_'.$task['queue']][] = $key;
			}
			if(empty($frameworkQueue)) return $result;
			if(!array_key_exists($frameworkQueue, $result)) return array();
			return $result[$frameworkQueue];
	}

	#获取所有任务名称
	function getAllTaskName(){
		return array_keys(dmConfig('task'));
	}

	#获取某个队列的所有任务
	function getQueueTask($queueName = ''){
			if(empty($queueName)) throw dmException('queueName cant\'t be empty!');
			$result = array();
			$tasks = dmConfig('task');
			foreach($tasks as $key=>$task){
				if($task['queue'] == $queueName)
					$result[] = $key;
			}
			return $result;
	}
}
