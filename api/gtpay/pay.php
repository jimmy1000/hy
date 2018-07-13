<?php
include './config.php';
/*
 * @Description 银宝接口 
 * @2017-01-04
 * @Author BY K
 */
/*
include '../include/db.php';
include '../include/mysqli.php';

*/
$banktype = $_REQUEST['type'];
 

if($banktype == "ALIPAY"){
	$banktype = "ALIPAY";
}
if($banktype == "WECHAT"){
	$banktype = "WEIXIN";
}
if($banktype == "ALIPAYWAP"){
	$banktype = "ALIPAYWAP";
}
if($banktype == "WAP"){
	$banktype = "WEIXINWAP";
}
if($banktype == "TENPAY"){
	$banktype = "TENPAY";
}
if($banktype == 'QQWAPPAY'){
   $banktype = 'QQPAYWAP';
}
if($banktype == 'JDPAY'){
  $banktype = 'JDPAY';
}
if($banktype == 'JDPAYWAP'){
  $banktype = 'JDPAYWAP';
}
if($banktype == 'QQCODE'){
    $banktype = 'QQCODE';
}
$order_no = $ordernumber =  $_REQUEST["username"]."_".date("YmdHis"); 


$uid = $_REQUEST['uid']; 

$paymoney 	=  isset($_REQUEST["coin"])? trim($_REQUEST["coin"]):'0';
$attach = $_REQUEST["username"]."|".$_REQUEST['type']."|".$$ordernumber;
//partner={}&banktype={}&paymoney={}&ordernumber={}&callbackurl={}key
$sign = md5("partner={$partner}&banktype={$banktype}&paymoney={$paymoney}&ordernumber={$ordernumber}&callbackurl={$callbackurl}".$merchantKey);
 
?> 
<html>
<head>
<title>银宝支付</title>
</head>
<body onLoad="document.diy.submit();">
<form name='diy' id="diy" action='<?php echo $gateWay; ?>' method='get'>
<input type='hidden' name='partner'	 value='<?php echo $partner; ?>'>
<input type='hidden' name='banktype'				value='<?php echo $banktype; ?>'>
<input type='hidden' name='paymoney'				value='<?php echo $paymoney; ?>'>
<input type='hidden' name='ordernumber'					value='<?php echo $ordernumber; ?>'>
<input type='hidden' name='callbackurl'					value='<?php echo $callbackurl; ?>'>
<input type='hidden' name='hrefbackurl'					value='<?php echo $hrefbackurl; ?>'>
<input type='hidden' name='attach'					value='<?php echo $attach; ?>'>
<input type='hidden' name='sign'				value='<?php echo $sign; ?>'> 
</form>
</body>
</html>