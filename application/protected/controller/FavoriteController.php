<?php
/** 
 * @author 
 * @version 
 */
class FavoriteController extends BaseController
{
       
    #列表
    function indexAction(){
               echo json_encode([
                'list'=>[
                ['picurl'=>'http://f12.baidu.com/it/u=1536232571,2962168798&fm=76', 'name'=>rand(1000,9999), 'description'=>'adfsasdfasdfasdfasdfadfs'],
            ]])
            ;
    }

    #添加
    function addAction(){
           $music_id = (int)$this->post('id', 0);  
           if(0 == $music_id){
                    $music_id = model('UserSetting')->getField('last_play_music_id', ['uid'=>$this->uid]);
           }
           $result = model('UserFavorite')->findSql('select max(weight) as weight from tbl_user_favorite where uid = '.$this->uid);
           if($result) $max_weight = $result[0]['weight'];
           $max_weight = intval($max_weight) + 1;
           model('UserFavorite')->replace(['uid'=>$this->uid, 'product_id'=>$music_id], ['ctime'=>time(), 'weight'=>$max_weight]);
           $favorite  = model('UserFavorite')->findSql('select count(*) as number from tbl_user_favorite where product_id = '.$music_id);
           model('Product')->update(['id'=>$music_id], ['favorite_times'=>(int)$favorite[0]['number']]);
           $this->success('收藏成功！');
    }

    #删除
    function cancelAction(){
          $music_id = $this->post('id', 0);  
           if(0 == $music_id){
                    $music_id = model('UserSetting')->getField('last_play_music_id', ['uid'=>$this->uid]);
           }
           model('UserFavorite')->delete(['uid'=>$this->uid, 'product_id'=>$music_id]);
           $favorite  = model('UserFavorite')->findSql('select count(*) as number from tbl_user_favorite where product_id = '.$music_id);
           model('Product')->update(['id'=>$music_id], ['favorite_times'=>(int)$favorite[0]['number']]);
           $this->success;
    }

    #上移
    function upAction(){
            $id = $this->post('id');
            $info = model('UserFavorite')->find(['product_id'=>$id, 'uid'=>$this->uid]);
            $datas  = model('UserFavorite')->findAll('uid='.$this->uid.' and weight >= '.$info['weight'], 'weight asc', '', 2);
            if(count($datas) < 2) return;
            model('UserFavorite')->update(['id'=>$datas[0]['id']], ['weight'=>$datas[1]['weight']]);
            model('UserFavorite')->update(['id'=>$datas[1]['id']], ['weight'=>$datas[0]['weight']]);
            $this->favoritelist();
    }

    #下移
    function downAction(){
            $id = $this->post('id');
            $info = model('UserFavorite')->find(['product_id'=>$id, 'uid'=>$this->uid]);
            $datas  = model('UserFavorite')->findAll('uid='.$this->uid.' and weight <= '.$info['weight'], 'weight desc', '', 2);
            if(count($datas) < 2) return;
            model('UserFavorite')->update(['id'=>$datas[0]['id']], ['weight'=>$datas[1]['weight']]);
            model('UserFavorite')->update(['id'=>$datas[1]['id']], ['weight'=>$datas[0]['weight']]);
            $this->favoritelist();
    }


    function favoritelist(){
             $uid = $this->uid;
             $sql = 'select b.* from tbl_user_favorite as a join tbl_product as b
                        on a.product_id = b.id
                     where a.uid = '.$uid.' order by a.weight desc, b.play_times desc 
                ';
             $data['datas'] = model('UserSetting')->findSql($sql);
             foreach($data['datas'] as &$value){
                   $setting = model('UserSetting')->find(['uid'=>$uid]);
                   if($value['id'] == $setting['last_play_music_id']){
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

}
