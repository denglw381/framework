<?php
/** 
 * 日志处理类 
 * @author 
 * @version 
 */
class DmLog {
	// 日志级别 从上到下，由低到高
	const ERR       = 'ERR';
	// 日志记录方式
	const FILE   = 3;


	// 日志信息
	static $log = array();

	static $uniqid = ''; 

	// 日期格式
	static $format = 'Y-m-d H:i:s';

	/** 
	 * 记录日志 并且会过滤未经设置的级别
	 */
	static function record($message,$level=self::ERR,$record=false) {
		if($record) {
				self::$log[]= self::formatMessage($message, $level);
		}   
		self::save();
	}   

	/** 
	 * 日志保存
	 */
	static function save() {
		$type = self::FILE;
		$destination = dmConfig('log.path').date('Y_m_d').".log";
		error_log(implode("",self::$log), $type,$destination);
		// 保存后清空日志缓存
		self::$log = array();
	}   

	/** 
	 * 日志直接写入
	 */
	static function write($message, $level=self::ERR, $destination=''){
		$type = self::FILE;
		$message = self::formatMessage($message, $level);
		if(empty($destination))
			$destination = dmConfig('log.path').date('Y_m_d').".log";
		//检测日志文件大小，超过配置大小则备份日志文件重新生成
		error_log($message, $type,$destination);
	}   

	/** 
	 *魔术方法
	 */
	public static function __callStatic($name,$arguments){
		$message = $arguments[0];
		$level  = strtoupper($name);
		self::record($message, $level, true);
	}   

	/** 
	 *格式化数据
	 */
	public static function formatMessage($message, $level=self::ERR){
		if(!is_scalar($message)) $message = json_encode($message);
		$now = date(self::$format);
		if(empty(self::$uniqid)) self::$uniqid = uniqid();
		return  "{$level} - {$now} --> ".self::$uniqid.'|'.$message.PHP_EOL;
	}   
}


