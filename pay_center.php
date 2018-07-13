<?php

require_once 'pay_mgr/init.php';

//用户名
$username = $_POST['username'];


if(!preg_match("/^[0-9a-zA-Z]*$/",$username)){
	echo '<script>alert("会员帐号只能由数字、大小写字母组成!");window.location.href="index.html";</script>';
	 die;
}

//支付方式
$bankcode = $_POST['bank_code'];
//ALIPAY   支付宝
//WECHAT   微信支付
//BANK     手机网银
//WAP      微信APP

//银行代码
$bank = isset($_POST['radbank'])?$_POST['radbank']:'';

//支付金额
$transamt = isset($_POST["coin"])? trim($_POST["coin"]):'0.00';

$url = "api/";

$params = "username=".$username."&coin=".$transamt."&type=".$bankcode."&bank=".$bank."&banktype=".$bankcode;

$appSet = $database->get(DB_PREFIX.'set','*',array('id'=>1));

//var_dump($appSet);exit;
$msg_close = '您好，该支付方式已被关闭，请选择其它支付方式，或稍后访问！';
$msg_weihu = '您好，该支付方式正在维护中，请选择其它支付方式，或稍后访问！';

switch($bankcode){
	// 支付宝
	case 'ALIPAY':
		if($appSet['alipay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['alipay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['alipay_from'];
		if($api_name == "qfpay"){
			$url = "/api/qfpay/index.php";
			jumpUrl($url, $params);
		}
		break;

	// 支付宝app
	case 'ALIPAYWAP':
		if($appSet['alipaywap_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['alipaywap_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['alipaywap_from'];
		if($api_name == "kdpay"){
			$url = "http://pay1.gzjyqdzskj1.top/api/kdpay/pay.php";
			jumpUrl($url, $params);
		}
		if($api_name == "qfpay"){
			$url = "/api/qfpay/index.php";
			jumpUrl($url, $params);
		}
		if($api_name == "jmypay"){
			$url = "/api/jmypay/pay.php";
			jumpUrl($url, $params);
		}
		break;

	// 支付宝付款码
	case 'ALIPAYCODE':
		if($appSet['alipaycode_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['alipaycode_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['alipaycode_from'];
		break;

	//微信
	case 'WECHAT':
		if($appSet['wechat_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['wechat_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['wechat_from'];
		if($api_name == "qfpay"){
			$url = "/api/qfpay/index.php";
			jumpUrl($url, $params);
		}
		break;

	//微信App
	case 'WAP':
		if($appSet['wap_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['wap_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['wap_from'];
		if($api_name == "kdpay"){
			$url = "http://pay1.gzjyqdzskj1.top/api/kdpay/pay.php";
			jumpUrl($url, $params);
		}
		break;

	// 微信付款码
	case 'WEIXINCODE':
		if($appSet['wechatcode_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['wechatcode_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['wechatcode_from'];
		break;

	//财付通
	case 'TENPAY':
		if($appSet['tenpay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['tenpay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['tenpay'];
		break;

	// QQ扫码方式
	case 'QQPAY':
		if($appSet['qqpay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['qqpay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['qqpay'];
		if($api_name == "yfpay"){
			$url = "/api/yfpay/pay.php";
			jumpUrl($url, $params);
		}
		break;

	// 手机QQ钱包方式
	case 'QQWAPPAY':
		if($appSet['qqwappay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['qqwappay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['qqwappay'];
		if($api_name == "yfpay"){
			$url = "/api/yfpay/pay.php";
			jumpUrl($url, $params);
		}
		break;

	// 京东
	case 'JDPAY':
		if($appSet['jdpay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['jdpay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['jdpay'];
		break;

	// 京东app
	case 'JDPAYWAP':
		if($appSet['jdpaywap_open'] == "1"){
			returnErrTip($msg_close);
		}
		if($appSet['jdpaywap_weihu'] == "1"){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['jdpaywap_form'];
		break;

	// 点卡
	case 'DIANKAPAY':
		if($appSet['diankapay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['diankapay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['diankapay'];
		if($api_name == "kxpay"){
			$url = "/api/kxpay/pay.php";
			jumpUrl($url, $params);
		}
		break;

	//银行
	case 'BANK':
		if($appSet['bank_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['bank_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['bank_from'];
		if($api_name == "ddbpay"){
			$url = "/api/ddbpay/index.php";
			jumpUrl($url, $params);
		}
		break;

	// 网银WAP
	case 'BANKWAP':
		if($appSet['bankwap_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['bankwap_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['bankwap_form'];
		break;

	// 网银扫码
	case 'BANKSCAN':
		if($appSet['bankscan_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['bankscan_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['bankscan_form'];
		break;

	// 网银快捷
	case 'BANKQUICK':
		if($appSet['bankquick_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['bankquick_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['bankquick_form'];
		break;

	// 美团
	case 'MEITUANPAY':
		if($appSet['meituanpay_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['meituanpay_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['meituanpay_form'];
		break;

	// ???
	case 'KJZF':
		if($appSet['kjzf_open'] == '1'){
			returnErrTip($msg_close);
		}
		if($appSet['kjzf_weihu'] == '1'){
			returnErrTip($msg_weihu);
		}

		$api_name = $appSet['kjzf_form'];
		break;

	// 其他
	default:
		returnErrTip($msg_weihu);
		break;
}

$url .= $api_name."/pay.php";
jumpUrl($url, $params);

 ?>