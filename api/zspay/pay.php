<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once  'common.php' ;

//  云付码支付

//支付方式
$type = getServiceType($_REQUEST['type']);
//支付金额
$coin = $_REQUEST['coin']*100;
//订单号
$order_id = date('YmdHis').rand(100000,999999);

$time=date("ymdhis",time());


//----------------------------银行表单数据-----------------------------//
if($_REQUEST['bank']!=""){
$data = [
    'merchantCode' =>$app_id ,				 //分配的商户号
    'outOrderId' => $order_id,				 //订单号
    'totalAmount' => $coin,				 //金额
    'orderCreateTime' => $time,				 //获取时间
    'merUrl' =>$local_url ,				 //同步通知
    'notifyUrl' => $notify_url,				 //异步通知URL
    "bankCode"=>getBankAbbreviation($_REQUEST['bank']),//支付银行代码
    "bankCardType"=>"01",//01借记卡

];

$sign = "merchantCode={$data['merchantCode']}&orderCreateTime={$data['orderCreateTime']}&outOrderId={$data['outOrderId']}&totalAmount={$data['totalAmount']}&KEY={$app_key}";
$data['sign'] = strtoupper(md5($sign));                        //签名

//数据入库
$insertArr = [
    'order_id'    => $order_id ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $sign,
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '泽圣支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];
if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

// 提交请求
	echo submitFormMethod($data, $pay_bank_url);exit;
}
//------------------------------------------------------------------------------//


//----------------------------扫码支付请求参数----------------------------------------//

$data = [
    'model  ' =>"QR_CODE" ,
    'merchantCode' =>$app_id ,				 //分配的商户号
    'outOrderId' => $order_id,				 //订单号
    'amount' => $coin,				 //金额
    'orderCreateTime' => $time,				 //获取时间
    'notifyUrl' => $notify_url,				 //异步通知URL
    "isSupportCredit"=>$type,//支付类型
    "ip"=>get_client_ip(),//获取ip

];

$sign = "merchantCode={$data['merchantCode']}&outOrderId={$data['outOrderId']}&amount={$data['amount']}&orderCreateTime={$data['orderCreateTime']}&noticeUrl={$data['noticeUrl']}&isSupportCredit={$data['isSupportCredit']}&KEY={$app_key}";
$data['sign'] = strtolower(md5($sign));                        //签名

//数据入库
$insertArr = [
    'order_id'    => $order_id ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $sign,
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '泽圣支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];
if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}


$result    =submitPostData($pay_scan_url,$data);



echo '<pre>';var_dump($result);echo '<hr>';die;
//返回的  支付url
$payUrl = $result['url'];


?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?= $type["desc"] ?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"> </script>
    <script src="js/jquery.qrcode.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text"><?= $type["desc"] ?>支付</span>
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
                <dd id="billId"><?= $order_id ?></dd>
            </dl>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan">
            </div>
            <div class="tip-text">
                <p>
                    请使用<?= $type["desc"] ?>扫一扫
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
            success: function(data) {
                if(data.result == '1'){
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
            text : '<?= $payUrl ?>', //任意内容
            background : "#ffffff",
        });
        setInterval("query('" + "<?= $order_id ?>" + "')",5000);
    })

</script>
</html>
