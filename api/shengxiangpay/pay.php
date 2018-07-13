<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//  盛祥支付

//支付方式
$type = getServiceType($_REQUEST['type']);
//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);
//订单号
$order_id = date('YmdHis').rand(100000,999999);

$signStr = "userid={$app_id}&orderid={$order_id}&bankid={$type['code']}&keyvalue={$app_key}";
$sign = md5($signStr);


$signStr2 = "money={$coin}&userid={$app_id}&orderid={$order_id}&bankid={$type['code']}&keyvalue={$app_key}";
$sign2 = md5($signStr2);


$url = "{$pay_request_url}?userid={$app_id}&orderid={$order_id}&money={$coin}&url={$notify_url}&bankid={$type['code']}&&aurl={$return_url}&sign={$sign}&ext=123&sign2={$sign2}";


//数据入库
$insertArr = [
	'order_id'    => $order_id ,
	'user_name'   => $_REQUEST['username'],
	'pay_type'    => $_REQUEST['type'],
	'pay_ip'      => get_client_ip(),
	'sign'        => $sign,
	'order_money' => $coin,
	'order_time'  => time() ,
	'pay_api'     => '盛祥支付',
	'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
	exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

header('Location:'.$url);exit;
//echo '<pre>';echo($url);echo '<hr>';
//echo '<pre>';echo('http://api.shxpac.com/pay.aspx?userid=3419&orderid=2018041113460749163&money=10&url=http://goulai.top/api/sxpay/notify_url.php&bankid=2007&&aurl=http://goulai.top/api/sxpay/return_url.php&sign=7526ddf60c25ce3e5f6800b18e715dbd&ext=dc130_ALIPAYWAP&sign2=726b0bd509f7579a939952088f3f9a0a1');echo '<hr>';die;



/*$data['sign'] = $sign;
$data['ext'] = '1';
$data['sign2'] = $sign2;*/




// 提交请求

/*echo submitFormMethod($data, $pay_request_url);exit;
if($type['action'] == 'form'){
}*/



if($result['returncode'] !== '1'){
	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

	returnError();
}

//返回的  支付url
$payUrl = $result['url'];

if($type['action'] == 'jump'){
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
