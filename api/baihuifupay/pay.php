<? header("content-Type: text/html; charset=UTF-8");?>
<?php
    require_once '../../pay_mgr/init.php';
	require_once(dirname(__FILE__).'/'.'config.php');
	require_once(dirname(__FILE__).'/'.'ccpay.class.php');

	// 请求数据赋值
	$data = array();

	$data['version']=$version;
	$data['merchantId']=$merchant_id;#商户号
	switch ($_REQUEST['type']){
	    case 'BANK':
	        $data['busType']="00";#支付类型，可选
	        break;
	    case 'WAP':
	        $data['busType']="05";#支付类型，可选
	        break;
	    case 'WECHAT':
	        $data['busType']="07";#支付类型，可选
	        break;
	    case 'QQPAY':
	        $data['busType']="08";#支付类型，可选
	        break;
	    case 'QQPAYWAP':
	        $data['busType']="09";#支付类型，可选
	        break;
	    case 'BANKWAP':
	        $data['busType']="11";#支付类型，可选
	        break;
	    case 'BANKQUICK':
	        $data['busType']="11";#支付类型，可选
	        break;
	    case 'BANKSCAN':
	        $data['busType']="21";#支付类型，可选
	        break;
	    case 'ALIPAY':
	        $data['busType']="06";#支付类型，可选
	        break;
	    case 'ALIPAYWAP':
	        $data['busType']="04";#支付类型，可选
	        break;
	}
	
	$data['orderId']=strtoupper(uniqid('BHF'));#商户订单号
	$data['orderAmt']=$_REQUEST['coin'];#金额，单位:元
	$data['orderTime']=date( 'YmdHis' );
	$data['goodsName']='iphonex';
	$data['notifyUrl']=$notify_url; #支付成功回调地址
	$data['signType']=$sign_type;
	
	// 初始化
	$ccpay = new CCPay($mer_key);
	// 准备待签名数据
	$str_to_sign = $ccpay->prepareSign($data);
	// 数据签名
	$sign = $ccpay->sign($str_to_sign);
	$data['sign'] = $sign;
	
	if (! $_SERVER['HTTP_REFERER']) {
	    $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
	} else {
	    $url = $_SERVER['HTTP_REFERER'];
	}
	$insertArr = array(
	    'order_id' => $data['orderNo'],
	    'user_name' => $_REQUEST['username'],
	    'pay_type' => $_REQUEST['type'],
	    'pay_ip' => get_client_ip(),
	    'sign' => $data['sign'],
	    'order_money' => $_REQUEST['coin'],
	    'order_time' => time(),
	    'pay_api' => '百汇付支付',
	    'pay_url' => $url
	);
	if (! $database->insert(DB_PREFIX . 'preorder', $insertArr)) {
	    // exit("<script>alert('创建订单失败!');history.go(-1);</script>");
	}
	// 生成表单数据
	echo $ccpay->buildForm($data, $pay_url);
	
?> 
