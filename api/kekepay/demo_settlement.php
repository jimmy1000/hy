<?php
/**
 * 结算结果查询 demo
 */

include_once realpath( __DIR__ )."/include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['outTradeNo']    = "201700000000";//商户订单号（T0/T1出款交易传商户出款订单号）

$res = $kekepay_class->Settlement($pram);

//成功
if($res['resultCode'] == "0000"){
	//更新数据库里面的打款记录
	
	exit("查询成功");
}else{
	die('查询失败，失败原因：'.$res['errMsg']);
}