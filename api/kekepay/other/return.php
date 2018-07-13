<?php
/**
 * 支付成功，同步页面（用户看到的页面）
 */

include_once realpath( __DIR__ )."/../include/kekepay_class.inc.php";
$kekepay_class = new kekepay_class();

$pram = array();
$pram['payKey']       = trim($_REQUEST['payKey']);//商户支付Key
$pram['orderPrice']   = $_REQUEST['orderPrice'];//订单金额，单位：元保留小数点后两位
$pram['outTradeNo']   = trim($_REQUEST['outTradeNo']);//商户订单号
$pram['productType']  = trim($_REQUEST['productType']);//产品类型
$pram['orderTime']    = trim($_REQUEST['orderTime']);//下单时间，格式yyyyMMddHHmmss
$pram['tradeStatus']  = trim($_REQUEST['tradeStatus']);//订单状态
$pram['successTime']  = trim($_REQUEST['successTime']);//成功时间，格式yyyyMMddHHmmss
$pram['trxNo']        = trim($_REQUEST['trxNo']);//交易流水号

if(isset($_REQUEST['remark']) && !empty($_REQUEST['remark'])){
	$pram['remark'] = trim($_REQUEST['remark']);//订单备注
}
if(isset($_REQUEST['productName']) && !empty($_REQUEST['productName'])){
	$pram['productName']  = trim($_REQUEST['productName']);//商品名称
}
//重新加密过后的 sign
$_sign = $kekepay_class->resSign($pram);
$sign = $_REQUEST['sign'];

//为了安全起见，判断sign值是否正确
if($_sign != $sign){
	//校验失败
	exit;
}

//支付成功
if($pram['tradeStatus'] == "SUCCESS"){
	//跳到支付成功页面
	
}elseif($pram['tradeStatus'] == "FAILED"){
	//跳到支付失败页面
	
}else{
	//跳到支付中页面
	
}











