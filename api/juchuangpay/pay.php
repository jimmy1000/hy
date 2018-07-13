<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//  聚创支付



//支付方式
$type = getServiceType($_REQUEST['type']);


//判断金额

//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);
//$coin = $_REQUEST['coin'];

if(!in_array($coin, $coinArr)){
    $dviStr = '';
    foreach($coinArr as $value){
		$dviStr .= '<div class="span2 thumbnail" data-coin="' . $value . '">' . $value .'</div>';
    }
	echo <<<TOO
    <link rel="stylesheet" type="text/css" href="/admin590/lib/bootstrap/css/bootstrap.css">
    <script src="/admin590/lib/jquery-1.7.2.min.js" type="text/javascript"></script>
  
    <h3 style="text-align:center;color:#bd362f" class="text-center">金额不符合, 请选择以下金额, 或返回选择其他支付方式!</h3>
    <div class="thumbnail" style="width: 80%;margin: 0 auto;">
        <div class="row" style="">
            {$dviStr}
        </div>
    </div>

    <script >
        $('.span2').css({
            height: '50px',
            marginTop: '10px' , 
            background: '#dceaf4',
            cursor: 'pointer',
            fontSize: '30px',
            textAlign: 'center',
            lineHeight: '50px',
            color: '#666'
        });
        $('.span2').hover(function(){         
            $(this).css({
                background: '#bbd8e9',
            })
        },function(){
            $(this).css({
                background: '#dceaf4',
            })
        })
        
        $('.span2').click(function(){
            var coin = $(this).data('coin');
            window.location.href = window.location.href + '&coin=' + coin;
        })        
    </script>
TOO;
exit;
}

if($coin < 20){
	returnErrTip('您好，该支付方式 金额 最低为20元！');

}

if($coin % 10 != 0){
	returnErrTip('您好，该支付方式 金额 必须为10的整数！');
}

//订单号
$order_id = date('YmdHis').rand(100000,999999);
//请求地址
$pay_request_url = $pay_query_url_arr[$type['urlType']];
if(!$pay_request_url){
	returnError();
}

//请求参数
switch($type['urlType']){
	case 'qq':
		$data = [
			//支付请求参数
			'merchno' => $app_id,				    //商户号
			'amount' => $coin,				        //交易金额
			'traceno' => $order_id,				    //商户订单号
			'payType' => $type['code'],				//支付方式
			'notifyUrl' => $notify_url,				//异步通知地址
			'cust1' => $return_url,				    //同步通知地址
			'settleType' => '1',				    //结算方式
		];
		break;
	case 'ali':
	    $data = [
            'notifyUrl' => $notify_url,
            'outOrderNo'=> $order_id,
            'goodsClauses' => 'test',
            'tradeAmount' => $coin,
            'code' => $app_id,
            'payCode' => $type['code'],
		];
		break;
	case 'kj':
		$data = [
			'version' => '1.0.0',
			'transCode'=> '8888',
			'merchantId' => $app_id,                //需商户自行替换自己商户号
			'merOrderNum' => $order_id,
			'bussId' => $type['code'],
			'tranAmt' => $coin,                     //交易金额 分 最小为10
			'sysTraceNum' => $order_id,
			'tranDateTime'=> date('YmdHis'),
			'currencyType' => '156',
			'merURL' => $return_url,
			'backURL' => $notify_url,
			'orderInfo' => '',
			'userId'=> '',
			'userPhoneHF' => '13011111111',//开户手机号码
			'userAcctNo'=> '此处填写银行卡号',//银行卡号
			'userNameHF' => strToHex('张三'),//持卡人姓名，需转化为16进制
			'quickPayCertNo' => '身份证号码',//持卡人身份证号码
			'userIp'=>'',
			'bankId' => '888880170122900',
			'stlmId'=>'',
			'entryType'=>'1',
			'attach'=>'',
			'reserver1'=>'',
			'reserver2'=>'',
			'reserver3'=>'',
			'reserver4'=>'7',
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
    'pay_api'     => '聚创支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

// 提交请求

if($type['action'] == 'form'){
	echo submitFormMethod($data, $pay_request_url);exit;
}

$result = json_decode(ccb_curl_calls($pay_request_url, $data), true);
//echo '<pre/>';var_dump($pay_request_url);echo '<hr>';
//echo '<pre/>';var_dump( $data);echo '<hr>';
//echo '<pre/>';var_dump($result);echo '<hr>';die;

if($result['payState'] !== 'success'){
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
<style>
    div{
    }
</style>
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
