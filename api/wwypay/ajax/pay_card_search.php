<?php
error_reporting(0);
header('Content-Type:text/html;charset=GB2312');
include_once("../config/pay_config.php");
include_once("../lib/class.cardgpay.php");
$cardpay = new cardpay();
$cardpay->parter 		= $merchant_id;		//�̼�Id
$cardpay->key 			= $merchant_key;	//�̼���Կ

$result	= $cardpay->search($_POST['order_id']);

$data = '{"success": "'.$result.'","message": "'. $cardpay->message .'"}';
die($data);
?>