<?php
/** 
 * @author 邓先生 email:nenu_1@126.com
 * @version 1.0
 */
class MusicController extends BaseController
{
        var $model;

        function _init(){
        }

        function __upload($files){
                $ret = [];
                $dir = spConfig('upload_path').'/'.date('Ymd');
                $url = spConfig('upload_url').'/'.date('Ymd');
                if(!is_dir($dir)) spMkdirs($dir);
                foreach($files as $key => $file){
                        if(empty($file['tmp_name'])) continue;
                        $ext = end(explode('.', $file['name']));
                        $file_name = '/'.md5(uniqid()).'.'.$ext;
                        move_uploaded_file($file['tmp_name'], $dir.'/'.$file_name);
                        $ret[$key] = $url.$file_name;
                }
                return $ret;
        }

        function doChangeAction(){
               set_time_limit(0);
               if(mb_strlen($this->spArgs('name'), 'utf-8') > 4) $this->error('曲目名称不能大于4个字符！');
               $ret = (array)$this->__upload($_FILES);
               $top = $this->spArgs('top', 0);
               $id = $this->spArgs('id', 0);
               $input = array_merge($ret, $_REQUEST);
               foreach($input as $key=>$val){
                        if(empty($val)) unset($input[$key]);
               }
               $input['ctime'] = time();
               if($input['url']){
                       $input['local'] = str_replace(spConfig('upload_url'), realpath(spConfig('upload_path')), $input['url']); 
                      // dmTask('ms_media')->add($input);
               }
               //library('Ffmpeg')->do_copy($input['local']);
               if($id){
                    model('Product')->update(['id'=>$id], $input);
               }else{
                    $datas = model('Product')->findSql('select min(weight) as weight from tbl_product');
                    $input['weight'] = $datas[0]['weight'] - 1;
                    $id = model('Product')->create($input);
               } 
               if($top){
                    $result = model('Product')->findSql('select max(weight) as weight from tbl_product');
                    $weight = intval($result[0]['weight']) + 1;
                    model('Product')->update(['id'=>$id], ['weight'=>$weight]); 
               }
               $this->success('上传成功！');
        }
        
        function changeAction(){
                $id = $this->spArgs('id', 0);
                if($id){
                     $data = model('Product')->find(['id'=>$id]);
                     if($data['local']){
                            $data['filesize'] = round(filesize($data['local'])/1024/1024, 2); 
                            $music = library('Ffmpeg')->get_mp3_info($data['local']);
                            $data['duration'] = date('i\'s\'\'', $music['seconds']);
                     }
                }
                $this->assign($data);
                $this->display();
        }

 
        function loadonAction(){
                $this->display();
        }

 
        function onlineAction(){ 
                $ret = [];
                $ret['datas'] = model('Product')->findAll(['is_show'=>1], '`weight` desc, id asc');
                $this->assign($ret);
                $this->display();
        }

        function outlineAction(){
                $ret = [];
                $ret['datas'] = model('Product')->findAll(['is_show'=>0], '`weight` desc, id asc');
                $this->assign($ret);
                $this->display();
        }

        function doPublish(){
                $status = $this->spArgs('publish', 1);
                $id = $this->spArgs('id', 1);
                model('Product')->update(['id'=>$id], ['is_show'=>$status]);
        }


        function setoutlineAction(){
                $id = $this->spArgs('id', 0);
                $result = model('Product')->findSql('select min(weight) as weight from tbl_product');
                $weight = intval($result[0]['weight']) - 1;
                model('Product')->update(['id'=>$id], ['is_show'=>0, 'weight'=>$weight]);
                $this->success('已下架');
        }


        function setonlineAction(){
                $id = $this->spArgs('id', 0);
                $result = model('Product')->findSql('select min(weight) as weight from tbl_product');
                $weight = intval($result[0]['weight']) - 1;
                model('Product')->update(['id'=>$id], ['is_show'=>1, 'weight'=>$weight]);
                $this->success('已上架');
        }

        function settopAction(){
                $id = (int)$this->spArgs('id', 0);
                $result = model('Product')->findSql('select max(weight) as weight from tbl_product');
                $weight = intval($result[0]['weight']) + 1;
                model('Product')->update(['id'=>$id], ['weight'=>$weight]); 
                $this->success('已置顶');
        }


#上移
        function upAction(){
                $id = $this->spArgs('id');
                $info = model('Product')->find(['id'=>$id]);
                $datas  = model('Product')->findAll('is_show=1 and weight >= '.$info['weight'], 'weight asc', '', 2);
                if(count($datas) < 2) return;
                if($datas[1]['weight'] == $datas[0]['weight']) $datas[0]['weight'] = $datas[0]['weight'] + 1;
                model('Product')->update(['id'=>$datas[0]['id']], ['weight'=>$datas[1]['weight']]);
                model('Product')->update(['id'=>$datas[1]['id']], ['weight'=>$datas[0]['weight']]);
                $this->success('上移成功！');
        }

#下移
        function downAction(){
                $id = $this->spArgs('id');
                $info = model('Product')->find(['id'=>$id]);
                $datas  = model('Product')->findAll('is_show=1 and weight <= '.$info['weight'], 'weight desc', '', 2);
                if(count($datas) < 2) return;
                if($datas[1]['weight'] == $datas[0]['weight']) $datas[0]['weight'] = $datas[0]['weight'] -1;
                model('Product')->update(['id'=>$datas[0]['id']], ['weight'=>$datas[1]['weight']]);
                model('Product')->update(['id'=>$datas[1]['id']], ['weight'=>$datas[0]['weight']]);
                $this->success('下移成功');
        }


}
