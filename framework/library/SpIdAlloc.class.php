<?php
//还有一个分支是redisSwitch＝0,则表示以db为
class SpIdAlloc{
        var $config = [];
        var $_dao = null;

        function _init($name = "id"){
                $this->_dao = spDb('tbl_id_alloc');
                $this->config = [
                        'maxnum' => 1024,
                        'minnum' => 1,
                        'start' => '10000000'
                ];
        }

        function getId($name = "id"){
                $this->_init($name);
                $min = intval($this->config['minnum']);
                $max = intval($this->config['maxnum']);
                $idx = rand($min, $max);
                $arrConds  = array(
                                'name' => $name,
                                'idx'  => $idx,
                                );
                $this->_dao->beginTransaction();
                $info = $this->_dao->findSql("select id from tbl_id_alloc where name='$name' and idx='$idx' for update ");
                if($info){
                        $id = $info[0]['id'] + $max;
                        $ret = $this->_dao->update($arrConds, ['id'=>$id]);
                }else{
                        $id  = $this->config['start'] + $idx;
                        $arrConds['id'] = $id;
                        $ret = $this->_dao->create($arrConds);
                }
                if(false == $ret) $this->_dao->rollBack();
                $this->_dao->commit();
                return $id;
        }
}
