<?php
require_once dirname(__FILE__).DS.'BaseController.php';
/** 
 * @author 邓先生 email:nenu_1@126.com
 * @version 1.0
 */
class AgentController extends BaseController
{
       var $model;

    function _init(){
		$this->model=model('Agent'); 
	}

	function indexAction(){
		$this->assign('syncs', spConfig('agent_method'));
		$page = $this->spArgs('p', 1);
		$pageSize = 20;
		$lists['data'] = $this->model->spPager($page, $pageSize)->findAll();
		$lists['html'] = $this->model->spPager()->getPagerStr();
		$this->assign('lists', $lists);
		$this->display("index.php");
	}

	function addAction(){
		$this->assign('syncs', spConfig('agent_method'));
		$this->display("edit.php");
	}

	function editAction(){
		if($id = $this->spArgs('id')){ 
			$data = $this->model->load($id); 
			$this->assign('data', $data);
		}
		$this->assign('syncs', spConfig('agent_method'));
		$this->display("edit.php");
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
			$result = $this->model->updateOne(array("id"=>intval($data['id'])),$data);
		}else
			$result = $this->model->addOne($data);
		if($result){$this->success("保存成功！");}
		else $this->error("保存失败！");
	}

	function searchAction(){
		$keyword = $this->spArgs('keyword');
		if(empty($keyword)) $this->error('搜索关键字不能为空');
		$sql = "select * from ".spConfig('db.prefix').agent." where name like '%".$keyword."%'";
		$lists = $this->model->findPageBySql($sql);
		$this->assign('keyword', $keyword);
		$this->assign('lists', $lists);
		$this->display("index.php");
	}

	function filterData($data){
		return $data;
	}
}
