<?php
class LanmuRightModel extends spModel{
	var $pk="id";
	var $table="lanmu_right";

	function addOne($arr){
		$arr['status'] = 1 ;
		return $this->create($arr);
	}
}

