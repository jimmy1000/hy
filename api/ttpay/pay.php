<?php
//
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
$key='07a22975-bfc8-40d6-b03d-e48e28227873';
require_once('common.php');
$common = new COMMON();
$orderNo=date("YmdHis").rand(100000,999999);
$getIp=get_client_ip();
$coin=$_REQUEST["coin"];
$timestamp = time();
$nonce =$common->RandStr(8);
$arrayData = array(
    'order_trano_in' => $orderNo,
    'order_goods' => '测试',
    'order_amount' => $coin,
    'order_ip' => $getIp,
    'order_bank_code' => '',
    'order_openid' => '',
    'order_return_url' => 'http://www.baidu.com',
    'order_notify_url' => 'http://www.baidu.com'
);


$str = $common->ParameSort($arrayData);

$signature = md5($timestamp.$nonce.$str);

require_once('des.class.php');
$Des = new DES(strtoupper(substr(md5($timestamp.$key.$nonce),0,8)));

$post_data = $Des->encrypt(json_encode($arrayData));

$result = $common->send_post_md5('https://127.0.0.1/h5/PayOrder',$key,$timestamp,$nonce,$signature, $post_data);
echo var_dump($result);
//数据入库
$insertArr = [
    'order_id'    => $orderNo ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $order_info['sign'],
    'order_money' =>$_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '随便付',
    'pay_url'     => $_SERVER['REMOTE_ADDR'] , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
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
            <span>￥</span><?= $coin ?>        </div>
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
        setInterval("query('" + "<?= $orderNo ?>" + "')", 5000);
    })

</script>
</html>
