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

    #需要认证
    private function needAuth(){
            $c = strtolower(spConfig(spConfig('url_controller')));
            $a = strtolower(spConfig(spConfig('url_action')));
            if(in_array($c.'/*', spConfig('no_auth')) || in_array($c.'/'.$a, spConfig('no_auth'))) return false;
            return true;
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


    #获取用户数据
    private function getUserInfo($needAuth = True){
            $needUserInfo = $this->needUserInfo();
            if($uid = spConfig('debug_uid')){
                    $this->userInfo = model('User')->load($uid);
            }else{
                    $this->userInfo = model('User')->getCurrentUserInfo($needUserInfo, $needAuth);
            }
            $this->openid = strval($this->userInfo['openid']);
            $this->uid  = intval($this->userInfo['uid']);
            spLog::_info('dcuid', $this->uid);
            if($this->uid) model('User')->update(array('uid'=>$this->uid), array('last_visit_time'=>time()));
    }



    #分发处理消息
    private function dispatchMsg(){
            $msg = file_get_contents('php://input');
            $xml = simplexml_load_string($msg, null, LIBXML_NOCDATA);
            if(empty($xml)) return false;
            $data = (array)$xml;
            if(empty($data)) return false;
            $this->openid = $data['FromUserName']; //设置openid
            $this->userInfo = model('User')->getUserInfo($this->openid);
            $this->uid = $this->userInfo['uid'];
            service('Msg')->handle($data);
            return true;
   }


    #检查签名
    function checkSignature()
    {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];

            $signature = $this->spArgs('signature');
            $timestamp = $this->spArgs('timestamp');
            $nonce = $this->spArgs('nonce');

            $token = spConfig('weixin.Token');
            $tmpArr = array($token, $timestamp, $nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode( $tmpArr );
            $tmpStr = sha1( $tmpStr );
            if( $tmpStr == $signature ){
                    return true;
            }else{
                    return false;
            }
    }



   function getAccessToken(){
            return model('Auth')->getAccessToken();
   }


   function getJsTicket(){
            return model('Auth')->getJsTicket();
   }
}
