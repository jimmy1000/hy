<?php

include 'common.php';
require_once '../../pay_mgr/init.php';
require_once('rsa.class.php');
require_once('base.class.php');

$rsa  = new Rsa() ;
$base = new Base() ;
$words = '微信' ;

$data['member_code']   = $conf['member_code']; //商户号
$data['type_code']     = 'wxh5'; //支付类型(微信h5)
$data['down_sn']       = date("YmdHis").rand(100000,999999) ; //订单号
$data['subject']       = 'pay'; //主题
$data['amount']       = $_REQUEST['coin']; //支付金额
$data['notify_url']    = $pay_callbackurl; //异步回调地址
$data['remark ']     = $_REQUEST['username'].'-'.$_REQUEST['type']; //备注

//生成签名
$data['sign'] = $base->makeInSign($data, $conf['member_secret']) ;
//组合数据
$post = [
    'member_code' => $conf['member_code'],
    'cipher_data' => $rsa->encrypt($data, $conf['public_path']),
] ;

//记录支付处理地址
if (!$_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ;
} else {
    $pay_url = $_SERVER['HTTP_REFERER'] ;
}

//数据入库
$insertArr = [
    'order_id'    => $data['down_sn'],
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sign '],
    'order_money' => $data['amount'],
    'order_time'  => time(),
    'pay_api'     => '芒果支付',
    'pay_url'     => $pay_url
];
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

//提交到接口
$url = $conf['url'].'/api/trans/pay' ;
$res = $base->curlPost($url, $post)  ;

if($res['code'] == '0000') {
//    $code_url = $res['code_url'] ;
   header('Location:'.$res['code_url']) ;
} else {
    exit("<script>alert('".$res['msg']."');history.go(-1);</script>");
}

die;
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
            <span>￥</span><?=$data['amount']?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$res['down_sn'] ?></dd>
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
        setInterval("query('<?=$data['down_sn']?>')",5000);
    })

</script>
</html>