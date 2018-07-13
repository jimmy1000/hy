<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
//天奕支付

//支付方式
$type = getServiceType($_REQUEST['type']);
//请求地址
$requestUrl = $domain_name . $url[$type['urlType']];
//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);
//订单号
$order_id = date('YmdHis').rand(100000,999999);

switch($type['urlType']){
    case 'zhifubao':
    case 'weixin':
        // 微信扫码 京东扫码 不管用   2018年3月27日18:31:50
        $data = [
            'app_id' => $merchant_id,                   //商家号
            'pay_type' => $type['code'],                //支付方式
            'order_id' => $order_id,                    //商户订单号
            'order_amt' => $coin,                       //订单金额 取值范围（0.1 到 3000.00）
            'notify_url' => $pay_callbackurl,           //支付结果 异步通知URL
            'return_url' => $hrefbackurl,               //支付成功跳转地址， 须 URLEncode（utf-8）编码
            'time_stamp' => date('YmdHis')      //提交时间戳(格式为 yyyyMMddHHmmss 4 位年+2 位月+2 位日+2 位时+2 位分+2 位秒)
        ];

        $extendsArr = [
            'goods_name' => '1',                        //商品名称
            'goods_num' => '1',                         //商品数量
            'goods_note' => '1',                        //商品说明
            'extends' => '1'                            //商户自定义参数
        ];

	    break;
    case 'wangyin':
        $data = [
			'app_id' => $merchant_id,                   //商家号
            'bank_code' => $type['code'],               //银行缩写
            'order_id' => $order_id,                    //商户订单号
            'order_amt' => $coin,                       //订单金额不可为空，精确到分，单位：元
            'notify_url' => $pay_callbackurl,           //支付结果 异步通知URL
			'return_url' => $hrefbackurl,               //支付成功跳转地址， 须 URLEncode（utf-8）编码
            'time_stamp' => date('YmdHis')
        ];

        $extendsArr = [
            'card_type' => 1,                           //银行卡类型。1：借记卡2：贷记卡
			'extends' => '1',                            //商户自定义参数
			'goods_name' => '1',                        //商品名称
        ];

        break;
}



$sign = createSign($data + array('key' => $appKey));
$data += $extendsArr;
//数据签名
$data['sign'] = $sign ;

// 提交请求
$result = json_decode(postSend($requestUrl, $data), true);
//echo '<pre>';var_dump($data);echo '<hr>';
//echo '<pre>';var_dump($result);echo '</pre>';die;
if($result['status_code'] !== 0){
	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL,FILE_APPEND);
	file_put_contents(dirname(__FILE__).'/err.log', var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

	returnError();
}

//数据入库
$insertArr = [
    'order_id'    => $order_id ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $result['pay_seq'],   //平台流水号
    'order_money' => $coin,
    'order_time'  => time() ,
    'pay_api'     => '天奕支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
] ;

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}


switch($type['action']){
    case 'jump':
		header('location:' . $result['pay_url']);
		exit;
        break;
}

?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?= $type['desc'] ?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"> </script>
    <script src="js/jquery.qrcode.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text"><?= $type['desc'] ?>支付</span>
    </h1>
    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount">
            <span>￥</span><?= $coin ?>
        </div>
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
                    请使用<?= $type['desc'] ?>扫一扫
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
            text : '<?= $result["pay_url"] ?>', //任意内容
            background : "#ffffff",
        });
        setInterval("query('" + "<?= $order_id ?>" + "')",5000);
    })

</script>
</html>
