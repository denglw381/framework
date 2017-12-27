<?php
class SpTaskTest extends SpTest{
	var $obj;
	public function _init_(){
		//$this->obj = new SpConfig();
	}

	public function testTaskStart(){
	}

	public function testgetProcess(){
//	'a:3:{s:14:"frameworkQueue";s:12:"ci_gearman_1";s:5:"queue";s:9:"gearman_1";s:9:"framework";s:2:"ci";} ';
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add(array('message'=>'aaaaaaaaaaa', 'type'=>'abscd')));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('ci_message')->add('aaaaaaaaaaa'));
//		var_dump(spTask('message')->add(array('message'=>'aaaaaaaaaaa', 'type'=>'abscd')));
//		var_dump(spTask('message')->add('aaaaaaaaaaa'));
		while(1){
			spTask('ci_message')->add(array('aa','bb','c'));
			spTask('ci_message')->add(array('aa','bb','c'));
			spTask('ms_media')->add(array('aa','bb','c'));
			spTask('ci_message')->add(array('aa','bb','c'));
			spTask('ci_message')->add(array('aa','bb','c'));
			spTask('ts_message')->add(array('aa','bb','c'));
			sleep(1);
		}
//		var_dump(spTask('message')->add('aaaaaaaaaaa'));
		//SpTask::run(array('frameworkQueue'=>'ci_gearman_1', 'queue'=>'gearman_1', 'framework'=>'ci'), new testDeamon);
		//SpTask::run(array('frameworkQueue'=>'ci_redis_1', 'queue'=>'redis_1', 'framework'=>'ci'), new testDeamon);
//		SpTask::run(array('frameworkQueue'=>'ci_memcacheqd_1', 'queue'=>'memcacheqd_1', 'framework'=>'ci'), new testDeamon);
//		var_dump($this->obj->getProccess());
	}

	public function testGetFrameworkQueueTask(){
		//var_dump($this->obj->getFrameworkQueueTask());
	}

	public function testGetTaskNames(){
		//var_dump($this->obj->getAllTaskName());
	}

	public function testGetQueueTask(){
//		var_dump($this->obj->getQueueTask('gearman_1'));
	}
}

class testDeamon{
	function listen(){
		return true;
	}
}
