<?php

include 'common.php';
require_once '../../pay_mgr/init.php';
require_once  './merchant.php' ;

$words = '' ; //支付类型描述

$merchant_code      = $merchant_id;//商户号，123001002003是测试商户号，调试时要更换商家自己的商户号
$service_type       = getPayType($_REQUEST['type'],$words);     //微信：weixin_scan 支付宝：alipay_scan qq钱包：tenpay_scan
$notify_url         = $pay_callbackurl;
$interface_version  = getVersionNumber($_REQUEST['type']); //版本号 默认V3.1必须大写
$client_ip          = get_client_ip();
$sign_type          = 'RSA-S'; //签名方式
$order_no           = date("YmdHis").rand(100000,999999) ; //订单号;
$order_time         = date('Y-m-d H:i:s');
$order_amount       = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$product_name       = 'online-pay' ; //商品名称
$extra_return_param = $_REQUEST['username'].'-'.$_REQUEST['type']; //备注

/////////////////////////////   参数组装  /////////////////////////////////
/**
除了sign_type dinpaySign参数，其他非空参数都要参与组装，组装顺序是按照a~z的顺序，下划线"_"优先于字母
 */
$signStr = "";

$signStr = $signStr."client_ip=".$client_ip."&";
if($extend_param != ""){
    $signStr = $signStr."extend_param=".$extend_param."&";
}
if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}

$signStr = $signStr."interface_version=".$interface_version."&";
$signStr = $signStr."merchant_code=".$merchant_code."&";
$signStr = $signStr."notify_url=".$notify_url."&";
$signStr = $signStr."order_amount=".$order_amount."&";
$signStr = $signStr."order_no=".$order_no."&";
$signStr = $signStr."order_time=".$order_time."&";

if($product_code != ""){
    $signStr = $signStr."product_code=".$product_code."&";
}
if($product_desc != ""){
    $signStr = $signStr."product_desc=".$product_desc."&";
}
$signStr = $signStr."product_name=".$product_name."&";
if($product_num != ""){
    $signStr = $signStr."product_num=".$product_num."&";
}
$signStr = $signStr."service_type=".$service_type;


/////////////////////////////   RSA-S签名  /////////////////////////////////
$merchant_private_key= openssl_get_privatekey($merchant_private_key);

openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);

$sign = base64_encode($sign_info);

$pay_url = getLocalRequestUrl() ; //获取本地支付请求地址

//数据入库
$insertArr = [
    'order_id'    => $order_no,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $sign,
    'order_money' => $order_amount,
    'order_time'  => time(),
    'pay_api'     => '智通宝支付',
    'pay_url'     => $pay_url
];
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}


/////////////////////////  提交参数到智通宝网关  ////////////////////////
/**
curl方法提交支付参数到网关https://api.ztbaopay.com/gateway/api/scanpay，并且获取返回值
 */

$postdata=array('extend_param'=>$extend_param,
    'extra_return_param'=>$extra_return_param,
    'product_code'=>$product_code,
    'product_desc'=>$product_desc,
    'product_num'=>$product_num,
    'merchant_code'=>$merchant_code,
    'service_type'=>$service_type,
    'notify_url'=>$notify_url,
    'interface_version'=>$interface_version,
    'sign_type'=>$sign_type,
    'order_no'=>$order_no,
    'client_ip'=>$client_ip,
    'sign'=>$sign,
    'order_time'=>$order_time,
    'order_amount'=>$order_amount,
    'product_name'=>$product_name);


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response=curl_exec($ch);
$res=simplexml_load_string($response);
curl_close($ch);

if ($res->response->resp_code !='SUCCESS') {
    exit("<script>alert('网络错误,请重试...');history.go(-1);</script>");
}

if (($res->response->result_code != 0)) {
    exit("<script>alert('网络错误,请重试...');history.go(-1);</script>");
}

$code_url = $res->response->qrcode ; //二维码地址
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?=$words?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"> </script>
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
            <span>￥</span><?=$postdata['order_amount']?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$postdata['order_no'] ?></dd>
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
    function query(orderid) {
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
            text : '<?=$code_url?>', //任意内容 background: "#ffffff",
            background : "#ffffff",
        });
        setInterval("query('<?=$postdata['order_no']?>')",5000);
    })

</script>
</html>