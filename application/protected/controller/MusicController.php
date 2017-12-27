<?php
/** 
 * @author 
 * @version 
 */
class MusicController extends BaseController
{
                

/*
{
    hidden  : true

   // 程序中使用到底属性初始化值\
   ,sliderVisibility:'hidden'
   ,curPosition    : '00:00'
   ,totalDuration : '00:00'
   ,musicTypeTitle : '频道选择'
   ,singername  : '未知'
   ,songname    : '未知'
   ,singerpic   : '../../res/img/logo_music.png'
   ,songHash    : ''
   ,singerPicRotate : 0
   ,selectIndex : 0
   ,musicTypes : [
     { type: 'calm', src:"https://w1.wantme.cn/static/images/index_qumu_01.png",
     title:'回忆乐章'}
     , { type: 'happy', src: "https://w1.wantme.cn/static/images/index_qumu_08.png", title:'钟表滴答'}
     , { type: 'puzzled', src: "https://w1.wantme.cn/static/images/index_qumu_02.png", title:'约会酒吧'}
     , { type: 'surprised', src: "https://w1.wantme.cn/static/images/index_qumu_03.png", title:'走过集市'}
     , { type: 'angry', src: "https://w1.wantme.cn/static/images/index_qumu_04.png", title:'雪地漫步'}
     , { type: 'scared', src: "https://w1.wantme.cn/static/images/index_qumu_05.png", title:'存钱计划'}
     , { type: 'sad', src: "https://w1.wantme.cn/static/images/index_qumu_06.png", title:'迪士尼岛'}
     , { type: 'smiling', src: "https://w1.wantme.cn/static/images/index_qumu_07.png", title:'人在路途'}
    ]
    , playList : []
    , curMusicInfo : {}
    , favorite_btn: "https://w1.wantme.cn/static/images/music_player_03.png"
    , play_btn: "https://w1.wantme.cn/static/images/music_player_02_1.png"
    , rand_btn: "https://w1.wantme.cn/static/images/music_player_01.png"
    , animationData: {}
    , style: 'opacity: 0'
    , showInterval : 0
    , defaultInterval : 30
    , percent : 0
  }
*/

    function indexAction(){
/*
var  $result = [
    'hidden'  => true
   // 程序中使用到底属性初始化值\
   ,'sliderVisibility' =>'hidden'
   ,'curPosition'    => '00:00'
   ,'totalDuration' => '00:00'
   ,'musicTypeTitle' => '频道选择'
   ,'singername'  => '未知'
   ,'songname'    => '未知'
   ,'singerpic'   => '../../res/img/logo_music.png'
   ,'songHash'    => ''
   ,'singerPicRotate' => 0
   ,'selectIndex' => 0
   ,'musicTypes' => [
      [ 
            'id' => '',
            'name' => 'calm', 
            'cover' => '回忆乐章',
            'url'  => "https://w1.wantme.cn/static/images/index_qumu_01.png",
            'image'  => "https://w1.wantme.cn/static/images/index_qumu_01.png",
            'play_times'  => "https://w1.wantme.cn/static/images/index_qumu_01.png",
            'ctime'  => "https://w1.wantme.cn/static/images/index_qumu_01.png",
     ], 
     [ 
        'type'=> 'happy', src: "https://w1.wantme.cn/static/images/index_qumu_08.png", title:'钟表滴答'
     ]
     , { type=> 'puzzled', src: "https://w1.wantme.cn/static/images/index_qumu_02.png", title:'约会酒吧'}
     , { type=> 'surprised', src: "https://w1.wantme.cn/static/images/index_qumu_03.png", title:'走过集市'}
     , { type=> 'angry', src: "https://w1.wantme.cn/static/images/index_qumu_04.png", title:'雪地漫步'}
     , { type=> 'scared', src: "https://w1.wantme.cn/static/images/index_qumu_05.png", title:'存钱计划'}
     , { type=> 'sad', src: "https://w1.wantme.cn/static/images/index_qumu_06.png", title:'迪士尼岛'}
     , { type=> 'smiling', src: "https://w1.wantme.cn/static/images/index_qumu_07.png", title:'人在路途'}
    ]
    ,'playList' => []
    ,'curMusicInfo' => {}
    ,'favorite_btn' => "https://w1.wantme.cn/static/images/music_player_03.png"
    ,'play_btn'=> "https://w1.wantme.cn/static/images/music_player_02_1.png"
    ,'rand_btn'=> "https://w1.wantme.cn/static/images/music_player_01.png"
    ,'animationData' => {}
    ,'style' => 'opacity: 0'
    ,'showInterval' => 0
    ,'defaultInterval' => 30
    ,'percent' => 0
];
*/
             $data = [];
             $data['datas'] = model('Product')->findAll(['is_show'=>1], 'weight desc, id desc'); 
          
             $this->success($data);
    }

