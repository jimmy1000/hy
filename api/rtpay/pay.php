<?php

include_once 'common.php';
require_once '../../pay_mgr/init.php';
include_once("./lib/class.bankpay.php");

error_reporting(E_ALL) ;

//必填参数
$data['parter']      = $pay_id ; //商户号
$data['type']        = getPayType($_REQUEST['type']); //支付类型

$data['value']       = sprintf('%.2f',$_REQUEST['coin']); //支付金额
$data['orderid']     = date("YmdHis").rand(100000,999999) ; //订单号
$data['callbackurl'] = $pay_callbackurl ; //异步支付地址
$data['sign']        = create_sign($data,$pay_key)    ;
$data['hrefbackurl'] = $pay_returnurl   ; //异步回调地址

//选填参数
$data['payerIp ']    = get_client_ip() ; //获取客户IP
$data['attach ']     = $_REQUEST['username'].'-'.$_REQUEST['type']; //备注


$pay_url = getLocalRequestUrl() ; //记录支付处理地址
//数据入库
$insertArr = [
    'order_id'    => $data['orderid'],
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sign'],
    'order_money' => $data['value'],
    'order_time'  => time(),
    'pay_api'     => '瑞通支付',
    'pay_url'     => $pay_url
] ;

if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

$html = submitFormMethod($data,$url) ; //表单提交
dd($html) ;

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