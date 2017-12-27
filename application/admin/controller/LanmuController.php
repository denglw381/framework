<?php
require_once dirname(__FILE__).DS.'BaseController.php';
class LanmuController extends BaseController{
	function indexAction(){
		$condition = array('tid'=>0, 'pid'=>0);
		if($id = $this->spArgs('id')){
			$condition = $this->getTidAndPid($id);
			$this->assign('location', $condition);
		}
		$lists = service('Lanmu')->listPage($condition);
		$this->assign('id', $id?$id:0);
		$this->assign('lists', $lists);
		$this->display("index.php");
	}

	function getTidAndPid($id){
		return service('Lanmu')->tidyIds($id);
	}

	function rightAction(){
		$uid =  $this->spArgs('uid', $this->mid);
		$lanmus = service('Lanmu')->getAll(array('tid'=>0));
		foreach($lanmus as $k=>$lm){
			$lanmus[$k]['llms'] = service('lanmu')->getAll(array('tid'=>$lm['id'],'lid'=>0));
			foreach($lanmus[$k]['llms'] as $key=>$val){
				$lanmus[$k]['llms'][$key]['sons'] = service('lanmu')->getAll(array('tid'=>$lm['id'],'lid'=>$val['id']));
				foreach($lanmus[$k]['llms'][$key]['sons'] as &$sons){
					$sons['hasRight'] = (bool)model('LanmuRight')->find(array('status'=>1, 'sid'=>$sons['id'], 'uid'=>$uid));
				}
			}
		}
		$this->assign('userInfo', service('User')->getOne($uid));
		$this->assign('datas', $lanmus);
		$this->assign('uid', $uid);
		$this->display("right.php");
	}

	function saveRightAction(){
		$rights = $this->spArgs('right');
		$uid	= $this->spArgs('uid');
		model('LanmuRight')->delete(array('uid'=>$uid));
		foreach($rights as $right){
			list($arr['tid'],$arr['lid'], $arr['sid']) = explode(',', $right); 
			$arr['uid'] = $uid;
			model('LanmuRight')->addOne($arr);
		}
		$this->success('添加成功');
	}

	function addAction(){
		$this->assign('pid', $this->spArgs('pid',0));
		$this->display('edit.php');
	}
	
	function editAction(){
		$data = service('Lanmu')->getOne($this->spArgs('id'));
		$this->assign('data', $data);
		$this->display('edit.php');
	}

	function saveAction(){
		if($id = $this->spArgs('id')){
			service('Lanmu')->updateOne(array('id'=>$id), $_POST);
			$this->success("更新成功");
		}else{
			$arr = $_POST;
			$otherInfo = array();
			if($pid = $this->spArgs('pid')){
				$otherInfo = $this->getTidAndPid($pid);
			}
			$arr = array_merge($arr, $otherInfo?$otherInfo:array());
			service('Lanmu')->addOne($arr);
			$this->success('添加成功');
		}
	}
}
?>
