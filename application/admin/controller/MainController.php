<?php
require_once dirname(__FILE__).DS.'BaseController.php';
class MainController extends BaseController{
	function indexAction(){
		$this->display();
	}

	function topAction(){
		$topLanmus = array();
		$Lanmus = service('Lanmu')->getAll(array('location'=>0));
		foreach($Lanmus as $lanmu){
			if(model('LanmuRight')->find(array('tid'=>$lanmu['id'],	'status'=>1, 'uid'=>$this->mid))){
				$topLanmus[] = $lanmu;
			}
		}
		$this->assign('topLanmus', $topLanmus);
		$this->display();
	}

	function leftAction(){
		$datas = service('Lanmu')->getLeftInfo($this->spArgs('id',null), $this->mid);
		$this->assign('datas', $datas);
		$this->display("left.php");
	}

	function rightAction(){
		$this->display("right.php");
	}

	function untitledAction(){
	}

	function loginAction(){
		if($this->mid) $this->jump(spurl('admin/main/index'));
		$this->display();
	}

	function doLoginAction(){
		if($this->mid) $this->jump(spurl('admin/main/index'));
		$uname = $this->spArgs('uname');	
		$passwd = $this->spArgs('passwd');
		$result = service('User')->login($uname, $passwd);
		if(is_array($result) && $result[0]==0 )
			$result = array('1','登录成功!');
		echo json_encode($result);
	}

	function loginOutAction(){
		service('User')->loginOut();
		$this->jump(spurl('admin/main/login'));
	}

	function changePwdAction(){
		$this->display('changePwd.php');
	}

	function doChangePwdAction(){
		$oldPwd = $this->spArgs('oldPwd');
		$newPwd	= $this->spArgs('newPwd');
		$newPwd1= $this->spArgs('newPwd1');
		if(empty($oldPwd) or empty($oldPwd) or empty($newPwd1)){
			$this->error('数据输入错误');
		}
		if($newPwd  != $newPwd1){
			$this->error('新密码两次输入不一致!');
			return;
		}
	        $result = service('User')->changePwd($this->mid, $newPwd, $oldPwd);	
		if($result) $this->success('密码修改成功!');
		else $this->error('密码修改失败!');
	}

	function testAction(){
		$this->display('test.html');
	}
}
?>
