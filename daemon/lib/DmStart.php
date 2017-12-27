<?php
error_reporting(~(E_NOTICE | E_WARNING));
date_default_timezone_set('Asia/Chongqing');
define('DM_DEBUG',			true);
require_once dirname(__FILE__).'/DmLib.php';
if(!defined('DM_CLIENT'))
	DmDaemon::run();

