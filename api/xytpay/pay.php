<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
//迅游通支付

//支付方式
$type = getServiceType($_REQUEST['type']);

//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);
//订单号
$order_id = date('YmdHis').rand(100000,999999);

//请求地址

switch($type['urlType']){
    case 'scan':
		$requestUrl = $scanPayRequestUrl;

		$data = [
			'payKey' => $merchant_id,                           //商户支付Key
			'orderPrice' => $coin,                              //订单金额，单位：元保留小数点后两位
			'outTradeNo' => $order_id,                          //商户支付订单号（长度30以内）
			'productType' => $type['code'],                     //产品类型  支付类型
			'orderTime' => date('YmdHis'),              //下单时间，格式yyyyMMddHHmmss
			'productName' => '1',                               //支付产品名称
            'orderIp' => get_client_ip(),                       //下单ip
//			'orderIp' => gethostbyname($_SERVER['SERVER_NAME']),  //下单ip
			'returnUrl' => $hrefbackurl,                        //页面通知地址
			'notifyUrl' => $pay_callbackurl,                    //后台异步通知地址
//    'subPayKey' => '',                                //子商户支付Key，大商户时必填
			'remark' => '1',                                    //备注
		];

        break;
    case 'bank':
		$requestUrl = $bankPayRequestUrl;

        $data = [
			'payKey' => $merchant_id,                           //商户支付Key
			'orderPrice' => $coin,                              //订单金额，单位：元保留小数点后两位
			'outTradeNo' => $order_id,                          //商户支付订单号（长度30以内）
			'productType' => $type['code'],                     //产品类型  支付类型
			'orderTime' => date('YmdHis'),              //下单时间，格式yyyyMMddHHmmss
			'productName' => '1',                               //支付产品名称
			'orderIp' => get_client_ip(),                       //下单ip
            'bankCode' => getBankAbbreviation($_REQUEST['bank']),//银行编码
            'bankAccountType' => 'PRIVATE_DEBIT_ACCOUNT',       //支付银行卡类型    借记卡
//            'bankAccountType' => 'PRIVATE_CREDIT_ACCOUNT',      //支付银行卡类型    信用卡
			'returnUrl' => $hrefbackurl,                        //页面通知地址
			'notifyUrl' => $pay_callbackurl,                    //后台异步通知地址
			'remark' => '1',                                    //备注
        ];

		break;
}


ksort($data);
$sign = createSign($data + array('paySecret' => $appKey));

$data['sign'] = $sign;


// 提交请求
$result = json_decode(postSend($requestUrl, $data), true);

if($result['resultCode'] !== '0000'){
	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL,FILE_APPEND);
	file_put_contents(dirname(__FILE__).'/err.log', var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

	returnError();
}

//返回的  支付url
$payUrl = $result['payMessage'];

//数据入库
$insertArr = [
    'order_id'    => $order_id ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $result['sign'],
    'order_money' => $coin,
    'order_time'  => time() ,
    'pay_api'     => '迅游通支付',
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
            text : '<?= $payUrl ?>', //任意内容
            background : "#ffffff",
        });
        setInterval("query('" + "<?= $order_id ?>" + "')",5000);
    })

</script>
</html>
