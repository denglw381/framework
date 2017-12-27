<?php
#进程控制类
class DmDaemon{
	private $pidfile  = '';  //守护进程pid的存储文件

	private $spawnList = array(); //需要启动的进程
	private $childList = array(); //已存在的进程

	//当子进程退出时，会触发该函数
	private function sigHandler($sig) {
		switch($sig) {
			case SIGINT:
			posix_kill(0, SIGINT); //stop all children proccess
			exit(0);
			break;
		}
	}

	private	function setSignalHandlers(){
		//注册子进程退出时调用的函数
		pcntl_signal(SIGINT, array($this, 'sigHandler'));
	}

#初始化配置
	function initGlobals(){
		$options = getopt("p:");
		$config = new DmConfig();
		if(false === $config->check()){
				exit(0);
		}
		$this->spawnList = $config->getProccess(); //获取所有进程配置
		unset($config);
		$this->pidfile = $options['p'];
		$this->max = count($this->spawnList);
		$this->setSignalHandlers();
	}

#进程启动入口函数
	static function run(){
			$daemon = new self;
			$daemon->initGlobals();
			if(dmConfig('daemon'))
				$daemon->startDaemon();
			else $daemon->startNormal();
	}

#正常启动进程
	function startNormal(){
			posix_setuid(dmConfig('puid'));
			#初始化所有进程
			while($config = array_pop($this->spawnList)){
				$this->spawn($config);
			}
			#等待子进程退出
			while($this->listen()){
					sleep(1);
					$cid = pcntl_waitpid(0, $status, WNOHANG);
					if($cid > 0) {
							$this->spawnList[] = $this->childList[$cid];
							DmLog::daemon(serialize($this->childList[$cid]).' | pid:'.$cid.' has exit !');
							unset($this->childList[$cid]);
							while($config = array_pop($this->spawnList))
									$this->spawn($config);
					}else{
							continue;
					}
			}	
	}

#守护进程的方式启动进程
	function startDaemon(){
			$pid = pcntl_fork();
			if($pid == 0) {
				if($this->pidfile) file_put_contents($this->pidfile, posix_getpid());//存储进程id
				$this->startNormal();
			}else{
					exit(0);
			}
	}

#启动子进程
	function spawn($config){
			$pid = pcntl_fork();
			if($pid == 0){
					DmTask::run($config, $this); //任务开始启动
					exit(0);
			}else{
				$this->childList[$pid] = $config;
				DmLog::daemon(serialize($config).' | pid:'.$pid.' bring up !');
			}
	}

#监听信号
	function listen(){
		pcntl_signal_dispatch();
		return true;
	}
}

