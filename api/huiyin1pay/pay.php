<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//  汇银支付
//支付方式
$type = getServiceType($_REQUEST['type']);
//支付金额
$coin = sprintf('%.2f',$_REQUEST['coin']);

if($_REQUEST['type'] == 'WECHAT' && 0){
    $coinArr = [99.5, 199];
    if(!in_array($coin, $coinArr)){
        $dviStr = '';
        foreach($coinArr as $value){
            $dviStr .= '<div class="span2 thumbnail" data-coin="' . $value . '">' . $value .'</div>';
        }
        echo <<<TOO
        <link rel="stylesheet" type="text/css" href="bootstrap.css">
        <script src="jquery-1.7.2.min.js" type="text/javascript"></script>
      
        <h3 style="text-align:center;color:#bd362f" class="text-center">目前只支持以下金额, 或返回选择其他支付方式!</h3>
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
}

//订单号
$order_id = date('YmdHis').rand(100000,999999);

$data = [
    //支付请求参数
        'merchantcode' => $app_id,				 //商户号
        'type' => $type['code'],				 //通道类型
//        'type' => 'KUAIJIE',				 //通道类型
        'amount' => $coin,				 //金额
        'orderid' => $order_id,				 //商户订单号
        'notifyurl' => $notify_url,				 //下行异步通知地址
        'callbackurl' => $return_url,				 //下行同步通知地址
        'clientip' => get_client_ip(),				 //支付用户IP
        'desc' => 1,				 //备注消息
	//'orderTime' => date('YmdHis'),              //下单时间，格式yyyyMMddHHmmss
];

$sign = strtoupper(md5("amount={$coin}&merchantcode={$app_id}&notifyurl={$notify_url}&orderid={$order_id}&type={$type['code']}&key={$app_key}"));

//$sign_key = 'key';
//$sign = create_sign($data, array($sign_key => $app_key));
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
    'pay_api'     => '汇银支付',
    'pay_url'     => getLocalRequestUrl() , //获取客户支付地址
];

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
	exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}


// 提交请求
//echo '<pre>';var_dump($data);echo '<hr>';die;
$result = json_decode(postSend($pay_request_url, $data, true), true);

if($result['data']['htmlstr'] != ''){
	echo urldecode($result['data']['htmlstr']);exit;
}

//返回的  支付url
$payUrl = $result['data']['url'];
if($result['code'] !== '0000'){
//	file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL,FILE_APPEND);
//	file_put_contents(dirname(__FILE__).'/err.log', var_export($result, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

	returnError();
}

if($type['action'] == 'jump'){
	header('location:' . $payUrl);exit;
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
