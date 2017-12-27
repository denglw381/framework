<?php
/**
 * spController 基础控制器程序父类 应用程序中的每个控制器程序都应继承于spController
 */
class spController { 

	/**
	 * 视图对象
	 */
	public $v;
	var    $mid;
	var    $userInfo;
	var    $user_type = null;
	/**
	 * 赋值到模板的变量
	 */
	private $__template_vals = array();

	/**
	 * 构造函数
	 */
	public function __construct()
	{	
		if(TRUE == spConfig('view.enabled')){
			$this->v = spClass('spView');
		}
        $data = [
                'static_url' => spConfig('static_url'),
                'request' => $_REQUEST
        ];
		$this->assign($data);
		$this->_initialize();
	}

	/** 
	 *初始化函数，用来代替构造函数 
	 */
	public function _initialize(){

	}
	/**
	 *
	 * 跳转程序
	 *
	 * 应用程序的控制器类可以覆盖该函数以使用自定义的跳转程序
	 *
	 * @param $url  需要前往的地址
	 * @param $delay   延迟时间
	 */
	public function jump($url, $delay = 0){
		echo "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body></body></html>";
		exit;
	}

	/**
	 *
	 * 错误提示程序
	 *
	 * 应用程序的控制器类可以覆盖该函数以使用自定义的错误提示
	 *
	 * @param $msg   错误提示需要的相关信息
	 * @param $url   跳转地址
	 */
	public function error($msg, $url = ''){
		$url = empty($url) ? "window.history.back();" : "location.href=\"{$url}\";";
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"{$msg}\");{$url}}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}

	/**
	 *
	 * 成功提示程序
	 *
	 * 应用程序的控制器类可以覆盖该函数以使用自定义的成功提示
	 *
	 * @param $msg   成功提示需要的相关信息
	 * @param $url   跳转地址
	 */
	public function success($msg, $url = ''){
		$url = empty($url) ? "window.history.back();" : "location.href=\"{$url}\";";
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"{$msg}\");{$url}}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}

	/**
	 * 魔术函数，获取赋值作为模板内变量
	 */
	public function __set($name, $value)
	{
		if(TRUE == spConfig('view.enabled') && false !== $value){
			$this->v->engine->assign(array($name=>$value));
		}
		$this->__template_vals[$name] = $value;
	}

	public function assign($name,$value = NULL){
		$this->v->engine->assign($name,$value );
	}

	/**
	 * 魔术函数，返回已赋值的变量值
	 */
	public function __get($name)
	{
		$vars = explode('_', $name);
		if($vars[0] == 'is'){
			$flag = false;
			for($i = 1; $i<count($vars); $i++){
				if(array_key_exists($vars[$i],$this->user_type) && (($this->userInfo['Role'] & $this->user_type[$vars[$i]]) == $this->user_type[$vars[$i]])) $flag = true;
				else {
					$flag = false;
					break;
				}
			}
			return $flag;
		}else{
			return $this->__template_vals[$name];
		}
	}

	/**
	 * 输出模板
	 *
	 * @param $tplname   模板路径及名称
	 * @param $output   是否直接显示模板，设置成FALSE将返回HTML而不输出
	 */
	public function display($tplname = '', $output = TRUE)
	{
        if(empty($tplname)) $tplname = strtolower(spConfig(spConfig('url_action'))).spConfig('view.auto_display_suffix');
		@ob_start();
		$className = get_class($this);
		$dir = strtolower(substr($className,0,strlen($className)-strlen('Controller')));
		if(TRUE == spConfig('view.enabled')){	
			$this->v->display($dir.DIRECTORY_SEPARATOR.$tplname);
		}else{
			extract($this->__template_vals);
			require($dir.DIRECTORY_SEPARATOR.$tplname);
		}
		if( TRUE != $output )return ob_get_clean();
	}

	/**
	 * 自动输出页面
	 * @param tplname 模板文件路径
	 */
	public function auto_display($tplname)
	{
		if( TRUE != $this->v->displayed && FALSE != spConfig('view.auto_display')){
			if( method_exists($this->v->engine, 'templateExists') && TRUE == $this->v->engine->templateExists($tplname))$this->display($tplname);
		}
	}

	/**
	 * 魔术函数，实现对控制器扩展类的自动加载
	 */
	public function __call($name, $args)
	{
		if(in_array($name, spConfig("auto_load_controller"))){
			return spClass($name)->__input($args);
		}elseif(!method_exists( $this, $name )){
			spError("方法 {$name}未定义！<br />请检查是否控制器类(".get_class($this).")与数据模型类重名？");
		}
	}

	/**
	 * 获取模板引擎实例
	 */
	public function getView()
	{
		$this->v->addfuncs();
		return $this->v->engine;
	}
	/**
	 * 设置当前用户的语言
	 * @param $lang   语言标识
	 */
	public function setLang($lang)
	{
		if( array_key_exists($lang, spConfig("lang")) ){
			@ob_start();
			$domain = ('www.' == substr($_SERVER["HTTP_HOST"],0,4)) ? substr($_SERVER["HTTP_HOST"],4) : $_SERVER["HTTP_HOST"];
			setcookie(spConfig('sp_app_id')."_SpLangCookies", $lang, time()+31536000, '/', $domain ); // 一年过期
			$_SESSION[spConfig('sp_app_id')."_SpLangSession"] = $lang;
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * 获取当前用户的语言
	 */
	public function getLang()
	{
		if( !isset($_COOKIE[spConfig('sp_app_id')."_SpLangCookies"]) )return $_SESSION[spConfig('sp_app_id')."_SpLangSession"];
		return $_COOKIE[spConfig('sp_app_id')."_SpLangCookies"];
	}

	//文件上传
    public function _upload($name){
		if($_FILES){
			return model('Upload')->upload($name);
		}
		return '';
    }
}

/**
 * spArgs 
 * 应用程序变量类
 * spArgs是封装了$_GET/$_POST、$_COOKIE等，提供一些简便的访问和使用这些
 * 全局变量的方法。
 */

class spArgs {
	/**
	 * 在内存中保存的变量
	 */
	private $args = null;

	/**
	 * 构造函数
	 *
	 */
	public function __construct(){
		$this->args = $_REQUEST;
	}

	/**
	 * 获取应用程序请求变量值，同时也可以指定获取的变量所属。
	 * 
	 * @param name    获取的变量名称，如果为空，则返回全部的请求变量
	 * @param default    当前获取的变量不存在的时候，将返回的默认值
	 * @param method    获取位置，取值GET，POST，COOKIE
	 */
	public function get($name = null, $default = FALSE, $method = null)
	{
		if(null != $name){
			if( $this->has($name) ){
				if( null != $method ){
					switch (strtolower($method)) {
					case 'get':
						return $_GET[$name];
					case 'post':
						return $_POST[$name];
					case 'cookie':
						return $_COOKIE[$name];
					}
				}
				return $this->args[$name];
			}else{
				return (FALSE === $default) ? FALSE : $default;
			}
		}else{
			return $this->args;
		}
	}

	/**
	 * 设置（增加）环境变量值，该名称将覆盖原来的环境变量名称
	 * 
	 * @param name    环境变量名称
	 * @param value    环境变量值
	 */
	public function set($name, $value)
	{
		$this->args[$name] = $value;
	}

	/**
	 * 检测是否存在某值
	 * 
	 * @param name    待检测的环境变量名称
	 */
	public function has($name)
	{
		return isset($this->args[$name]);
	}

	/**
	 * 构造输入函数，标准用法
	 * @param args    环境变量名称的参数
	 */
	public function __input($args = -1)
	{
		if( -1 == $args )return $this;
		@list( $name, $default, $method ) = $args;
		return $this->get($name, $default, $method);
	}

	/**
	 * 获取请求字符
	 */
	public function request(){
		return $_SERVER["QUERY_STRING"];
	}
}

