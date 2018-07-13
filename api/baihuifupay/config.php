<?php
	/*--请在这里配置您的基本信息--*/
	
	/****以下信息以实际为准****/
	
	// 支付接口版本
	$version = "V2.0";
	// 商户号（支付平台）
	$merchant_id = "8866622043";
	// 商户密钥（支付平台）
	$mer_key = "75e6b4e304e6444b91e7efb12105360a";
	// 签名类型
	$sign_type = "MD5";
	// 支付网关地址
	$pay_url = "http://pay.bigeee.pw/pay/gateway";
	// 商户通知地址（请根据自己的部署情况设置下面的路径）
	$notify_url = "http://".$_SERVER["HTTP_HOST"]."/api/baihuifupay/callback.php";
?>