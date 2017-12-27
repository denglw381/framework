<?php
#支持的请求方法
define('REQUEST_METHOD_GET',							'GET');
define('REQUEST_METHOD_POST',							'POST');
define('REQUEST_METHOD_HEAD',							'HEAD');

#请求行为
define('REQUEST_BEHAVIOR_ALLOW_REDIRECT',					'allow_redirect');
define('REQUEST_BEHAVIOR_MAX_REDIRECT',						'max_redirect');
define('REQUEST_BEHAVIOR_USER_AGENT',						'user_agent');
define('REQUEST_BEHAVIOR_AUTOREFERER',						'autoreferer');
define('REQUEST_BEHAVIOR_UPLOAD',						'upload');
define('REQUEST_BEHAVIOR_CONNECTTIMEOUT',					'connecttimeout');
define('REQUEST_BEHAVIOR_DNS_CACHE_TIMEOUT',					'dns_cache_timeout');
define('REQUEST_BEHAVIOR_TIMEOUT',						'timeout');
define('REQUEST_BEHAVIOR_ENCODING',						'encoding');
define('REQUEST_BEHAVIOR_ERROR_HANDLER',					'error_handler');
define('REQUEST_BEHAVIORS',							'behaviors');
if(!defined('COOKIE_FILE'))
define('COOKIE_FILE',								'/tmp/sp.curl.cookie');

$GLOBALS[REQUEST_BEHAVIORS]	= array(
	REQUEST_BEHAVIOR_ALLOW_REDIRECT				=> TRUE, 
	REQUEST_BEHAVIOR_MAX_REDIRECT				=> 10, 
	REQUEST_BEHAVIOR_USER_AGENT				=> 'api of api.weibo.com/curl extend of php/libcurl', 
	REQUEST_BEHAVIOR_AUTOREFERER				=> TRUE, 
	REQUEST_BEHAVIOR_UPLOAD					=> FALSE, 
	REQUEST_BEHAVIOR_CONNECTTIMEOUT				=> 10, 
	REQUEST_BEHAVIOR_DNS_CACHE_TIMEOUT			=> 3600, 
	REQUEST_BEHAVIOR_TIMEOUT				=> 10, 
	REQUEST_BEHAVIOR_ENCODING				=> 'gzip', 
	REQUEST_BEHAVIOR_ERROR_HANDLER				=> '__api_default_curl_error_handler', 
);

#响应键值
define('RESP_CODE',						'resp_code');
define('RESP_BODY',						'resp_body');
define('RESP_HEADER',						'resp_header');

#HTTP 1xx状态验证
define('HTTP_1XX_RESP',						'/^HTTP\/1.[01] 1\d{2} \w+/');

#默认错误处理的错误消息
define('E_CURL_ERROR_FMT',					'curl "%s" error[%d]: %s');

class SpCurl{

	static $instance = null;

	static function getInstance(){
		if(!self::$instance instanceof self){
			//self::$i
		}
	}

	#默认的curl错误处理
	function __api_default_curl_error_handler($curl, $url, $errno, $errstr) {
		trigger_error(sprintf(E_CURL_ERROR_FMT, $url, $errno, $errstr), E_USER_ERROR);
	}

	#切换CURL请求方法
	function __api_method_switch($curl, $method) {
		switch ( $method) {
		case REQUEST_METHOD_POST:
			$this->__api_curl_setopt($curl, CURLOPT_POST, TRUE);
			break;
		case REQUEST_METHOD_HEAD:
			$this->__api_curl_setopt($curl, CURLOPT_NOBODY, TRUE);
			break;
		case REQUEST_METHOD_GET:
			$this->__api_curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
			break;
		default:
			break;
		}
	}

	#设置默认头信息
	function __api_default_header_set($curl) {
		$this->__api_curl_setopt($curl, CURLOPT_RETURNTRANSFER,			TRUE);
		$this->__api_curl_setopt($curl, CURLOPT_HEADER,				TRUE);
		$this->__api_curl_setopt($curl, CURLOPT_FOLLOWLOCATION,			(bool)$this->api_behavior(REQUEST_BEHAVIOR_ALLOW_REDIRECT));
		$this->__api_curl_setopt($curl, CURLOPT_MAXREDIRS,			(int)$this->api_behavior(REQUEST_BEHAVIOR_MAX_REDIRECT));
		$this->__api_curl_setopt($curl, CURLOPT_USERAGENT,			(string)$this->api_behavior(REQUEST_BEHAVIOR_USER_AGENT));
		$this->__api_curl_setopt($curl, CURLOPT_AUTOREFERER,			(bool)$this->api_behavior(REQUEST_BEHAVIOR_AUTOREFERER));
		$this->__api_curl_setopt($curl, CURLOPT_UPLOAD,				(bool)$this->api_behavior(REQUEST_BEHAVIOR_UPLOAD));
		$this->__api_curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,			(int)$this->api_behavior(REQUEST_BEHAVIOR_CONNECTTIMEOUT));
		$this->__api_curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT,		(int)$this->api_behavior(REQUEST_BEHAVIOR_DNS_CACHE_TIMEOUT));
		$this->__api_curl_setopt($curl, CURLOPT_TIMEOUT,			(int)$this->api_behavior(REQUEST_BEHAVIOR_TIMEOUT));
		$this->__api_curl_setopt($curl, CURLOPT_ENCODING,			(string)$this->api_behavior(REQUEST_BEHAVIOR_ENCODING));
		$this->__api_curl_setopt($curl, CURLOPT_COOKIEJAR,			COOKIE_FILE);
