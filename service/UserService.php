<?php
class UserService extends spService{
	var $userDao = '';
	
	function _initialize(){
		$this->userDao = model('User');
	}

	function login($uname, $passwd){
		return $this->userDao->login($uname, $passwd);
	}

	function loginOut(){
		return $this->userDao->loginOut();
	}

	function changePwd($uid, $newPwd, $oldPwd = NULL){
		if(empty($newPwd)) return false;
		$userInfo = $this->userDao->getUserInfo($uid);
		if($oldPwd){
			$passwd = $this->userDao->encodePwd($oldPwd);
			if( $passwd != $userInfo['passwd']){
				return false;
			}
		}
		return $this->userDao->updataAUser(array('uid'=>$uid), array('passwd'=>$newPwd));
	}

	function listPage($page = 1, $pageSize = 20){
		return $this->userDao->listPage($page, $pageSize);
	}
	
	function getUserById($id){
		return $this->userDao->find(array('uid'=>$id));
	}

	function addOne($arr){
		return $this->userDao->addAUser($arr);
	}

	function getOne($id){
		return $this->userDao->getUserInfo($id);
	}
}
