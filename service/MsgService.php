<?php
class MsgService{
        #消息处理入口
        function handle($data){
                spLog::debug($data);
                $this->setUserInfo($data);
                switch($data['MsgType']){
                        case 'event':
                                $this->handleEvent($data);
                                break;
            case 'text':
                $ret =  '<xml>
                            <ToUserName>'.$data["FromUserName"].'</ToUserName>
                            <FromUserName>'.$data["ToUserName"].'</FromUserName>
                            <CreateTime>'.time().'</CreateTime>
                            <MsgType>transfer_customer_service</MsgType>
                        </xml>';
                $ret = preg_replace('/>\s*</', '><', $ret);
                spLog::_info('ret',$ret);
                echo trim(strval($ret));
                break;
                }
                echo '';
        }

        #关注了微信号的用户才能以此种方式获取用户信息
        function setUserInfo($data){
        if($info = model('User')->find(array('openid'=>$data['FromUserName'])) && $info['nick_name'])  return;
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN';
                $access_token = model('Auth')->getAccessToken();
                $url = str_replace(array('ACCESS_TOKEN', 'OPENID'), array($access_token, $data['FromUserName']), $url);
                $curl = library('SpCurl');
                $ret = $curl->get($url);
                $data = json_decode($curl->resp_body($ret), true);
                if(isset($data['money'])) unset($data['money']);
                if($data && $data['openid']) model('User')->save($data);
                return true;
        }

        #处理事件
        function handleEvent($data){
                switch($data['Event']){
            case 'SCAN':
               if($data['EventKey']){
                __log("scan:".$data['EventKey']);
                    if($data['EventKey'] < 100000000){
                        $from_uid = $data["EventKey"];
                        $userInfo = model('User')->find(array('openid'=>$data['FromUserName']));
                        if($userInfo && $userInfo['uid'] != $from_uid){
                                $_data = array('openid'=>$data['FromUserName']);
                                if(empty($userInfo['from_uid'])) $_data['from_uid'] = $from_uid;
                                model('User')->save($_data);
                        }
                        if($_data['from_uid'])
                                model('User')->countExpand($data['FromUserName']);//统计业务员推广结果
                    }else{
                        //用户扫码评论
                        $orderid = $data['EventKey']-100000000;
                        __log("orderid : ".$orderid);
                        $openid = $data['FromUserName'];
                        $uid = model('User')->getUserInfo($openid)['uid'];
                        // 如果是业务合伙人扫码,不分配订单
                        if(model('User')->isPartner($uid)) break;
                        $orderInfo = model("OrderList")->load($orderid);
                        $res = model('OrderList')->modify($orderid,array('isown'=>$orderInfo['uid'],'uid'=>$uid,'uptime'=>time(),'client_pay_status'=>1));
                        if(!$res){
                            __log("reassing order failed info:orderid=".$orderid." time =".time());
                        }else{
                            model("Message")->commentNotify($openid);
                        }
                    }

                        }
                    }


                }
            break;
                        case 'LOCATION':
                                $_data = array('openid'=>$data['FromUserName'], 'lat'=>$data['Latitude'], 'lng'=>$data['Longitude']);
                                if($_data['lat'] && $_data['lng']){
                                        $address = get_city_bylatlng($_data['lng'],$_data['lat'],'wgs84ll');
                                        $address = $address['result'];
                                        // spLog::debug($address);
                                        $_data['bd_lat'] = $address['location']['lat'];
                                        $_data['bd_lng'] = $address['location']['lng'];
                                        $area = model('Area')->find(array('name'=>$address['addressComponent']['district']));
                                        if($area) $_data['area_code'] = $area['code'];
                                }
                                return model('User')->save($_data);
                                break;
                        case 'unsubscribe':
                                $_data = array('subscribe'=>0, 'openid'=>$data['FromUserName']);
                                return model('User')->save($_data);
                                break;

