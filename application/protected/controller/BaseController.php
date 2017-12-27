<?php
/** 
 * @author 
 * @version 
 */
class BaseController extends spController
{

    var $post = [];
    var $uid = 0;
    var $user_info = [];
    var $user_id = 0;
	/** 
	 * 初始化数据
	 */
	function  _initialize(){
        $input = trim(file_get_contents('php://input'));
        if(preg_match("/^[\[\{].+[\]\}]$/i", $input)){
                $this->post = json_decode($input, true);
        }
        //$this->post['token'] = '8f7cecfd06e1c339614c49f9f74a8713284efe10';
        $this->initUser();
        $this->__init();
	}

    function post($name, $default = NULL){
           if(is_null($this->post[$name])) return $default;
           return $this->post[$name];
    }
    
    function __init(){
    }

    /**
     *初始化用户
     */
    function initUser(){
            $user_info = [];
            if($token = (string)$this->post('token')){
                   $user_info  = model('UserWeixin')->find(['access_token'=>$token]);
            }
            if(empty($user_info) && TRUE == $this->needUserInfo()){
                    $this->error("需要重新登录", 1000);
            }
            $this->uid = $user_info['uid'];  
            $this->userid = $user_info['userid'];
            $this->user_info = $user_info;
    }

    #需要认证
    private function needUserInfo(){
            $c = strtolower(spConfig(spConfig('url_controller')));
            $a = strtolower(spConfig(spConfig('url_action')));
            $ignore_auth = spConfig('weixin_app.ignore_auth');
            if(in_array($c.'/*', $ignore_auth) || in_array($c.'/'.$a, $ignore_auth)) 
                return FALSE;
            return TRUE;
    }

	/**
	 *初始化用户信息
	 */
    /*
	public function initUser(){
		$this->user_type = spConfig('user_type'); 
		if(empty($_SESSION['mid']) && isset($_COOKIE['mid'])){
			$_SESSION['mid'] = (int)library('spEncript')->decrypt($_COOKIE['mid']);
		}
		if($_SESSION['mid']){
			$this->mid = $_SESSION['mid'];
			if(empty($_COOKIE['mid']))
				setcookie('mid', library('spEncript')->encrypt($_SESSION['mid']), time()+60*60*24, '/');
			if($_SESSION["userInfo"]){
				$this->userInfo = unserialize($_SESSION['userInfo']);
			}else{
				$this->userInfo = model('User')->getUserInfo($this->mid);
				unset($this->userInfo["passwd"]);
				$_SESSION['userInfo'] = serialize($this->userInfo);
			}	
			$this->assign('userInfo',$this->userInfo);
		}else{
			$this->mid = 0;
		}
		$this->assign('mid',$this->mid);
	}
    */

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
