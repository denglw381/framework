<?php
define('SPQUEUE', 'SpQueueDb');//db队列
if(!defined('SP_PRIPORITY'))
define('SP_PRIPORITY', '0,10');//优先级,3级

interface SpQueueInterface{
	public static function getInstance();
	public function push($queueNo, $data);
	public function get($queueNo);
	public function pop();
}

