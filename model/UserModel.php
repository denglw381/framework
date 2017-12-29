<?php
class UserModel extends spModel{
	var $pk="userid";
	var $table="user";

	var $r = 0;

	function _initialize(){

	}

	/** 
	 * 检查权限 
	 * 
	 * @return 
	 */
	function checkRight(){

	}

	/** 
	 * 添加一个用户 
	 *  
	 * @param $arr 用户数据
	 * @return 
	 */
	function addAUser($arr){
		if($this->getUserInfo($arr['uname'],"uname")){
			return array(-4,"用户已存在");
		}else{
			$arr["passwd"] = $this->encodePwd($arr["passwd"]);
			$arr["ctime"]  = time();
			if(empty($arr["email"])) $arr["email"] = "noEmail" ;
			$arr["mtime"]  = 0;
			$arr["role"]   = in_array(array(1,2), $arr['role'])? 1 : 2;
			return	$this->create ($arr);
		}	
	}

	/** 
	 * 修改一个用户 
	 * 
	 * @param $condition,$arr 
	 * @param $arr 
	 * @return 
	 */
	function updataAUser($condition,$arr){
		$arr["mtime"] = time();
		if(array_key_exists("passwd",$arr)){
			$arr["passwd"]= $this->encodePwd($arr["passwd"]);
		}
		return $this->update( $condition, $arr);		
	}

	/** 
	 * 加密数据 
	 * 
	 * @param $pwd 
	 * @return 
	 */
	function encodePwd($pwd){
		return md5($pwd.md5($pwd));

	}

	/** 
	 * 登录操作 
	 */
	function login($uname,$passwd){
	//	$this->updataAUser(array("uname"=>$uname),array("passwd"=>$passwd));
		$max_login_wrong_times = spConfig('user.max_login_wrong_times');
		$login_wrong_forbid_time = spConfig('user.login_wrong_forbid_time');
		$userInfo = $this->getUserInfo($uname , "uname"); 
		if(empty($userInfo)){
			return array("-1","用户不存在");
		}
		if($userInfo['fails'] >= $max_login_wrong_times){
			if(time()-$userInfo['login_time'] < $login_wrong_forbid_time){
				return array('-3', array('对不起，您的登陆失败次数过多，请于'.friendlyLeftTime($login_wrong_forbid_time-(time()-$userInfo['login_time'])).'后再登陆'));
			}
			else{
				$this->updateField(array('uname'=>$uname), 'fails', 0);
				$userInfo['fails'] = 0;
			}
		}
		$this->updateField(array('uname'=>$uname), 'login_time', time());
		$passwd   = $this->encodePwd($passwd);
		if( $passwd != $userInfo["passwd"]){
			$fails = $userInfo['fails'] + 1;
			$this->incrField(array('uname'=>$uname), 'fails');
			$left_times = spConfig('user.max_login_wrong_times')-$fails;
			$left_times = $left_times > 0 ? $left_times : 0;
			if($left_times > 0)
				return array("-2","用户密码错误, 您还有".$left_times."登陆机会");
			else 
				return array("-4", array('对不起，您的登陆失败次数过多，请于'.friendlyLeftTime($login_wrong_forbid_time).'后再登陆'));
		}
		$this->updateField(array('uname'=>$uname), 'fails', 0);
		$_SESSION['mid'] = $userInfo["userid"];
		setcookie('mid', library('spEncript')->encrypt($_SESSION['mid']), time()+spConfig('cookie.expire_time'), '/');
		return array(0, $userInfo['userid']);
	}

    function getLoginUid(){
         $mid = $_COOKIE['mid'];
         $mid = library('spEncript')->decrypt($mid);
         return $mid;
    }

	/** 
	 * 获取用户信息 
	 */
	function getUserInfo($value,$field="userid"){
		$info =  $this->find([$field=>$value]);	
        return $info;
	}

	/** 
	 *退出登录  
	 */
	function loginOut(){
		unset($_SESSION['mid']);
		unset($_SESSION['userInfo']);
		setcookie('mid', 0, time() - spConfig('cookie.expire_time'), '/');
		setcookie('syncId', 0, time() - spConfig('cookie.expire_time'), '/');
		session_destroy();
	}

	function listPage($page = 1, $pageSize = 20){
		$result = array();
		$result['data'] = $this->spPager($page, $pageSize)->findAll();
		$result['html'] = $this->spPager()->getPagerStr();
		return $result;
	}

	function setSyncId($userid, $syncId){
		return $this->updateField(array('userid'=>$userid), 'sync_id', $syncId);
	}

	function getSyncId($userid){
		return $this->getField('sync_id', array('userid'=>$userid));	
	}

	function isAdmin($userid){
		$userInfo = $this->getField('role', 'userid = '.$userid);
		return $userInfo['role'] == 1 ? true : false;
	}

	function getCids($userid){
		return $this->getField('cids', 'userid = '.$userid);
	}
}


