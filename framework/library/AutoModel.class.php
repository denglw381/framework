<?php
/**
 * 自动加载model文件
 * @param unknown_type $className
 */
class AutoModel{
	/*
	function __construct(){
		if(TRUE == spConfig('view.enabled')){
			$this->v = spClass('spView');
		}
		$this->v->engine->setTemplateDir(dirname(__FILE__).'/automodel/');
	}

	function getModel($tpl){
		$data = $this->v->engine->fetch($tpl);
		var_dump($data);
	}

	static function __autoload($className){
		$filename=APP_MODEL.DS.$className.".php";
		if(file_exists($filename)) include $filename;
		else{
			$filename=APP_INCLUDE.DS.$className.".class.php";
			if(file_exists($filename)) include $filename;	
		}
	}
    */

	/**
	 * 自动生成model文件
	 */
	static function makeAllModels(){
		$heihei=new spModel();
		$result=$heihei->findSql("show tables");
		foreach ($result as $key=>$val){
			$val=array_values($val);
			$val=$val[0];
			$table=str_replace(spConfig("db.prefix"),"",$val);
			$tableClassName = ucfirst(self::getTableClassName($table).'Model');
			if(!file_exists($modelFileName = spConfig('model_path').DS.$tableClassName.".php")) 
			{
				$f=fopen($modelFileName,"w");
				if(preg_match("/^".spConfig("db.prefix")."[a-zA-Z0-9]+/i",$val)) $tablestr='$table';
				else $tablestr='$tbl_name';
				$str='<?php
class {{tableClassName}} extends spModel{
	var $pk="{{pk}}";
	var '.$tablestr.'="{{table}}";
			}
			';
			$result=$heihei->findSql("describe $val");
			$i=0;
			foreach ($result as $k=>$val){
				if($i==0){
					$key=$val["Field"];
					$i++;
				}
				else {
					if($val["Key"]=='PRI'){
						$key=$val["Field"];
					}
				} 
				$i++;
			}
			$str=str_replace(array("{{tableClassName}}","{{table}}","{{pk}}"),array($tableClassName,$table,$key),$str);
			fwrite($f,$str);
			fclose($f);
			}
		}
	}

	static function getTableClassName($tableName){
		return	preg_replace_callback('/_(\w)/',create_function(
			'$matches',
			'return strtoupper($matches[1]);'	
		),
		$tableName
	);	
	}

	static function getArrayStr(array $arr, $level = 1, $is_field = false){
		$str = 'array('.PHP_EOL;
		foreach($arr as $key=>$val){
			if(is_string($key)) $key = '\''.$key.'\'';
			if(is_string($val)) $val = '\''.$val.'\'';
			$str .= str_repeat(SP_SPACE, $level);
			if(is_scalar($val))
				$str .= $key.'=>'.$val.','.PHP_EOL;	
			else if(is_array($val)){
				$str .= $key.'=>'.getArrayStr($val, $level+1, true);
			}
		}
		$str .= str_repeat(SP_SPACE, $level-1);
		if($is_field){
			$str .= '),'.PHP_EOL;
		}else{
			$str .= ');'.PHP_EOL;
		}
		return $str;
	}
}


