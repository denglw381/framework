<?php
	$http = new HttpRequest('http://www.zhisland.com');
	$http->setCookie();
	$msg = $http->send();
	$resbody = $msg->getBody();
	//$resp = iconv("ISO-8859-1", "UTF-8", $resbody);
	var_dump($resp).PHP_EOL;
?>
