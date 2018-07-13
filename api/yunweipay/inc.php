<?php
header('Content-Type:text/html;charset=utf8');
date_default_timezone_set('Asia/Shanghai');

$userid='10942';
$userkey='a92cdef308c8db2f9e1adeb12134d5506d71e089';
$hrefback = 'http://'.$_SERVER['HTTP_HOST'].'/api/yunweipay/hrefback.php';
$callback = 'http://'.$_SERVER['HTTP_HOST'].'/api/yunweipay/callback.php';
?>