                        case 'subscribe':
                                $_data = array('subscribe'=>1, 'openid'=>$data['FromUserName']);
                if($data['EventKey']){
                        list($qrscene, $from_uid) = explode('_', $data["EventKey"]);
                        if($qrscene == 'qrscene'){
                            if($from_uid < 100000000){
                                $userInfo = model('User')->find(array('openid'=>$data['FromUserName']));
                                if(empty($userInfo['from_uid'])) $_data['from_uid'] = $from_uid;
                            }else{
                                $orderid = $from_uid-100000000;
                                $openid = $data['FromUserName'];
                                $uid = model('User')->getUserInfo($openid)['uid'];
                                // 如果是业务合伙人扫码,不分配订单
                                if(model('User')->isPartner($uid)) break;
                                $orderInfo = model("OrderList")->load($orderid);
                                $res = model('OrderList')->modify($orderid,array('isown'=>$orderInfo['uid'],'uid'=>$uid,'uptime'=>time(),'client_pay_status'=>1));
                                if(!$res){
                                    __log("reassing order failed info:orderid=".$orderid." time =".time());
                                }else{
                                    model("Message")->commentNotify($openid);
                                }
                            }
                        }

                }
                                model('User')->save($_data);
                if($_data['from_uid'])
                    model('User')->countExpand($data['FromUserName']);//统计业务员推广结果
                $msgObj = new stdClass();
                $msgObj->FromUserName = $data['FromUserName'];
                $msgObj->ToUserName = $data['ToUserName'];
                $msg = '欢迎您来到东窗微信平台，我们为您提供一站式门窗、阳台、阳光房等产品的选购、安装以及售后服务。请点击下方“东窗首页”链接，开始了解和购买东窗产品。';
                $ret = $this->transmitText($msgObj,$msg);
             //   $this->sendTu($data);
                echo $ret;
                                break;
            case 'CLICK':
                $eventKey = $data['EventKey'];
                $msgObj = new stdClass();
                $msgObj->FromUserName = $data['FromUserName'];
                $msgObj->ToUserName = $data['ToUserName'];
                switch ($eventKey) {
                    case 'dcweb':
                        $msg = '敬请期待东窗商城4月6日正式发布，谢谢支持！';
                        $ret = $this->transmitText($msgObj,$msg);
                        echo $ret;
                        break;
                        echo $ret;
                        break;
                    case 'service':
                        $msg = '您好，东窗客服为您服务！';
                        $ret = $this->transmitText($msgObj,$msg);
                        echo $ret;
                        break;

                    default:
                        # code...
                        break;
                }

                }
        }


    #发送图文消息
    function sendTu($data){
       $data = array(
                'touser'=>$data['FromUserName'],
                'msgtype'=> 'news',
                'news' => array(
                        'articles' => array(
                            array('title'=>'欢迎来到东窗，点击"查看全文"了解东窗!', 'description'=>'', 'url'=>spUrl('protected/edc/edc'), 'picurl'=>spConfig('static_url').'/asset/img/msg_index.jpg')
                   )
                )
        );

        $result = model('Message')->__send_kf($data);
        return $result;
    }



    #发送图文消息
    function sendTw($data){
       $data = array(
                'touser'=>$data['FromUserName'],
                'msgtype'=> 'news',
                'news' => array(
                        'articles' => array(
                            array('title'=>'东窗合伙人招募啦!', 'description'=>'', 'url'=>spUrl('protected/user/bp_what'), 'picurl'=>spConfig('static_url').'/asset/img/bp_what_sub.jpg'),
                            array('title'=>'关注东窗送好礼,iphone,小米,魅族手机等您拿!', 'description'=>'', 'url'=>spUrl('protected/edc/gift'), 'picurl'=>spConfig('static_url').'/asset/img/gift_sub.jpg'),
                            array('title'=>'注册业务合伙人就能抽大奖，10辆三轮等您拿！', 'description'=>'', 'url'=>spUrl('protected/edc/register_gift'), 'picurl'=>spConfig('static_url').'/asset/img/register_gift_sub.jpg'),
                            array('title'=>'首发日庆祝，红包大放送！', 'description'=>'', 'url'=>spUrl('protected/edc/act'), 'picurl'=>spConfig('static_url').'/asset/img/act_sub.jpg'),
                        )
                )
        );
        $result = model('Message')->__send_kf($data);
        return $result;
    }


    function transmitText($object, $content)
     {
         if (!isset($content) || empty($content)){
             return "";
         }
         $xmlTpl = "<xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime>%s</CreateTime>
                         <MsgType><![CDATA[text]]></MsgType>
                         <Content><![CDATA[%s]]></Content>
                    </xml>";
         $result = sprintf($xmlTpl,$object->FromUserName,$object->ToUserName,time(),$content);

         return $result;
     }
}

