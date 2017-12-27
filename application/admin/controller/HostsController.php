<?php
require_once dirname(__FILE__).DS.'BaseController.php';
/** 
 *  
 * 
 * @author 
 * @version 
 */
class HostsController extends BaseController
{
	var $host;

	function _init(){
		$this->host=model('Hosts'); 
		$this->syncMethod = spConfig("svn_transport_method");
		$this->remote_host = spConfig("svn_remote_host");
	}

	function indexAction(){
		$id = $this->spArgs('id',1);
		$this->assign('cat', model('HostsCategory')->getOne($id));
		$this->devBranch=$this->host->getDevBranch($id);
		$this->trunk=$this->host->getTrunk($id);
		$this->online1=$this->host->getOnline1($id);
		$this->onlineBranch=$this->host->getOnlineBranch($id);
		$this->test=$this->host->getTest($id);
		$this->assign("test",$this->test);
		$this->online=$this->host->getOnline($id);
		$this->assign('category_id', $id);
		$this->display("index.php");
	}

	function editAction(){
		$category_id = $this->spArgs('category_id', 1);
		if($id = $this->spArgs('id')){ 
			$this->hostDetail = $this->host->getAHost($id); 
			$category_id = $this->hostDetail['category_id'];
		}
		$this->assign('categorys_info', model('HostsCategory')->getOne($category_id));
		$categorys = model('HostsCategory')->getSameLevelAll($category_id);
		$this->assign('category_id', $category_id);
		$this->assign('agents', model('Agent')->getOptions());
		$this->assign('categorys', read_key_value_from_list('id', 'name',$categorys));
		$this->assign('top_categorys', read_key_value_from_list('id', 'name', model('HostsCategory')->getAll('pid = 0')));
		$this->display("edit.php");
	}

	function deleteAction(){
		$host = model('Hosts');
		$id = $this->spArgs("id");
		$result	= $host->delete(array("id"=>$id));
		if($result) echo 1;
		else echo 0;
	}

	function doeditAction(){
		$data = $this->spArgs();
		$data = $this->filterData($data);
		$data["ext"]   = 0;
		if(intval($data['id'])){
			$result = $this->host->updateOne(array("id"=>intval($data['id'])),$data);
		}else
		$result = $this->host->addOne($data);
		if($result){$this->success("保存成功！");}
		else $this->error("保存失败！");
	}

	function filterData($data){
		$data["ctime"] = time();
		$data['host'] = trim($data['host']);
		$data['host'] = preg_replace("/\/+$/", "", $data['host']);
		$data['agent'] = preg_replace("/\/+$/", "", $data['agent']);
		$data['webdir'] = preg_replace("/\/+$/", "", $data['webdir']);
		return $data;
	}

	function cateListAction(){
		$pid = (int)$this->spArgs('pid', 0);
		if(0 == $pid) $condition = 'pid != 0';	
		else $condition = 'pid = '.$pid ;
		$datas = model('HostsCategory')->getList($condition);			
		$this->assign('datas', $datas);
		$this->assign('pid', $pid);
		$this->display('cateList.php');
	}

	function cateEditAction(){
		$model =  model('HostsCategory');
		if($id = $this->spArgs('id'))
			$data = $model->getOne($id);	
		else
			$data['pid'] = $this->spArgs('pid', 0);
		$this->assign('data', $data);
		$this->assign('lists', read_key_value_from_list('id', 'name', $model->findAll(array('pid'=>0))));
		$this->display('cateEdit.php');
	}

	function cateSaveAction(){
		if('' == ($name = trim($this->spArgs('name')))){
			echo '0';
		}
		$pid = $this->spArgs('pid', 0);
		if($id = $this->spArgs('id')){
			model('HostsCategory')->updateOne($name, $pid, $id);
		}else{
			model('HostsCategory')->addOne($name, $pid);
		}
		echo '1';
	}

	function cateDelAction(){
		$id = intval($this->spArgs('id'));
		if(model('HostsCategory')->deleteOne($id)) echo '1';
		else echo '0';
	}
}
