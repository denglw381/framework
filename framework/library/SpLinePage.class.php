<?php
#分页类，line与普通分页
class SpLinePage{
        var $delimiter = '_O0_';
        var $direction = 'next';
        var $input;
        #解析sql语句
        function parse_sql($sql, $page = 0, $count = 20, $prev_id = null, $next_id = null){
             if($prev_id) $this->direction = 'prev';
             else if($next_id) $this->direction = 'next';
             $input = $prev_id ? $prev_id : ($next_id ? $next_id : null);
             $order = $this->parse_order($sql);
             $input = $this->parse_input($input, $order);
             $sql = $this->build_field($sql, $order);
             $sql  = $this->build_condition($sql, $order, $input, $this->direction);
             $sql  = $this->build_count($sql, $count);
             return $sql;
        }

        #解析排序
        function parse_order($sql){
            $order = preg_split('/\s+order\s+by\s+/i',$sql);
            $order = end($order);
            $order = explode(',', $order);
            array_walk($order, 
                function($value, $key) use (&$order)
                 {
                    $order[$key] = trim($value);
                 }
            );
            $arr = array();
            foreach($order as $key => $val){
                   if($val){
                        $val = preg_split('/\s+/', $val); 
                        if(isset($val[1])) $arr[$val[0]] = strtolower($val[1]);
                        else $arr[$val[0]] = 'asc';
                   }
            }
            return $arr;
        }
        
        #
        function build_count($sql, $count){
            $sql = preg_replace('/limit[\d,\s]+$/i', '', $sql);
            $sql .=  ' limit '.$count;
            return $sql;
        }
        
        #附加条件
        function get_condition_str($order, $input, $direction = 'next'){
            $arr = array();
            foreach($order as $key=>$val){
                if($val == 'desc'){
                    if($direction == 'next'){
                         $arr[$key] = $key.'<'.$input[$key];
                    }else{
                         $arr[$key] = $key.'>'.$input[$key];
                    }
                }else{
                    if($direction == 'next'){
                         $arr[$key] = $key.'>'.$input[$key];
                    }else{
                         $arr[$key] = $key.'<'.$input[$key];
                    }
                }
            }
            $condition_arr = array();
            $length = count($order);
            $t_index = 0;
            for($i = 0; $i<$length; $i++){
                   $str_arr = array();
                   $index = 0; //$length-($i+1);
                   foreach($order as $key=>$val){
                       if($index == $t_index){
                             $str_arr[] = $arr[$key];
                             break;
                        }else{
                            $str_arr[] = $key.'='.$input[$key]; 
                        }
                        $index ++ ;
                   }
                   $str[] = ' ('.join(' and ', $str_arr).') ';
                   $t_index ++;
            }
            $return_str = join(' or ', $str);
            if(count($str) > 1) $return_str = ' ('.$return_str.') ';
            return $return_str;
        }

        #解析条件
        function build_field($sql, $order){
            $str = '';
            $addition_condition = array();
            $i = 0;
            foreach($order as $key=>$val){
                $str .= ', '.$key.' as '.$this->delimiter.$i;
                $i ++;
            }
            $result = preg_replace('/^(\s*select\s+.*?)(\s+from\s+)/i', "\${1}{$str}\${2}",$sql);
            return $result;
        }

        #解析条件->todo 尚不支持子查询
        function build_condition($sql, $order, $input, $direction = 'next'){
            if(empty($input)) return $sql;
            $pattern = '/(?>\sGROUP\s+BY\s|\sHAVING\s|\sORDER\s+BY\s|\sLIMIT\s).*?$/is';
            $main_sql = preg_replace('/\(((?>[^()]+)|(?R))*\)/isx', '', $sql);
           // $data = preg_match_all($pattern, $sql, $matches);
            $condition_addon = $this->get_condition_str($order, $input, $direction);
            if(stripos($main_sql, 'where')) $condition_addon = ' and '.$condition_addon;
            else $condition_addon = ' where '.$condition_addon;
            return preg_replace($pattern,  " {$condition_addon}\${0}", $sql);
        }

        #解析输入
        function parse_input($input, $order){
            if(empty($input)) return array();
            $input = explode($this->delimiter, $input);
            $i = 0;
            foreach($order as $key=>$val){
                   $order[$key] = '\''.$input[$i].'\'';
                   $i++ ;
            }
            return $order;
        }

        #线性分页
        function get($sql, $count = 20, $prev_id = null, $next_id = null, $callback = null){
                $return  = array();
                $return['page_is_last'] = true;
                $tmp_count = $count + 1;
                $sql = $this->parse_sql($sql, $page = 0, $tmp_count, $prev_id, $next_id);
                $result = call_user_func_array($callback, array($sql, false));
               // $result = $result->result('array');
                if($result && (count($result) > $count)){
                        $return['page_is_last'] = false;
                        unset($result[$count]);
                }
                $str = array();
                $last_record = end($result);
                if($last_record)
                foreach($last_record as $key=>$val){
                        if(0 === strpos($key, $this->delimiter)){
                                $str[] = $last_record[$key];
                        }
                }
                $return['next'] = join($this->delimiter, $str);
                $str = $first_record = array();
                if($result)
                    $first_record = $result[0];
                if($first_record)
                foreach($first_record as $key=>$val){
                        if(0 === strpos($key, $this->delimiter)){
                                $str[] = $first_record[$key];
                        }
                }
                $return['prev'] = join($this->delimiter, $str);

                foreach($result as &$row){
                        foreach($row as $key=>$val){
                                if(0 === strpos($key, $this->delimiter)){
                                        unset($row[$key]);
                                }
                        }
                }
                $return['datas'] = $result;
                $return['count'] = count($return['datas']);
//                $return['total'] = $count;
                return $return;
        }

}

/*
$sql = 'select uid,uname,dd,`from` from (select * from ts_user where (uid>0) order by uid desc) where (uid=22149) group by uid having abc
orDer by Uid
deSc, 
ctime';
//$order =  array('a.`uid`'=>'desc','id'=>'asc', 'ctime'=>'asc','fid'=>'desc');
//$input = '1234_O0_1111_O0_2222_O0_3333';
$input = '11111_O0_22222';
$pager = new LinePage();
var_dump($pager->parse_sql($sql, 0, 20 , $input, null));
exit;
var_dump($pager->build_condition($sql, $order, $pager->parse_input($input, $order), 'prev'));
exit;
var_dump($pager->build_condition($sql, $order));
var_dump($pager->build_field($sql, $order));
exit;
var_dump($pager->query($sql));
exit();
var_Dump($pager->parse_input('1234_O0_12345', array('uid'=>10, 'uids'=>100)));
var_dump($pager->get_condition_str(array('a.`uid`'=>'desc'), array('a.`uid`'=>100), 'prev'));
var_dump($pager->get_condition_str(array('a.`uid`'=>'desc','id'=>'asc', 'ctime'=>'asc','fid'=>'desc'), array('a.`uid`'=>100, 'ctime'=>1000, 'id'=>'1000', 'fid'=>29), 'prev'));
*/
