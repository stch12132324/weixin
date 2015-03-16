<?php
// +----------------------------------------------------------------------
// +@ Framework 入口文件
// +@ By Stch12132324
// +@ Time:2014-08-08
// +----------------------------------------------------------------------

define('BJ_ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -3)));
define('MICROTIME_START',microtime());
define('TIME', time());
ini_set("magic_quotes_runtime",0);
unset($HTTP_ENV_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_POST_FILES, $HTTP_COOKIE_VARS);
/*
@ 包含文件
*/
require BJ_ROOT.'Config/config.inc.php';
include BJ_ROOT.'Lib/Common/common.php';
include BJ_ROOT.'Lib/Common/functions.php';
include BJ_ROOT.'Lib/Core/App.class.php';
/*
@ 定义系统常量
*/
define('IP', ip());
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
define('SCRIPT_NAME', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : preg_replace("/(.*)\.php(.*)/i", "\\1.php", $_SERVER['PHP_SELF']));
define('QUERY_STRING', $_SERVER['QUERY_STRING']);
define('PATH_INFO', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');
define('DOMAIN', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : preg_replace("/([^:]*)[:0-9]*/i", "\\1", $_SERVER['HTTP_HOST']));
define('RELATE_URL', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : SCRIPT_NAME.(QUERY_STRING ? '?'.QUERY_STRING : PATH_INFO));
if(function_exists('date_default_timezone_set')) date_default_timezone_set(TIMEZONE);
header('Content-type: text/html; charset='.CHARSET);
/*
@ 开始运行，进行路由
*/
App::run();
?>