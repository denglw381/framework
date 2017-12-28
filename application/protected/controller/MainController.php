<?php
/** 
 * @author 
 * @version 
 */
class MainController extends BaseController
{
	#获取主记录总数
	function indexAction(){
        $ret = spDB('')->findPage('select * from tbl_product order by id');
var_Dump($ret);
        //$this->jump(spUrl('admin/music/online'));
        //    $this->display();
	}

    function doAction(){
            $datas = model('Product')->findAll();
            foreach($datas as $data){
                 dmTask('ms_media')->add($data);
            }
            
    }
}
