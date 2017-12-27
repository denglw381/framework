<?php
/** 
 * @author 
 * @version 
 */
class UserController extends BaseController
{
	
	#获取主记录总数
	function saveAction(){
            $data = $this->post;
            $data['nickname'] = $data['nickName'];
            $data['headimgurl'] = $data['avatarUrl'];
            model('UserWeixin')->update(['access_token'=>$data['token']], $data);
            $user_info = model('UserWeixin')->find(['access_token'=>$data['token']]);
            $this->success($user_info);
	}

    function setRandAction(){
            $set = (int)$this->post('rand');
            if($set) $play_rand = 1;
            else $play_rand = 0;
            model('UserSetting')->replace(['uid'=>$this->uid], ['play_rand'=>$play_rand]);
            $this->success('设置成功！');
    }

    function favoritelistAction(){
             $pause = $this->post('pause', 0); 
             $uid = $this->uid;
             $sql = 'select b.* from tbl_user_favorite as a join tbl_product as b
                        on a.product_id = b.id
                     where a.uid = '.$uid.' order by a.weight desc, b.play_times desc 
                ';
             $data['datas'] = model('UserSetting')->findSql($sql);
             foreach($data['datas'] as &$value){
                   $setting = model('UserSetting')->find(['uid'=>$uid]);
                   if($pause == 0 && $value['id'] == $setting['last_play_music_id']){
                            $value['play_icon'] = 'https://w1.wantme.cn/static/images/music_player_02_1.png';
                            $value['play_icon'] = 'https://w1.wantme.cn/static/images/favorite_14.png';
                            $value['is_pay'] = 1;
                   }else{
                            $value['play_icon'] = 'https://w1.wantme.cn/static/images/player_23.png';
                            $value['play_icon'] = 'https://w1.wantme.cn/static/images/favorite_04.png';
                            $value['is_pay'] = 0;
                   }
                   $value['favorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_06.png';
                   $value['is_favorite'] = 1;
             }
             $data['favorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_06.png';
             $data['unfavorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_15.png';
             $data['play_icon'] = 'https://w1.wantme.cn/static/images/favorite_14.png';
             $data['unplay_icon'] = 'https://w1.wantme.cn/static/images/favorite_04.png';
             $this->success($data);
    }

    function setPause(){
            $uid = $this->uid;
            $pause = (int)$this->post('pause');
            model('UserSetting')->update(['uid'=>$uid], ['paused'=>$pause]);
            $this->success('操作成功！');
    }


    function playdurationAction(){
            $uid = $this->uid;
            $duration = (int)$this->post('duration');
            model('UserSetting')->update(['uid'=>$uid], ['play_duration'=>$duration]);
            if($duration == 0){
                model('UserSetting')->update(['uid'=>$uid], ['stop'=>0]);
            }
            $this->success('操作成功！');
    }


    function getAction(){
            $uid = $this->uid;
            $userInfo = model('UserWeixin')->find(['uid'=>$uid]); 
            $this->success($userInfo);
    }
    
    function settingAction(){
            $uid = $this->uid;
            $setting = model('UserSetting')->find(['uid'=>$uid]);
            $this->success($setting);
    }
}
