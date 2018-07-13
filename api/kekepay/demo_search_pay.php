<?php
/**
 * 支付结果查询 demo
 */

include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['outTradeNo']    = "201700000000";//商户支付订单号

$res = $kekepay_class->searchPay($pram);

if($res['status'] == 200){
	echo "成功的数据：<br/>";
	var_dump($res['data']);
	die('<br/>获取成功');
}else{
	die('获取失败，失败原因：'.$res['msg']);
}