//		$this->__api_curl_setopt($curl, CURLOPT_COOKIEFILE,			COOKIE_FILE);
		$this->__api_curl_setopt($curl, CURLOPT_COOKIESESSION,			true);
	}

	#设置用户自定义头信息
	function __api_custom_header_set($curl, $headers = NULL) {
		if ( empty($headers) ) return ;
		if ( is_string($headers) ) 
			$headers	= explode("\r\n", $headers);
		#类型修复
		foreach ( $headers as &$header ) 
			if ( is_array($header) ) 
				$header	= sprintf('%s: %s', $header[0], $header[1]);
		$this->__api_curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	}

	#设置请求body
	function __api_datas_set($curl, $datas = NULL) {
		if ( empty($datas) ) return ;
		$this->__api_curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
	}

	#对curl_setopt的封装
	function __api_curl_setopt($curl, $optname, $optval) {
		curl_setopt($curl, $optname, $optval);
		$this->__api_curl_error($curl);
	}

	#curl错误检查处理
	function __api_curl_error($curl) {
		if ( curl_errno($curl) ) {
			$url	= curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
			$errno	= curl_errno($curl);
			$errstr	= curl_error($curl);
			$errh	= $this->api_behavior(REQUEST_BEHAVIOR_ERROR_HANDLER);
			if ( function_exists($errh) )
				$errh($curl, $url, $errno, $errstr);
		}
	}

	#api默认行为切换
	function api_behavior($names, $values = NULL) {
		if ( !is_string($names) && !is_array($names) ) return ;
		if ( !is_null($values) ) {
			if ( is_string($names) ) 
				$GLOBALS[REQUEST_BEHAVIORS][$names]	= $values;
			else if ( is_array($names) && !is_array($values) )
				foreach ( $names as $name )
					$GLOBALS[REQUEST_BEHAVIORS][$name]	= $values;
			else if ( is_array($names) && is_array($values) )
				foreach ( $names as $k => $name ) 
					$GLOBALS[REQUEST_BEHAVIORS][$name]	= $values[$k];
		}
		if ( is_string($names) ) {
			$return	= $GLOBALS[REQUEST_BEHAVIORS][$names];
		} else if ( is_array($names) ) {
			$return	= array();
			foreach ( $names as $name ) 
				$return[$name]	= array_key_exists($name, $GLOBALS[REQUEST_BEHAVIORS]) 
				? $GLOBALS[REQUEST_BEHAVIORS][$name]
				: NULL;
		}
		return $return;
	}

	#请求入口
	function api_request($url, $method, $datas = NULL, $headers = NULL) {
		static $curl = null;
		if(is_null($curl)) $curl = curl_init();
		$this->__api_curl_setopt($curl,  CURLOPT_URL,	$url);
		$this->__api_method_switch($curl, $method);
		$this->__api_default_header_set($curl);
		$this->__api_custom_header_set($curl, $headers);
		$this->__api_datas_set($curl, $datas);
		$response	= curl_exec($curl);
		$this->__api_curl_error($curl);
		$status_code	= curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$components		= explode("\r\n\r\n", $response);
		$i				= -1;
		while ( ++ $i < count($components) ) 
			if ( !preg_match(HTTP_1XX_RESP, $components[$i]) ) break;
		$headers		= $components[$i];
		$body			= implode("\r\n\r\n", array_slice($components, $i + 1));
		$result = array(
			RESP_CODE	=> $status_code, 
			RESP_HEADER	=> $headers, 
			RESP_BODY	=> $body, 
		);
        SpLog::info(['url'=>$url, 'method'=>$method, 'datas'=>$datas, 'headers'=>$headers, 'result'=>$result]);
        return $result;
	}

	#GET请求
	function get($url, $datas = NULL, $headers = NULL) {
		if($datas){
			foreach($datas as $key=>$val){
				$args[] = $key.'='.$val;
			}
			$args = join('&', $args);
			$url = $url . '?' . $args;
		}
		return $this->api_request($url, REQUEST_METHOD_GET, NULL, $headers);
	}

	#POST请求
	function post($url, $datas = NULL, $headers = NULL) {
		return $this->api_request($url, REQUEST_METHOD_POST, $datas, $headers);
	}

	#HEAD请求
	function head($url, $headers = NULL) {
		return $this->api_request($url, REQUEST_METHOD_HEAD, NULL, $headers);
	}

	#读取响应码
	function resp_code($resp) {
		return $resp[RESP_CODE];
	}

	#读取响应头
	function resp_header($resp) {
		return $resp[RESP_HEADER];
	}

	#读取响应体
	function resp_body($resp) {
		return $resp[RESP_BODY];
	}
}
