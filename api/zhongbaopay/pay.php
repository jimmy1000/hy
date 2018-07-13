<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//  众宝支付

//支付方式
$type = getServiceType($_REQUEST['type']);
//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);
//订单号
$order_id = date('YmdHis').rand(100000,999999);

//请求地址
$pay_request_url = $pay_query_url_arr[$type['urlType']];
if(!$pay_request_url){
    returnError();
}

// 支付请求参数
switch($type['urlType']){
    case 'wap':
        $data = [
            'merchantid' => $app_id, //商户号
            'paytype' => $type['code'], //通道类型
            'amount' => $coin, //金额
            'orderid' => $order_id, //商户订单号
            'notifyurl' => $notify_url, //下行异步通知地址
            'request_time' => date('YmdHis'), //时间
        ];
        break;
    case 'kj':
        $data = [
            'merchantid' => $app_id, //商户号
            'amount' => $coin, //金额
            'orderid' => $order_id, //商户订单号
            'notifyurl' => $notify_url, //异步通知地址
            'request_time' => date('YmdHis'), //时间
        ];
        break;
    case 'wxbarcode':
        $data = [
            'merchantid' => $app_id, //商户号
            'amount' => $coin, //金额
            'orderid' => $order_id, //商户订单号
            'notifyurl' => $notify_url, //异步通知地址
            'request_time' => date('YmdHis'), //时间
        ];
        break;
    case 'fix':
        $data = [
            'merchantid' => $app_id, //商户号
            'paytype' => $type['code'], //通道类型
            'orderid' => $order_id, //商户订单号
            'notifyurl' => $notify_url, //异步通知地址
            'request_time' => date('YmdHis'), //时间
        ];
        break;
    default:
        returnError();
}
$sign_key = 'key';

if($sign_key){
    $sign = create_sign($data, array($sign_key => $app_key));
}else{
    $sign = create_sign($data, $app_key);
}

$data['returnurl'] = $return_url;  //同步通知地址
if($type['urlType'] == 'wap'){
	$data['isqrcode'] = 'Y'; //返回二维码(wap 不生效)
	$data['israndom'] = 'N'; //返回二维码(wap 不生效)
}elseif($type['urlType'] == 'kj'){
	$data['bankcode'] = ''; //支付银行 (如为空则需要在众宝支付页选择对应的银行)
}
$data['sign'] = $sign;

//数据入库
$insertArr = [
    'order_id'    => $order_id ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $sign,
    'order_money' => $coin,
    'order_time'  => time() ,
    'pay_api'     => '众宝支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

// 提交请求

if($type['action'] == 'form'){
	echo submitFormMethod($data, $pay_request_url);exit;
}

//$result = json_decode(postSend($pay_request_url, $data), true);
$result = json_decode(submitPostData($pay_request_url, $data), true);
//echo '<pre/>';var_dump($pay_request_url);echo '<hr>';
//echo '<pre/>';var_dump( $data);echo '<hr>';
//echo '<pre/>';var_dump($result);echo '<hr>';die;

if($result['code'] !== 0){
	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

	returnError();
}


$payUrl = $result['qrinfo'];

//返回的  支付url
if($result['qrtype'] != 'qrcode'){
	header('location:' . $payUrl);exit;
}

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
