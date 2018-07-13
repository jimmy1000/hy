<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
//error_reporting(E_ALL^E_NOTICE^E_WARNING);
$payType = getPayType($_REQUEST['type']);


//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);

//if($_REQUEST['type'] == 'WEIXINCODE' && $_REQUEST['WEIXINCODE_FLAG'] != 1){
//    //地址
//	$formAction = '?' . $_SERVER['QUERY_STRING'];
//$name = 'abc';
//    $html = include_once 'WEIXINCODE.php';
//    echo $html;die;
//}
//金海哲支付
//error_reporting(E_ALL^E_NOTICE^E_WARNING);
//支付方式


//订单号
$order_id = date('YmdHis').rand(100000,999999);

$data['merchantNo'] = $merchant_id; //商户号
$data['requestNo'] =  $order_id; //支付流水
$data['amount'] = $coin * 100;//金额（分）
$data['payMethod'] = $payType['code'];//业务编号
//$data['payMethod'] = 6003;//业务编号
$data['backUrl'] = $pay_callbackurl;   //服务器返回URL
$data['pageUrl'] = $hrefbackurl;   //页面返回URL
$data['payDate'] = time();   //支付时间，必须为时间戳
$data['agencyCode'] = '';   //分支机构号
$data['remark1'] = '1';
$data['remark2'] = '2';
$data['remark3'] = '3';

//  微信条码付款  参数
if($payType['code'] == 6013){
//	$data['authCode'] = uniqid();
	$data['authCode'] = 134576530461 + rand(100, 999);
	//134576530461
}
//  网银支付 参数
if($payType['code'] == 6002 ){
/*  对私借记卡： BankAccountType 值为 11 ;
    对私贷记卡： BankAccountType 值为 12 ;
    对私存折：   BankAccountType 值为 13 ;
    对公借记卡： BankAccountType 值为 21 ;
    对公贷记卡： BankAccountType 值为 22 ;
    对公存折：   BankAccountType 值为 23 ;*/
	$data['bankAccountType'] = '11';
	// 银行
	$data['bankType'] = getBankAbbreviation($_REQUEST['bank']);
	//测试  农业银行
//	$data['bankType'] = 1031000;
}

//生成sign
$signature = $data['merchantNo']."|".$data['requestNo']."|".$data['amount']."|".$data['pageUrl']."|".$data['backUrl']."|".$data['payDate']."|".$data['agencyCode']."|".$data['remark1']."|".$data['remark2']."|".$data['remark3'];

$pr_key = openssl_pkey_get_private($private_key);
$pu_key = openssl_pkey_get_public($public_key);

$sign = '';
//openssl_sign(加密前的字符串,加密后的字符串,密钥:私钥);
openssl_sign($signature,$sign,$pr_key);
openssl_free_key($pr_key);
$sign = base64_encode($sign);
$data['signature'] = $sign;




//数据入库
$insertArr = [
	'order_id'    => $order_id ,
	'user_name'   => $_REQUEST['username'],
	'pay_type'    => $_REQUEST['type'],
	'pay_ip'      => get_client_ip(),
	'sign'        => $sign,
	'order_money' => $coin,
	'order_time'  => time() ,
	'pay_api'     => '金海哲支付',
	'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
] ;

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
	exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}


//  微信条码付款  参数
if($payType['code'] == 6013){
    echo submitFormMethod($data,$payRequestUrl);
    echo '<pre>';var_dump(1);echo '<hr>';die;
}

if($payType['desc']=="网银"){

    $html=submitFormMethod($data,$payRequestUrl);
    echo   $html;exit;

}






// 提交请求
$result = json_decode(postSend($payRequestUrl, $data), true);


//  没有返回链接地址
if(!$result['backQrCodeUrl']){
	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL,FILE_APPEND);
	file_put_contents(dirname(__FILE__).'/err.log', var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);
    echo  $result['msg'];
	returnError();
}

//返回的  支付url
$payUrl = $result['backQrCodeUrl'];



switch($payType['action']){
    case 'jump':
		header('location:' . $payUrl);
		exit;
        break;
}

?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?= $payType['desc'] ?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"> </script>
    <script src="js/jquery.qrcode.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text"><?= $payType['desc'] ?>支付</span>
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
                    请使用<?= $payType['desc'] ?>扫一扫
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
