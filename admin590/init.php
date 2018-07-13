<?php
define('ROOT_PATH', dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)) . DIRECTORY_SEPARATOR);
define('FRAMEWORK_PATH', ROOT_PATH . 'core' . DIRECTORY_SEPARATOR);
$xymf_lib = realpath(ROOT_PATH . "../");
if (is_file($xymf_lib . "/lib/PHPExcel/PHPExcel.php")) {
    define('ExcelLib_PATH', $xymf_lib . '/lib' . DIRECTORY_SEPARATOR);
} else {
    define('ExcelLib_PATH', ROOT_PATH . 'core' . DIRECTORY_SEPARATOR);
}
require FRAMEWORK_PATH . 'function_core.php';
require FRAMEWORK_PATH . 'medoo.min.php';
require FRAMEWORK_PATH . 'config.php';
define('MAGIC_QUOTES_GPC', PHP_VERSION < '5.3.0' && function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc());
if (! MAGIC_QUOTES_GPC) {
    $_GET = $_GET ? daddslashes($_GET) : array();
    $_POST = $_POST ? daddslashes($_POST) : array();
    $_COOKIE = $_COOKIE ? daddslashes($_COOKIE) : array();
    $_FILES = $_FILES ? daddslashes($_FILES) : array();
}
date_default_timezone_set('PRC');
$http = $_SERVER['HTTP_HOST'];
$arr = explode(':', $http);
/*
if (! (strstr($arr[0], 'pay66657') || strstr($arr[0], 'pay77158') || strstr($arr[0], '77158'))) {
    // exit;
}
*/
$database = new medoo(array(
    'database_type' => DB_TYPE,
    'database_name' => DB_NAME,
    'server' => DB_HOST,
    'port' => DB_PORT,
    'username' => DB_USER,
    'password' => DB_PASSWD
));
error_reporting(0);
ob_start();
session_start();
