<?php
set_time_limit(0);
define("WEB_ROOT",	dirname(__FILE__));
define("APP_APPLICATION_PATH", WEB_ROOT.'/../application');
define("SP_PATH",	WEB_ROOT.'/../framework');
$spConfig = require_once( WEB_ROOT.'/../config/main.php');
require_once SP_PATH.DIRECTORY_SEPARATOR.'framework.php';
if(!defined('IN_CMS'))
spRun(); 
if(function_exists('xhprof_run')) xhprof_run();
/*
$xhprof_data = xhprof_disable();
include_once "xhprof_lib.php";
include_once "xhprof_runs.php";
$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_testing");
spLog::xhprof("http://xhprof.zhisland.com/?run={$run_id}&sort=fn&source=xhprof_testing&all=1");
*/
