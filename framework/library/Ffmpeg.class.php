<?php
class Ffmpeg{
                
        var $ffmpeg = "/usr/local/ffmpeg/bin/ffmpeg";

        function get_mp3_info($path){
               $command = $this->ffmpeg.' -i '.$path. " 2>&1"; 
               exec($command, $output, $result); 
               $data = [];
               foreach($output as $info){
                       if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $info, $match)) {
                               $data['duration'] = $match[1]; //播放时间
                               $arr_duration = explode(':', $match[1]);
                               $data['seconds'] = $arr_duration[0] * 3600 + $arr_duration[1] * 60 + $arr_duration[2]; //转换播放时间为秒数
                               $data['start'] = $match[2]; //开始时间
                               $data['bitrate'] = $match[3]; //码率(kb)
                               break;
                       }
               }
               return $data;
        }

        function concat_mp3($mp31, $mp32){
                $tmp_mp3 = '/tmp/'.md5(uniqid()).'.mp3';
                $command = $this->ffmpeg." -i \"concat:$mp31|$mp32\" -acodec copy $tmp_mp3";
                exec($command, $output, $result);
                return $tmp_mp3;
        }

        function merge_mp3($mp3, $times){
                if($times < 2) return $mp3;
                $tmp_mp3 = $mp3;
                for($i=0; $i<$times; $i++){
                      $tmp_mp3 = $this->concat_mp3($tmp_mp3, $mp3);
                }
                return $tmp_mp3;
        }

        /**
         *
         */
        function get_minute_mp3($mp3, $minute = 5){
                $tmp_mp3 = '/tmp/'.md5(uniqid()).'.mp3';
                $seconds = $minute * 60;
                $info = $this->get_mp3_info($mp3);
                $times = ceil($seconds / $info['seconds']);
                $mp3 = $this->merge_mp3($mp3, $times);
                $end_time = sprintf('%02d:%02d:%02d', $seconds/3600, ($seconds%3600)/60, $seconds%3600%60);
                $command = $this->ffmpeg." -i $mp3 -vn -acodec copy -ss 00:00:00 -t $end_time ".$tmp_mp3;
                exec($command, $output, $result);
                return $tmp_mp3;
        }

        function do_copy_rename($mp3, $minute = 5){
                $tmp_name = $this->get_minute_mp3($mp3, $minute);
                $new_mp3_name = preg_replace('/\.mp3$/i', "_$minute.mp3", $mp3);
                copy($tmp_name, $new_mp3_name);
        }

        /**
         *
         */
        function do_copy($mp3){
                $this->do_copy_rename($mp3, 5);
                $this->do_copy_rename($mp3, 15);
                $this->do_copy_rename($mp3, 30);
                $this->do_copy_rename($mp3, 60);
                exec('/bin/rm /tmp/*mp3', $output, $result);
        }

        function del_mp3($mp3){
                $minutes = [5, 15, 30, 60]; 
                $new_mp3_name = preg_replace('/\.mp3$/i', "_$minute.mp3", $mp3);
        }
}

//$ff = new Ffmpeg;
//$mp3 = "/www/web/root/w1.wantme.cn/webroot/music/nianhuasishui.mp3";
//$mp3 = "/www/web/root/w1.wantme.cn/webroot/static/upload/20171218/a57f72ff1424a6c5ef58b43a9d0b5932.mp3";
//$mp3 = "/www/web/root/w1.wantme.cn/webroot/music/youjianalang.mp3";
//$mp3 = "/www/web/root/w1.wantme.cn/webroot/music/qiangnianzhilian.mp3";
//$output = $ff->get_mp3_info("/www/web/root/w1.wantme.cn/webroot/music/nianhuasishui.mp3");
//$ff->concat_mp3($mp3, $mp3);
//$output = $ff->get_minute_mp3($mp3, 60);
//$ff->do_copy($mp3);
//var_Dump($output);
