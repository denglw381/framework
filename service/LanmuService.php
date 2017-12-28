<?php
class LanmuService extends spService{
	var $lanmuDao = '';
	//栏目配置
	var $lanmuConfig = array(
		'顶级栏目',
		'一级栏目',
		'三级栏目'
	);
	
	function _initialize(){
		$this->lanmuDao = model('Lanmu');
	}

	function getOne($id){
		return $this->lanmuDao->getOne($id);
	}

	function addOne($data){
		return $this->lanmuDao->addOne($data);
	}

	function updateOne($condition, $data){
		return $this->lanmuDao->updateOne($condition, $data);
	}

	function listPage($condition = NULL, $page = 1, $pageSize = 20){
		return $this->lanmuDao->listPage($condition, $page, $pageSize);
	}

	function getAll($condition = null){
		$lanmus = $this->lanmuDao->getAll($condition);
		$this->_tidyUrl($lanmus);
		return $lanmus;
	}
	
	function getLanmuLocationName($id){
		$info = $this->getOne($id);
	}

	function tidyIds($id){
			$condition = array();	
			$condition['pid'] = $id;
			$data = $this->getOne($id);
			if($data) $condition['location'] += $data['location'] + 1;
			switch($data['location']){
			case 0:
				$condition['tid'] = $id;
				$condition['lid'] = 0;
				break;
			case 1:
				$condition['tid'] = $data['tid'];
				$condition['lid'] = $id;
				break;
			default:
				$condition['tid'] = 0;
				$condition['lid'] = 0;
				break;
			}
			return $condition;
	}

	function getLeftInfo($topId, $uid){
		$result = array();
		$titleInfo = $this->getAll(array('tid'=>(int)$topId, 'lid'=>0));
		foreach($titleInfo as &$info){
			if(model('LanmuRight')->find(array('lid'=>$info['id'], 'status'=>1, 'uid'=>$uid))){
				$info['sons'] = $this->lanmuDao->findAllSql('select l.* from `'.spConfig('db.prefix').'lanmu` as l join '.spConfig('db.prefix').'lanmu_right as r on l.`tid`=r.`tid` and l.lid=r.`lid` and l.`id`=r.`sid` where r.`status`=1 and r.`uid`='.$uid.' and r.`tid`='.$topId.' and r.`lid`='.$info['id']);
				$this->_tidyUrl($info['sons']);
				$result[] = $info;
			}
		}
		return $result;
	}

	function setUrlHistory($location, $url){
		if(!in_array(array('top','left','right'), $location)){
			return false;
		}else{
			$_SESSION['admin_url'][$location] = $url; 
			return true;
		}

	}

	function getUrlHistory($location = null){
		if(is_null($location)) return $_SESSION['admin_url'];
		if(!in_array(array('top','left','right'), $location)){
			$location = 'top';
		}
		return $_SESSION['admin_url'][$location];
	}

	private function _tidyUrl(&$datas){
		foreach($datas as &$data){
			if(empty($data['url']) && $data['lid']){
				$data['controller'] = 'admin/'.$data['controller'];//硬编码
				$data['url'] = spUrl($data['controller'], $data['action']);
			}//else echo $data['controller'];
		}
	}
}