    function detailAction(){
            $id = $this->post('id', 0);
            $detail = model('Product')->find(['id'=>$id]);
            $this->success($detail);
    }

    #播放一首或下一首歌曲
    function playAction(){
            $data = [];
            $auto = $this->post('auto', 0);
            $data['product_id'] = (int)$this->post('id', 0);
            $setting = model('UserSetting')->find(['uid'=>$this->uid]);
            if($auto && $setting['play_duration'] > 0){
                model('UserSetting')->update(['uid'=>$this->uid], ['play_duration'=>0]);
                $this->success(new stdClass);
            }
            if(0 == $data['product_id']){
                    if($auto == 0 && $setting && $setting['last_play_music_id']){//手动触发逻辑
                            $data['product_id'] = $setting['last_play_music_id']; 
                    }            
                    elseif($auto){ //播放器播放下一首歌曲逻辑
                         $data['product_id'] = $setting['last_play_music_id']; 
                         if($setting['play_rand']){
                               $music = model('Product')->find('is_show=1 and id!='.$data['product_id'], 'rand()');
                         }else{
                               $music = model('Product')->find(['id'=>$data['product_id']]);
                               $music = model('Product')->find('is_show=1 and weight<'.$music['weight'], 'weight desc');
                               if(empty($music)) $music = model('Product')->find('is_show=1', 'weight desc'); 
                         }
                         $data['product_id'] = $music['id'];
                    }else{//新用户进来随机播放
                    //        if($setting['play_rand']){
                            $data['product_id'] = rand(1, 100);
                    }

            }
            $music = model('Product')->find(['id'=>$data['product_id'], 'is_show'=>1]);
            if(empty($music)) $this->playAction();
            $data['uid'] = $this->uid;
            $data['ctime'] = time();
            model('UserViewHistory')->create($data);
            model('Product')->incrField(['id'=>$data['product_id']], 'play_times');
            if($setting['play_duration']){
                 //$music['url'] = preg_replace('/\.mp3$/i', '_'.$setting['play_duration'].'.mp3', $music['url']);
            }
            $music['singer'] = '蓝鲸睡眠';
            $music['epname'] = '蓝鲸睡眠';
            $music['background'] = 'http://w5.wantme.cn/static/images/play_back_ground.png';
            $music['favorite_icon'] = 'https://w1.wantme.cn/static/images/music_player_03.png';
            $music['is_favorite'] = (int) model('UserFavorite')->find(['uid'=>$this->uid, 'product_id'=>$music['id']]);
            $music['is_rand'] = $setting['play_rand'];
            if($setting['play_rand'] == 0) $music['rand_ico'] = 'https://w1.wantme.cn/static/images/music_player_01.png';
            else $music['rand_ico'] = 'https://w1.wantme.cn/static/images/player_rander_03.png';
            if(0 == $music['is_favorite']) $music['favorite_icon'] = 'https://w1.wantme.cn/static/images/player_favorite.png';
            model('UserSetting')->replace(['uid'=>$this->uid], ['last_play_music_id'=>$music['id']]);
            $this->success($music);
    }

    function hotAction(){
            $data = [];
            $pause = $this->post('pause', 0); 
            $data['datas'] = model('Product')->findAll(['is_show'=>1], 'play_times desc, favorite_times desc, weight desc, id desc');
            $uid = $this->uid;
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
                   $value['favorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_15.png';
                   $value['is_favorite'] = 0;
                   $favorite = model('UserFavorite')->find(['uid'=>$uid, 'product_id'=>$value['id']]);
                   if($favorite){
                         $value['is_favorite'] = 1;
                         $value['favorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_06.png';
                   }
             }
             $data['favorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_06.png';
             $data['unfavorite_icon'] = 'https://w1.wantme.cn/static/images/favorite_15.png';
             $data['play_icon'] = 'https://w1.wantme.cn/static/images/favorite_14.png';
             $data['unplay_icon'] = 'https://w1.wantme.cn/static/images/favorite_04.png';
            $this->success($data);
    }

    function settingAction(){
            $rand = $this->post('rand', 0);
            D('UserSetting')->replace(['uid'=>$this->uid], ['play_rand'=>$rand]);
            $this->success();
   }
}
