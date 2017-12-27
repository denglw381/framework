<?php
class LanmuModel extends spModel{
	var $pk="id";
	var $table="lanmu";
	function getOne($id){
		return $this->find(array('id'=>$id));
	}

	function listPage($condition = NULL, $page = 1, $pageSize = 20){
		$result = array();
		$result['data'] = $this->spPager($page, $pageSize)->findAll($condition);
		$result['html'] = $this->spPager()->getPagerStr();
		return $result;
	}

	function addOne($arr){
		$arr['ctime'] = time();
		$id = $this->create($arr);
		$this->updateOne(array('id'=>$id),array('order'=>$id));
		return $id;
	}

	function updateOne($condition, $data){
		$data['ctime'] = time();
		return $this->update($condition, $data);
	}

	function getAll($condition = null){
		return $this->findAll($condition);
	}

}
			
