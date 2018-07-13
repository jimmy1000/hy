<?php
$basedir = dirname(__FILE__).'/';
$merchant_private_key = file_get_contents($basedir.'rsa_private_key.pem');
$merchant_public_key = file_get_contents($basedir.'rsa_public_key.pem');
$dinpay_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCS+XhiuKRtpkNb05C9pEXyPFfm
OUOkakNmGRVlcR628PEKQ98Narqey+TdzhO9wp4cYudF8sWwUwxAzOdIO5FJkV3j
f9asHgAuh2EZA25D353HOyS/7Ulprc6Jn8FmAOKXl+QGFqxBThiS/yRsAkE10sAf
xDwZeAJEUSb5zRNcWwIDAQAB
-----END PUBLIC KEY-----'; 
$dinpay_public_key = file_get_contents($basedir.'dinpay_public_key.pem');
$merchant_code = '6000032828';
$notify_url = "http://pay.bocen.top/call/hyshbpay.php";//"http://" . $_SERVER['HTTP_HOST'] . "/api/shbpay/offline_notify.php";

$page_url = '';