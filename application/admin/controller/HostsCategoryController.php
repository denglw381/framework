<?php
/** 
 * @author 邓先生 email:nenu_1@126.com
 * @version 1.0
 */
class HostsCategoryController extends spController
{
       var $model;

    function _initialize(){
		$this->model=model('HostsCategory'); 
	}

	function indexAction(){
		$lists = $this->model->getList('pid = 0');
		$this->assign('lists', $lists);
		$this->display("index.php");
	}

	function addAction(){
		$data['pid'] = 0;
		$data['status'] = 1;
		$this->assign('data', $data);
		$this->display("edit.php");
	}

	function editAction(){
			if($id = $this->spArgs('id')){ 
					$data = $this->model->find(array('id'=>$id)); 
					$this->assign('data', $data);
			}		$this->display("edit.php");
	}

	function deleteAction(){
		$id = $this->spArgs("id");
		$result	= $this->model->delete(array("id"=>$id));
		if($result) $this->success("删除成功");
	}

	function saveAction(){
		$data = $this->spArgs();
		$data = $this->filterData($data);
		if(intval($data['id'])){
			$result = $this->model->update(array("id"=>intval($data['id'])),$data);
		}else
			$result = $this->model->create($data);
		if($result){$this->success("保存成功！");}
		else $this->error("保存失败！");
	}

	function searchAction(){
		$keyword = $this->spArgs('keyword');
		if(empty($keyword)) $this->error('搜索关键字不能为空');
		$sql = "select * from ".spConfig('db.prefix').hosts_category." where id like '%".$keyword."%'";
		$lists = $this->model->findPageBySql($sql);
		$this->assign('keyword', $keyword);
		$this->assign('lists', $lists);
		$this->display("index.php");
	}

	function filterData($data){
		return $data;
	}
}
