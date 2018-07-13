<?php

include 'common.php' ;
require_once '../../pay_mgr/init.php';


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 15:04
 */
$type   = getPayType($_REQUEST['type']) ;
$amount =  sprintf("%.2f",$_REQUEST['coin']) ; //格式化支付金额
$data['cus_orderno']  = date("YmdHis").rand(100000,999999); //订单号
$data['merchant_no']  = $merchant_no ;
$data['msg']          = $_REQUEST['username'].'-'.$_REQUEST['type'].'-'.$type.'-'.$data['cus_orderno']  ; //备注信息
$data['order_type']   = $type   ; //支付类型
$data['trans_amount'] = $amount ; //支付金额
$data['sign']         = create_sign($data,$key) ; //创建签名
$data['ip']           = get_client_ip() ;         //IP地址

//记录支付处理地址
if ( !$_SERVER['HTTP_REFERER'] ) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ;
} else {
    $pay_url = $_SERVER['HTTP_REFERER'] ;
}

//数据入库
$insertArr = [
    'order_id'    => $data['cus_orderno'],
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sign'],
    'order_money' => $data['trans_amount'],
    'order_time'  => time(),
    'pay_api'     => '便利付',
    'pay_url'     => $pay_url
] ;

if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

$result = submitPostData($request_url,$data) ;
$result = json_decode($result,true)   ;

if ( !isset($result['qrcode']) ) {
    exit("<script>alert('网络错误请重试');history.go(-1);</script>");

} else {
    if ($_REQUEST['type'] == 'WAP') {
        header('Location:'.$result['qrcode']) ;
        die;
    }
    $code_url = $result['qrcode'] ;
    $words    = '微信' ;
}
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
            <span>￥</span><?=$amount?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$data['cus_orderno'] ?></dd>
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
        setInterval("query('<?=$data['cus_orderno']?>')",5000);
    })

</script>
</html>