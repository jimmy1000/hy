<?php
require("config.php");
include 'common.php';
include '../../pay_mgr/init.php';
$username = $_REQUEST['username'];
$coin = $_REQUEST['coin'];
$money=sprintf("%.2f",$coin);
$order=date("YmdHis").rand(100000,999999);
$p_data=array(
    'payKey'=>$apikey,
    'orderPrice'=>$money,
    'outTradeNo'=>$order,
    'productType'=>$payType,
    'orderTime'=>date('YmdHis'),
    'productName'=>'在线支付',
    'orderIp'=>getIP(),
    'returnUrl'=>$hrefbackurl,
    'notifyUrl'=>$callbackurl,
    'remark'=>'123'
);
$zd="notifyUrl=".$p_data['notifyUrl']."&orderIp=".$p_data['orderIp']."&orderPrice=".$money."&orderTime=".$p_data['orderTime']."&outTradeNo=".$order."&payKey=".$apikey."&productName=".$p_data['productName']."&productType=".$p_data['productType']."&remark=".$p_data['remark']."&returnUrl=".$hrefbackurl."&paySecret=".$paySecret;  //拼接字符串，里面的拼接字段必须全部用上。
$p_data['sign']=strtoupper(md5($zd));//MD5值必须大写
//数据入库
$insertArr = [
    'order_id'    => $order,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => getIP(),
    'sign'        => $p_data['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '汇合支付',
    'pay_url'     => getLocalRequestUrl()
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}
if ($isScan==1){
    $form=form($p_data,$FormUrl);
    echo $form;
    exit;
}


$postUrl=post($FormUrl,$p_data);
$json_array=json_decode($postUrl,true);

if($json_array['resultCode']=='0000'){
    $payCodeUrl=$json_array['payMessage'];
    if($isWAP){
        header("Location: $payCodeUrl");
        exit;
    }
}else{
    echo $json_array['errMsg'];exit;
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?= $words ?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script src="js/jquery.qrcode.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text"><?= $words ?>支付</span>
    </h1>
    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount">
            <span>￥</span><?= $money ?>       </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?= $orderNo ?></dd>
            </dl>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan">
            </div>
            <div class="tip-text">
                <p>
                    请使用<?= $words ?>扫一扫
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
        var datas = {"OrderId": orderid};
        $.ajax({
            url: 'queryOrder.php',
            cache: false,
            type: 'post',
            data: datas,
            dataType: 'json',
            success: function (data) {
                if (data.result == '1') {
                    alert('支付成功');
                    window.location.href = '/';
                }
            }
        });
    }

    $(function () {
        $('#barCode').empty();
        $('#barCode').qrcode({
            render: "canvas", //table方式
            width: 300, //宽度
            height: 300, //高度
            text: '<?= $payCodeUrl ?>', //任意内容
            background: "#ffffff",
        });
        setInterval("query('" + "<?= $order ?>" + "')", 5000);
    })

</script>
</html>


