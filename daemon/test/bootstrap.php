<?php
define('DM_CLIENT', true);
require dirname(__FILE__).'/../lib/SpStart.php';
//web端api基类
class SpTest extends PHPUnit_Framework_TestCase{
	function setUp(){
		parent::setUp();
		$this->_init_();
	}	

	function _init_(){
	}
}

