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
            echo 'aaaa';
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
}
