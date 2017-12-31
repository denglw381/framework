<?php
/** 
 * @author 
 * @version 
 */
class AuthController extends BaseController
{
	
	#获取主记录总数
	function saveAction(){
            //__log($this->post);
            echo 'aaaaaabbb';
	}

    function codeAction(){
            $code = $this->post['code'];
            //__log($code);
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code";
            $weixin_app_config = spConfig('weixin_app');
            $url = str_replace(["APPID", "SECRET", "JSCODE"], [$weixin_app_config['appid'], $weixin_app_config['appsecret'], $code], $url);
            $curl = library('SpCurl');
            $data = json_decode($curl->resp_body($curl->get($url)), true);
            if($info = model('UserWeixin')->find(['openid'=>$data['openid']])){
                    $this->success($info);
            }
            $data['access_token'] = sha1($data['openid'].rand(100,999));
            model('UserWeixin')->replace(['openid'=>$data['openid']], $data);
            $this->success($data);
    }

    function indexAction(){
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code';
            $code =  $this->spArgs('code');
            $url = str_replace(array('APPID', 'SECRET', 'CODE'), array(spConfig('weixin.AppId'), spConfig('weixin.AppSecret'), $code), $url);
            $curl = library('SpCurl');
            $ret = $curl->get($url);
            $data = json_decode($curl->resp_body($ret), true);
            $userInfo = array();
            if($data['scope'] == 'snsapi_userinfo'){
                    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN';
                    $url = str_replace(array('ACCESS_TOKEN', 'OPENID'), array($data['access_token'], $data['openid']), $url);
                    $userInfo = json_decode($curl->resp_body($curl->get($url)), true);
            }
            //if($userInfo && $userInfo['nickname']){
            if(is_array($data) && $data['openid']) $userInfo = array_merge($userInfo, $data);
            model('User')->save($userInfo);
            //}
            model('User')->login($data['openid']);
            $this->jump($_SESSION['_current_url'], 0, array('__openid'=>$data['openid']));
    }

    function cookieAction(){
            setcookie('wx_openid', $openid, time()-spConfig('cookie.expire_time'));
            setcookie('__wx_openid', $openid, time()-spConfig('cookie.expire_time'));
    }

    function testAction(){
            spLog::debug('___'.var_Export($openid, true));
            spLog::debug($_SERVER['SERVER_NAME']);
            spLog::debug($_COOKIE);
            spLog::debug($_SESSION);
    }

}
