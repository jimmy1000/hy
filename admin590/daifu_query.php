<?php
include 'global.php';
error_reporting(E_ALL);
ini_set('display_errors',true);
$orderid = $_REQUEST['orderid'];
$rows = $database->get(DB_PREFIX.'xiafa','*',array('orderid'=>$orderid));
$file = dirname(__FILE__).'/../api/qspay/daifu.php';
include $file;
$daifu  = new daifu();
var_dump($daifu->query($orderid,$rows['money']));
?>