<?php

class AuthModel{

        function getAccessToken(){
                if(($accessToken = $this->accessToken()) == ''){
                        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.spConfig('weixin.AppId').'&secret='.spConfig('weixin.AppSecret');
                        $curl = library('SpCurl');
                        $data = $curl->get($url);
                        $data = $curl->resp_body($data);
                        $accessToken = $this->accessToken($data);
                }
                return $accessToken;
        }

        private function accessToken($data = ''){
                $cache = library('SpRedis');
                $key = 'wx_access_token';
                if($data && is_string($data)) $data =  json_decode($data, true);
                if($data['access_token'] && $data['expires_in']){
                        $cache->set($key, $data['access_token'], 1800);
                        return $data['access_token'];
                }else{
                        return $cache->get($key);
                }
        }

        private function jsTicket($data = ''){
                $cache = library('SpRedis');
                $key = 'wx_js_ticket';
                if($data && is_string($data)) $data = json_decode($data, true);
                if($data['ticket'] && $data['expires_in']){
                        $cache->set($key, $data['ticket'], 1800);
                        return $data['ticket'];
                }else{
                        return $cache->get($key);
                }

        }

        function getJsTicket($times = 0){
                if($times < 5){
                        if(false == ($ticket = $this->jsTicket())){
                                $token = $this->getAccessToken();
                                $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$token.'&type=jsapi';
                                $curl = library('SpCurl');
                                $data = $curl->get($url);
                                $data = $curl->resp_body($data);
                                $ticket = $this->jsTicket($data);
                                if(empty($ticket)) return $this->getJsTicket($times + 1);
                        }
                }
                return $ticket;
        }

}
?>
