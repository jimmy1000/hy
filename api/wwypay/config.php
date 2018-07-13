<?php
//=======================卡类支付和网银支付公用配置==================
//商户ID
$merchant_id		= '1704'; //填写自己的

//通信密钥
$merchant_key		= 'e5a08a4e15e5488a9ef0b34afc2d1a8f';//填写自己的


//==========================卡类支付配置=============================
//支付的区域 0代表全国通用	
$restrict			= '0';


//接收下行数据的地址, 该地址必须是可以再互联网上访问的网址
$callback_url		= "http://payhy.8889s.com/api/wwypay/callback.php";
//$callback_url_muti  = "http://payhy.8889s.com/api/wwypay/callback/muti_card.php";

//======================网银支付配置=================================
//接收网银支付接口的地址
$bank_callback_url	= "http://payhy.8889s.com/api/wwypay/callback.php";


//网银支付跳转回的页面地址
$bank_hrefbackurl	= 'http://payhy.8889s.com/';


?>