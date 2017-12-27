<?php
require dirname(__FILE__).'/SpQueueInterface.php';
class SpQueueMemcacheq implements SpQueueInterface{
	var $queue;
	static $instance;
	function __construct(){
		$this->queue = new Memcached;
		$this->queue->addServer('127.0.0.1','44003');
	}

	static function getInstance(){
		if(self::$instance instanceof self){
		}else{
			self::$instance = new SpQueueMemcacheq;
		}
		return self::$instance;
	}	

	function push($queueNo, $data){
		return $this->queue->set($queueNo, $data);
	}

	function get($queueNo){
		return $this->queue->get($queueNo);
	}

	function pop(){
	}
}

/*
SpQueueMemcacheq::getInstance()->push(1,'1sfasdfasdf');
SpQueueMemcacheq::getInstance()->push(1,'2sfasdfasdf');
SpQueueMemcacheq::getInstance()->push(1,'3sfasdfasdf');
SpQueueMemcacheq::getInstance()->push(1,'4sfasdfasdsssf');
SpQueueMemcacheq::getInstance()->push(1,'5sfasdfsssasdf');
SpQueueMemcacheq::getInstance()->push(1,'6ssssddsfasdfasdf');
 */
//echo SpQueueMemcacheq::getInstance()->pop(1);

