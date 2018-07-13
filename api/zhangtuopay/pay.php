<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//  掌托支付

//支付方式
$type = getServiceType($_REQUEST['type']);
//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);
//订单号
$order_id = date('YmdHis').rand(100000,999999);

$version = '3.0';/*接口版本号,目前固定值为3.0*/
$method = 'ZT.online.interface';/*接口名称: ZT.online.interface"*/
$partner = $app_id;//商户id,由API分配
$banktype = $type['code'];
$paymoney = $coin;//单位元（人民币）,两位小数点
$ordernumber = $order_id;//商户系统订单号，该订单号将作为接口的返回数据。该值需在商户系统内唯一
$callbackurl = $notify_url;//下行异步通知的地址，需要以http://开头且没有任何参数
$hrefbackurl = $return_url;//下行同步通知过程的返回地址(在支付完成后接口将会跳转到的商户系统连接地址)。注：若提交值无该参数，或者该参数值为空，则在支付完成后，接口将不会跳转到商户系统，用户将停留在接口系统提示支付成功的页面。
$goodsname= 'test';//商品名称。若该值包含中文，请注意编码
$attach = '1';//备注信息，下行中会原样返回。若该值包含中文，请注意编码


$data = [
    'version' => $version,
    'method' => $method,
    'partner' => $partner,
    'banktype' => $banktype,
    'paymoney' => $paymoney,
    'ordernumber' => $ordernumber,
    'callbackurl' => $callbackurl,
    'hrefbackurl' => $hrefbackurl,
    'goodsname' => $goodsname,
    'attach' => $attach,
    'isshow' => $isshow,
];

$signSource = sprintf("version=%s&method=%s&partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $version,$method,$partner, $banktype, $paymoney, $ordernumber, $callbackurl, $app_key);
$sign = md5($signSource);//32位小写MD5签名值，UTF-8编码

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
    'pay_api'     => '掌托支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

// 提交请求

if($type['action'] == 'jump'){
	echo submitFormMethod($data, $pay_request_url);exit;
}

$result = json_decode(postSend($pay_request_url, $data), true);

if($result['status'] !== '1'){
	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

	returnError();
}

//返回的  支付url
$payUrl = $result['qrurl'];

//if($type['action'] == 'jump'){
//	header('location:' . $payUrl);exit;
//}

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
