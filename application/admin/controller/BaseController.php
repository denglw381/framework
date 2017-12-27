<?php
class BaseController extends spController{
	function _initialize(){
        $this->initUser();
		$this->checkRole();
		$this->checkRight();//检查权限
		$this->assign('userInfo', $this->userInfo);
	}
    
    function initUser(){
        $this->mid = model('User')->getLoginUid();
        if($this->mid) $this->userInfo = model('User')->getUserInfo($this->mid);
    }

	function checkRole(){
		if($this->exludeCheckUser()) return;
		if(empty($this->mid) or $this->userInfo['role']!=1){
			$this->jump(spUrl('admin/main/login'));
		}
	}

	function checkRight(){
	}

	private function exludeCheckUser(){
		$map = array(
			array('main', 'login'),
			array('main', 'doLogin'),
			array('main', 'loginOut'),
		);
		foreach($map as $val){
			if((spConfig(spConfig('url_action')) == $val[1]) && (spConfig(spConfig('url_controller')) == $val[0])) return true;
		}
		return false;
	}

	function _init(){
	}


    /**
     *成功返回的信息
     */
    public function success($info, $code = 0){
            if(empty($info)) $info = new stdClass;
            $result = ['code'=>$code, 'data'=>$info];
            spRecordRequest(['return'=>$result]);
            echo json_encode($result);
            exit();
    }


    /**
     *错误返回的信息
     */
    public function error($info, $code = -1){
            if(empty($info)) $info = new stdClass;
            $result = ['code'=>$code, 'data'=>$info];
            spRecordRequest(['return'=>$result]);
            echo json_encode($result);
            exit();
    }

}
?>
