<?php
error_reporting(E_ERROR);
include 'config.php';
$username = trim($_REQUEST['username']);
$orderid =  date("YmdHis").rand(100000,999999);
$money = $_REQUEST['coin'];
$type = $_REQUEST['type'];
if ($type == 'WECHAT') {
    $type = 'weixin_scan';
} elseif ($type == 'ALIPAY') {
        $type = 'alipay_scan';
}elseif($type == 'QQPAY'){
  $type = 'tenpay_scan';
}
$attach = $username .'-'.$type;
//include ('phpqrcode.php');
//$uid = intval($_REQUEST['uid']);
$username = $_REQUEST['username'];

/**
 * 获取客户端IP地址
 * 
 * @param integer $type
 *            返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 *
 */
function get_client_ip($type = 0)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array(
        $ip,
        $long
    ) : array(
        '0.0.0.0',
        0
    );
    return $ip[$type];
}
/*
 * 功能：智汇付微信 支付宝通用扫码支付接口
 * 版本：3.0
 * 日期：2016-07-10
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 * 并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 */

// ///////////////////////// 初始化接口参数 //////////////////////////////
/**
 * 接口参数请参考智汇付微信支付文档，除了sign参数，其他参数都要在这里初始化
 */

// $merchant_code = $merchant_code;//商户号，1118004517是测试商户号，调试时要更换商家自己的商户号

$service_type = $type; // 微信：weixin_scan 支付宝：alipay_scan
if ($service_type == 'weixin_scan') {
    $words = '微信';
}elseif ($service_type == 'tenpay_scan') {
    $words = 'QQ扫码';
}  else {
    $words = '支付宝';
}



$interface_version = 'V3.1';

$client_ip = get_client_ip();

$sign_type = 'RSA-S';

$order_no = $orderid;

$order_time = date("Y-m-d H:i:s");

$order_amount = $money;

$product_name = 'ONLINE PAY';

$product_code = $_POST["product_code"];

$product_num = $_POST["product_num"];

$product_desc = $_POST["product_desc"];

$extra_return_param = $attach;

$extend_param = '';

// /////////////////////////// 参数组装 /////////////////////////////////
/**
 * 除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */

$signStr = "";

$signStr = $signStr . "client_ip=" . $client_ip . "&";

if ($extend_param != "") {
    $signStr = $signStr . "extend_param=" . $extend_param . "&";
}

if ($extra_return_param != "") {
    $signStr = $signStr . "extra_return_param=" . $extra_return_param . "&";
}

$signStr = $signStr . "interface_version=" . $interface_version . "&";

$signStr = $signStr . "merchant_code=" . $merchant_code . "&";

$signStr = $signStr . "notify_url=" . $notify_url . "&";

$signStr = $signStr . "order_amount=" . $order_amount . "&";

$signStr = $signStr . "order_no=" . $order_no . "&";

$signStr = $signStr . "order_time=" . $order_time . "&";

if ($product_code != "") {
    $signStr = $signStr . "product_code=" . $product_code . "&";
}

if ($product_desc != "") {
    $signStr = $signStr . "product_desc=" . $product_desc . "&";
}

$signStr = $signStr . "product_name=" . $product_name . "&";

if ($product_num != "") {
    $signStr = $signStr . "product_num=" . $product_num . "&";
}

$signStr = $signStr . "service_type=" . $service_type;

// /////////////////////////// RSA-S签名 /////////////////////////////////

// ///////////////////////////////初始化商户私钥//////////////////////////////////////

$merchant_private_key = openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr, $sign_info, $merchant_private_key, OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);
// /////////////////////// 提交参数到智汇付网关 ////////////////////////

/**
 * curl方法提交支付参数到智汇付网关https://api.zhihpay.com/gateway/api/scanpay，并且获取返回值
 */

$postdata = array(
    'extend_param' => $extend_param,
    'extra_return_param' => $extra_return_param,
    'product_code' => $product_code,
    'product_desc' => $product_desc,
    'product_num' => $product_num,
    'merchant_code' => $merchant_code,
    'service_type' => $service_type,
    'notify_url' => $notify_url,
    'interface_version' => $interface_version,
    'sign_type' => $sign_type,
    'order_no' => $order_no,
    'client_ip' => $client_ip,
    'sign' => $sign,
    'order_time' => $order_time,
    'order_amount' => $order_amount,
    'product_name' => $product_name
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.zfbill.net/gateway/api/scanpay");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$res = simplexml_load_string($response);
//var_dump($res);
if($res['resp_code']=='FAIL'){
  echo("<script>alert('系统维护:请尝试其他支付方式!');window.location='/';</script>");exit();
}
if (curl_error($ch)) {
    var_dump( curl_errno($ch));
}
curl_close($ch);

/**
 * 解析智付返回参数，获取qrcode的值，并且根据这个值生成二维码
 */

$resp_code = $res->response->resp_code;

if ($resp_code == "SUCCESS") {
    $qrcode = $res->response->qrcode;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="zh-cn">
<title><?=$words?>扫码支付</title>
<link href="./wechat_pay.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script src="js/jquery.qrcode.min.js"></script>

</head>
<body>
<div class="body">
	<h1 class="mod-title">
	<span class="ico-wechat"></span><span class="text"><?=$words?>支付</span>
	</h1>
	<div class="mod-ct">
		<div class="order">
		</div>
		<div class="amount">
			<span>￥</span><?=number_format($_REQUEST['coin'],2)?>
		</div>
		<div class="qr-image" style="">
		  <div id="barCode" style="margin-left: 155px;">
		     
		  </div>
		</div>
		<!--detail-open 加上这个类是展示订单信息，不加不展示-->
		<div class="detail detail-open" id="orderDetail" style="">
			<dl class="detail-ct" style="display: block;">				
				<dt>交易单号</dt>
				<dd id="billId"><?=$order_no?></dd>
			</dl>			
		</div>
		<div class="tip">
			<span class="dec dec-left"></span>
			<span class="dec dec-right"></span>
			<div class="ico-scan">
			</div>
			<div class="tip-text">
				<p>
					请使用<?=$words?>扫一扫
				</p>
				<p>
					扫描二维码完成支付
				</p>
			</div>
		</div>
	</div>
	<div class="foot">
		<div class="inner">
			<p>
			</p>
			
		</div>
	</div>
</div>
</body>
<script>

function query(orderid){
	var datas = 'OrderId='+orderid;
    $.ajax({
        url: 'queryOrder.php',
        method: 'post',
        data: datas,
        dataType: 'json',
        success: function(data) {
            if(data.status == '1'){
                alert('支付成功');
                window.location.href = '/';
            }     
        }
    });
}
  $(function(){
	  $('#barCode').empty();                   
      $('#barCode').qrcode({
			render : "canvas", //table方式 
			width : 300, //宽度 
			height : 300, //高度 
			text : '<?=$qrcode?>', //任意内容 background: "#ffffff",
			background : "#ffffff",
		});
	  setInterval("query('<?=$order_no?>')",5000); 
  })    
</script>
</html>