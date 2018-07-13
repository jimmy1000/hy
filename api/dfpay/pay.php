<?php

include 'common.php' ;
require_once '../../pay_mgr/init.php';

error_reporting(0);
header("Content-type: text/html; charset=utf-8");
$pay_memberid    = $merId ;   //商户ID
$pay_orderid     = date("YmdHis").rand(100000,999999);    //订单号
$pay_amount      = sprintf("%.2f",$_REQUEST['coin']) ;    //交易金额
$pay_applydate   = date('Y-m-d H:i:s') ;  //订单时间
$pay_notifyurl   =  $callbackUrl;   //服务端返回地址
$pay_callbackurl = $hrefcallbackUrl;  //页面跳转返回地址
$Md5key          = $security_key;   //密钥
$tjurl           = $payUrl;   //提交地址
$pay_bankcode    =  getPayType();   //银行编码微信扫码	902银联扫码	911 支付宝扫码	903
$words = '支付宝' ;


//扫码
$native = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl,
);
ksort($native);
$md5str = "";
foreach ($native as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}

$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$native["pay_md5sign"]     = $sign;
$native['pay_attach']      = $_REQUEST['username'].'-'.$_REQUEST['type'].'-'.$pay_bankcode.'-'.$native['pay_memberid'] ;
$native['pay_productname'] = 'online-pay';


//记录支付处理地址
if ( !$_SERVER['HTTP_REFERER'] ) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ;
} else {
    $pay_url = $_SERVER['HTTP_REFERER'] ;
}
//数据入库
$insertArr = [
    'order_id'    => $native['pay_orderid'],
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $native['pay_md5sign'],
    'order_money' => $native['pay_amount'],
    'order_time'  => time(),
    'pay_api'     => '东方支付',
    'pay_url'     => $pay_url
] ;


if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

 $resutl = submitPostData($payUrl,$native) ;
 $resutl = json_decode($resutl,true) ;
 if (empty($resutl['data'])) {
     exit("<script>alert('网络延迟,请重试...');history.go(-1);</script>");
 }
 $code_url = '';
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
            <span>￥</span><?=$pay_amount?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$native['pay_orderid'] ?></dd>
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
        setInterval("query('<?=$native['pay_orderid']?>')",5000);
    })

</script>
</html>