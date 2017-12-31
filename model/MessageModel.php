<?php

class MessageModel{
        //模板id
        private $template_id = 'MsalYAGxiY_-BzRgUHPzbdiONgAbhCR5f-14HLvHPqQ';
        private $config ;
        public function __construct(){
                $this->config = spConfig('message_template');
        }
#普通发消息，带重发机制
        function send($data){
                $ret = $this->__send($data);
                if($ret == false){
                        spDb('dc_message')->create(array('message'=>json_encode($data), 'times'=>1, 'status'=>0, 'ctime'=>time(), 'type'=>1, 'last_send_time'=>0));
                }
                return $ret;
        }

        function __send($data){
                if(empty($data)) return false;
                $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN';
                $url = str_replace('ACCESS_TOKEN', model('Auth')->getAccessToken(), $url);
                if(!is_string($data)) $json = json_encode($data);
                $ch =  curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                __log(array('url'=>$url, 'data'=>$json, 'ret'=>$result));
                $result = json_decode($result, true);
                $ret = $result['errcode'];
                if(0 == $ret) $ret = true;
                else $ret = false;
                return $ret;
        }


        function __send_kf($data, $send = false){
                if(false == $send){
                        $result = spDb('dc_message')->create(array('message'=>json_encode($data, JSON_UNESCAPED_UNICODE), 'times'=>1, 'status'=>0, 'type'=>2, 'ctime'=>time(), 'last_send_time'=>0));
                        return $result;
                }else{
                        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN';
                        $url = str_replace('ACCESS_TOKEN', model('Auth')->getAccessToken(), $url);
                        $curl = library('SpCurl');
                        $result = json_decode($curl->resp_body($curl->post($url, $data)), true);
                        if($result && 0 == $result["errcode"]) return true;
                        return false;
                }
        }


        function __send_kf($data, $send = false){
                if(false == $send){
                        $result = spDb('dc_message')->create(array('message'=>json_encode($data, JSON_UNESCAPED_UNICODE), 'times'=>1, 'status'=>0, 'type'=>2, 'ctime'=>time(), 'last_send_time'=>0));
                        return $result;
                }else{
                        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=ACCESS_TOKEN';
                        $url = str_replace('ACCESS_TOKEN', model('Auth')->getAccessToken(), $url);
                        $curl = library('SpCurl');
                        $result = json_decode($curl->resp_body($curl->post($url, $data)), true);
                        if($result && 0 == $result["errcode"]) return true;
                        return false;
                }
        }


        /**
         * 发货通知
         */
        public function delivery_release($info){
                //拼接数组
                $data = [];
                $data['touser'] = $info['do_openid']; //发给业务合伙人
                $data['template_id'] = $this->config['delivery_release'];
                $data['url'] = spConfig('weixin.URL').'/protected/order/lists?type=2';//'http://www.mmzuka.com';
                $data['topcolor'] = '#FF0000';
                $this->set($data['data'], 'first',"您好，订单号为：".$info['orderid']."的门窗产品已生产完成，等待确认发货。");
                if(!empty($info['send_date']))
                        $send_date = $info['send_date']>time()?$info['send_date']:time()+24*60*60;#如果发货时间小于当前时间则取当前时间
                else
                        $send_date = time()+24*60*60;
                $this->set($data['data'], 'keyword1', delayTime($info['send_date_info']));
                $this->set($data['data'], 'keyword2', "{$info['u_name']};电话：{$info['mobile']};地址:{$info['user_province']}{$info['user_city']}{$info['user_country']}{$info['u_address']}");
                $this->set($data['data'], 'remark', "请及时与客户确认发货时间！点击详情，进入“未安装”列表中查看此笔订单详情");
                $this->send($data);
        }

}
