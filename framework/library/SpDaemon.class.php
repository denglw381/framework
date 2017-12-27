<?php
require dirname(__FILE__).'/SpQueueDb.class.php';

class SpDaemon{
	//最大的子进程数量
	static $max = 1;
	//当前的子进程数量
	static $child = 0;
	static $pids  = array(); 

	//当子进程退出时，会触发该函数
	static function sigHandler($sig) {
		switch($sig) {
		case SIGCHLD:
			self::$child--;
			break;
		case SIGUSR1:
			break;
		case SIGTERM:
			break;
		case SIGALRM:
			break;
		}
	}

	static function setSignalHandlers(){
		//注册子进程退出时调用的函数
		pcntl_signal(SIGCHLD, array('SpDaemon','sigHandler'));
		pcntl_signal(SIGUSR1, array('SpDaemon','sigHandler'), false);
		pcntl_signal(SIGTERM, array('SpDaemon','sigHandler'));
		pcntl_signal(SIGALRM, array('SpDaemon','sigHandler'));
	}

	static function run(){
		//配合pcntl_signal使用
		declare(ticks=1);
		self::setSignalHandlers();
		$pid = pcntl_fork();
		if($pid == 0) {
			while(true){
				if (self::$child >= self::$max) {
					pcntl_wait($status);
				}else{
					self::$child ++;
					self::spawn();
				}
			}	
		}else{
			exit(0);
		}

	}

	static function spawn(){
		$pid = pcntl_fork();
		if($pid == 0){
			while(true){
				usleep(100);
				self::listen();
//				file_put_contents(dirname(__FILE__).'/img/'.microtime(true).'.png',file_get_contents('http://denglianwen.deving.zhisland.com/index.php?app=home&mod=public&act=verify'));
		//		SpTask::getInstance()->run();
				SpQueueDb::getInstance()->push(array(rand(1,100)));
			}
			exit(0);
		}
	}

	static function listen(){
		if(posix_getppid()==1) exit(0);
	}
}

SpDaemon::run();
