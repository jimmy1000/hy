<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once './utils.php' ;

$words = '' ;
$data = array(
    'messageid'         => '200001',
    'out_trade_no'      => date("YmdHis").rand(100000,999999), //订单号
    'back_notify_url'   => $pay_callbackurl,
    'branch_id'         => $branch_id,
    'prod_name'         => 'pay',
    'prod_desc'         => 'pay',
    'pay_type'          => getPayType($words),
    'total_fee'         => $_REQUEST['coin'] * 100,
    'nonce_str'         => createNoncestr(32)
);
$data       = sign($data, $appKey) ;

$pay_url = getLocalRequestUrl() ; //获取客户支付地址
//数据入库
$insertArr = [
    'order_id'    => $data['out_trade_no'] ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sig'],
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '新码支付',
    'pay_url'     => $pay_url
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

$result     = httpPost($url, $data) ;
$resultJson = json_decode($result) ;

if ($resultJson->resultCode == '00' && $resultJson->resCode == '00') {
    $resultToSign = array();
    foreach ($resultJson as $key => $value) {
        if ($key != 'sign') {
            $resultToSign[$key] = $value;
        }
    }
    $str = formatBizQueryParaMap($resultToSign);
    $resultSign = strtoupper(md5($str."&key=".$appKey));

    if ($resultSign != $resultJson->sign) {
        exit("<script>alert('网络错误!');history.go(-1);</script>") ;
    }
} else {
    exit("<script>alert('".$resultJson->resDesc."');history.go(-1);</script>") ;
}

$code_url = $resultJson->payUrl ;

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
            <span>￥</span><?=$_REQUEST['coin']?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$data['out_trade_no'] ?></dd>
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
        setInterval("query('<?=$data['out_trade_no']?>')",5000);
    })

</script>
</html>

