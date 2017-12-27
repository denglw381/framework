<?php
define('DM_TASK_RET_SUCCESS',          1); 
define('DM_TASK_RET_RETRY',            2); 
define('DM_TASK_RET_FAILED',           4); 
#任务处理类
class DmTask{
		var $config;
		var $queueHandler;
		var $queueTask;
		var $frameworkQueueTask;
			
		#构造函数
		function __construct($config = ''){
				if(is_array($config)){
						$this->config = $config;
						$this->preloadFramework(); //加载框架文件
						$this->preloadQueueHandler(); //加载队列处理函数
				}
		}

		#加载任务所依赖的框架
		private function preloadFramework(){
				$preload = dmConfig('framework.'.$this->config['framework']);	
				if($preload && is_string($preload)){
					$tmp = $preload;
					$preload = array();
					$preload[] = $tmp;
				}
				if(is_array($preload)){
					foreach($preload as $file){
						if(empty($file)) continue;
						if(!is_file($file)) throw dmException('framework file '.$file.' not exists !');
						else require $file;
					}
				}
				return true;
		}

		private function preloadQueueHandler(){
			$queue = dmConfig('queue_config.'.$this->config['queue']);
			$this->queueHandler = dmConfig('queue_handle.'.$queue['type']);
			$this->queueHandler = new $this->queueHandler($queue['config']);
			$config = new DmConfig;
			$this->frameworkQueueTask = $config->getFrameworkQueueTask($this->config['frameworkQueue']);
			$this->queueTask = $config->getQueueTask($this->config['queue']);
			$this->queueHandler->registerFunction($this->frameworkQueueTask); //注册所有处理函数
		}

		#处理所在任务
		function work(){
			$this->queueHandler->work();
			$this->queueHandler->taskObj = $this;
			return true;
		}

		#任务运行
		static function  run($config, $daemon){
				try{
					$task = new self($config);		
					while($task->work() && $daemon->listen());
			//		$task->work() && $daemon->listen();
				}catch(Exception $e){
					spLog::Exception($e->getMessage());
				}
		}

		#处理任务数据
		function process($data){
			if($data && $data['method']){
				try{
					if(DM_DEBUG) DmLog::debug($data);
					if(false === $this->isLegalData($data)) return true;
					$callable = $this->import($data['method']);
					$ret = call_user_func_array($callable, array($data['data']));
					switch($ret){
						case DM_TASK_RET_SUCCESS:
						break;	
						case DM_TASK_RET_RETRY:
							if($data['times'] >= dmConfig('max_retry_num')){
								DmLog::failed($data);
							}else{
								$data['times'] ++;
								$result = dmTask($data['method'])->add($data, false);
							}
						break;
						case DM_TASK_RET_FAILED:
						default:	
							DmLog::failed($data);
						break;
					}
				}
				catch(DmException $e){
					DmLog::Exception($e->getMessage());
					$result = dmTask($data['method'])->add($data, false);
				}

			}else{
				sleep(2);
			}
			return true;
		}

		#是否为本进程的合法数据
		function isLegalData($data){
				$task = $data['method'];
				//查看是否为队列任务
				if(empty($this->queueTask)) return false;
				if(in_array($task, $this->queueTask)){
						//查看是否为本队列框架任务
						if(!in_array($task, $this->frameworkQueueTask)){
								dmTask($data['method'])->add($data, false);
								return false;
						}
						return true;
				}
				return false;
		}

		#打包任务数据
		function pack($task, $data){
				$result = array();	
				$result['method']	= $task;
				$result['data']		= $data;
				$result['times']	= 0;
				return $result;
		}

		#导入处理函数
		function import($method){
				static $pools = array();
				$method = strtolower($method);
				if(!array_key_exists($method, $pools)){
						if(file_exists($path = dmConfig('task_path').'/'.$this->config['framework'].'/'.$method.'.php')){
								require_once $path;
								if(function_exists($method.'_run')){
									$pools[$method] =  $method.'_run';
								}else if(class_exists($method)){
									$class = new $method;
									if(!method_exists($class, 'run')) dmException('class '.$method.' don\'t have a method run');
									$pools[$method] = array($class, 'run');
								}else{
									dmException('there must be a class '.$method.' has a method run  or a function '.$method.'_run defined ');
								}
						}else{
							dmException('file '.$method.'.php not exists');	
						}	
				}
				return $pools[$method];
		}
}


