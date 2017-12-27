<?php
require_once dirname(__FILE__).DS.'BaseController.php';
class UserController extends BaseController{
	#首页
	function indexAction(){
		$lists = service('User')->listPage($this->spArgs('page',1),20);
		$this->assign('lists', $lists);
		$this->display('index.php');
	}
	#添加操作
	function addAction(){
		$this->display('edit.php');
	}

	#编辑显示页面
	function editAction(){
		$id = $this->spArgs('id');
		$userInfo = service('User')->getUserById($id);
		$this->assign('user', $userInfo);
		$this->display('edit.php');
	}

	#保存操作
	function doSaveAction(){
		$data = $this->spArgs();
		if($data['uid']){
			$result = model('User')->updataAUser(array('uid'=>$data['uid']), $data);
			if($result)
				$this->success('更新成功！');
			else{
				$this->error('更新失败！');
			}
		}
		else {
			$result = service('User')->addOne($this->spArgs());
			if(is_numeric($result)) $this->success('添加成功');
			else $this->error($result[1]);
		}
	}

	function rightAction(){
		$uid	= $this->spArgs('id', $this->mid);
		Service('Right')->get($uid);
		$this->assign('uid', $uid);
		$this->assign('right', Service('Right'));
		$this->display('right.php');
	}

	function saveRightAction(){
		$rights = $this->spArgs('right');
		$uid	= $this->spArgs('uid', $this->mid);
		Service('Right')->get($uid);
		service('Right')->set(0);
		if($rights){
			foreach($rights as $right){
				service('Right')->add($right);
			}
		}else{
		}
		$this->success('权限保存成功');
	}

	function projectAction(){
		if(($uid = $this->spArgs('uid')) && ($cids = $this->spArgs('cids'))){
			 	$cids = join(',', $cids);	
				$result = model('User')->update(array('uid'=>$uid), array('cids'=>$cids));
				$this->success('修改成功！', spUrl('admin/User', 'project', array('id'=>$uid)));
		}else{
			$uid = $this->spArgs('id');
		}
		$this->assign('uid', $uid);
		$cids = explode(',', model('User')->getField('cids', array('uid'=>$uid)));
		$this->assign('cids', $cids);
		$hosts = model('HostsCategory')->getTreeAll();
		$this->assign('projects', $hosts);
		$this->display('project.html');
	}

	function deleteAction(){
		$uid = $this->spArgs('id');
		if(1 == $uid || empty($uid)) echo 0; //$this->error('用户id不正确');
		$result = model('User')->delete(array('uid'=>$uid));
		if($result) echo 1;
		else echo 0;
	}
}
?>
