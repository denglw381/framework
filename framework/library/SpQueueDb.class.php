<?php
/*
 * mysql
  CREATE TABLE `sp_queue` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `data` text NOT NULL,
    `times` tinyint(4) unsigned NOT NULL DEFAULT '0',
    `priority` tinyint(4) unsigned NOT NULL DEFAULT '5',
    `ctime` int(11) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
  *
  * sqlite3	
*/
require dirname(__FILE__).'/SpQueueInterface.php';
class SpQueueDb implements SpQueueInterface{
	var $queue;
	var $conn = NULL;
	var $priority = array();
	var $db = 'sqlite3';
	static $instance;

	function __construct(){
		$initMethod = 'use'.ucfirst($this->db);
		$this->$initMethod();
		$this->setPriority();
	}

	function useMysql(){
		$this->conn = mysql_connect('127.0.0.1', 'root', '123');
		mysql_query('set names utf8', $this->conn);
		mysql_select_db('sync', $this->conn);

	}

	function useSqlite3(){
		if(!is_resource($this->conn)) $this->conn = new SQLite3(dirname(__FILE__).'/test.db');
		if(filesize(dirname(__FILE__)."/test.db")==0){
			$sql = 'CREATE TABLE sp_queue(
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				data TEXT,
				times INTEGER,
				priority INTEGER,
				ctime INTEGER
			);
			';
			$this->conn->exec($sql);
		}
	}

	private function setPriority(){
		$priority = explode(',',SP_PRIPORITY);
		$this->priority['min']		= $priority[0];
		$this->priority['max']		= end($priority);
		$this->priority['default']	= floor($this->priority['max']/2);
	}

	static function getInstance(){
		if(!self::$instance instanceof self){
			self::$instance = new self;
		}
		return self::$instance;
	}	

	function push($data, $queueNo = null){
		$data = serialize($data);
		if(is_null($queueNo) || $queueNo > $this->priority['max'] || $queueNo < $this->priority['min']) $queueNo = $this->priority['default'];
		$sql = 'insert into sp_queue(data,times,ctime,priority) values(\''.$data.'\',0,'.time().','.(int)$queueNo.')';
		$method = $this->db.'Push';
		return $this->$method($sql);
	}

	function sqlite3Push($sql){
		return $this->conn->exec($sql);
	}

	function mysqlPush($sql){
		return mysql_query($sql, $this->conn);
	}

	function get($queueNo){
		$method = $this->db.'Get';
		return $this->$method($queueNo);
	}

	function mysqlGet($queueNo){
		$result = mysql_query('select * from `sp_queue` where `priority`='.$queueNo.' order by id asc limit 1', $this->conn);
		if($result) mysql_query('delete from `sp_queue` where `priority`='.$queueNo.' order by id asc limit 1', $this->conn);
		else return false;
		$row	= mysql_fetch_row($result, MYSQL_ASSOC);	
		return $row['data'];
	}

	function sqlite3Get($queueNo){
		$result = $this->conn->query('select * from sp_queue where priority='.$queueNo.' order by id ');
		$row = $result->fetchArray();
		if($row) $this->conn->exec('delete from sp_queue where id = '.$row['id']);
		else return false;
		return $row['data'];
	}

	function pop(){
		for($i = $this->priority['max']; $i>= $this->priority['min']; $i--){
			$data = $this->get($i);
			if($data) return unserialize($data);
		}
		return false;
	}
}

//SpQueueDb::getInstance()->push(array('1','2','3'), rand(1,6));
//var_dump(SpQueueDb::getInstance()->pop());
?>

