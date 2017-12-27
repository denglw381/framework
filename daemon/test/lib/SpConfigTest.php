<?php
class SpConfigTest extends SpTest{
	var $obj;
	public function _init_(){
		$this->obj = new SpConfig();
	}

	public function testCheck(){
		var_dump($this->obj->check());
	}

	public function testgetProcess(){
//		var_dump($this->obj->getProccess());
	}

	public function testGetFrameworkQueueTask($frameworkQueue){
		//var_dump($this->obj->getFrameworkQueueTask());
	}

	public function testGetTaskNames(){
		//var_dump($this->obj->getAllTaskName());
	}

	public function testGetQueueTask(){
//		var_dump($this->obj->getQueueTask('gearman_1'));
	}
}
