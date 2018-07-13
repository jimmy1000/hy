<?php

require_once '../../pay_mgr/init.php';
include './config.php';
header("Content-type: text/html; charset=utf-8");

$username = $_REQUEST['username'];
$coin = $_REQUEST['coin']*100;
$type = $_REQUEST['type'];
$post_type = 0;
switch($type){
    case 'WECHAT':
        $post_type = 2701;
        $words = '微信';    
        break;
    case 'ALIPAY':
        $post_type = 2702;
        $words = '支付宝';
        break;
    case 'BANK':
        $post_type = 2704;
         $words = '网银支付';
        break;
    case 'BANKWAP':
        $post_type = 2703;
        $words = '手机网银';
        break;
    case 'QQPAY':
        $post_type = 2705;
        $words = 'QQ钱包';
        break;
    default :
        break;  
} 
    if(empty($post_type)){
         die('<br/>无效的请求<br/>');
    }
        $seller_id = $mer_no;
	$order_type = $post_type;
	$out_trade_no =  date("YmdHis").rand(100000,999999);   //订单号
	$pay_body = 'quick-web';
	$total_fee = $coin ;
	$spbill_create_ip = get_client_ip();
	$spbill_times = time();
	$noncestr = 'idse'.time();
	$remark = $username.'-'.$type;
	$parameter = array(
		'seller_id'         => $seller_id,
		'order_type'        => $order_type,
		'out_trade_no'      => $out_trade_no,
		'pay_body'          => $pay_body,
		'total_fee'         => $total_fee,
		'notify_url'        => $callback,
		'return_url'        => $herfback,
		'spbill_create_ip'  => $spbill_create_ip,
		'spbill_times'      => $spbill_times,
		'noncestr'          => $noncestr,
		'remark'            => $remark
	);
	$merchant_private_key= openssl_get_privatekey($merchant_private_key);
	openssl_sign(getSignStr($parameter),$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
	$sign = base64_encode($sign_info);
        if(!$_SERVER['HTTP_REFERER']){
            $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        }else{
            $url = $_SERVER['HTTP_REFERER'];
        }
         $insertArr = array('order_id'=>$out_trade_no,
                            'user_name'=>$username,
                            'pay_type'=>$type,
                            'pay_ip'=>get_client_ip(),
                            'sign'=>$sign,
                            'order_money'=>$total_fee/100,
                            'order_time'=>time(),
                            'pay_api'=>'富盈宝支付',
                            'pay_url'=>$url
                        );

        if(!$database->insert(DB_PREFIX.'preorder'  ,$insertArr )){
             echo("<script>alert('系统维护:请尝试其他支付方式!');window.location='/';</script>");exit();
        }
	$parameter['sign'] = $sign;
	$resp = http_poststr("http://api.xueyuplus.com/wbsp/unifiedorder", json_encode($parameter));
        $resp = json_decode($resp,true);
        $web_public_key = openssl_get_publickey($server_public_key);
        $flag = openssl_verify(getSignStr($resp),base64_decode($resp['sign']),$web_public_key,OPENSSL_ALGO_MD5);
	if (!$flag) {
            echo("<script>alert('系统维护:订单提交失败,重新发起充值!');window.location='/';</script>");
            exit();
        }
        if ($type == 'BANK' || $type == 'BANKWAP' ) {
            header('location:' . $resp['pay_url']);
            die;
        }
?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="zh-cn">
<title><?=$words?>扫码支付</title>
<link href="./wechat_pay.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
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
			<span>￥</span><?=number_format($_REQUEST['coin'],2)?>
		</div>
		<div class="qr-image" style="">
		  <div id="barCode" style="margin-left: 0px;">
		     <?php echo '<img src="http://api.xueyuplus.com/wbsp/image_api?text='.$resp['pay_url'].'">'; ?>
		  </div>
		</div>
		<!--detail-open 加上这个类是展示订单信息，不加不展示-->
		<div class="detail detail-open" id="orderDetail" style="">
			<dl class="detail-ct" style="display: block;">				
				<dt>交易单号</dt>
				<dd id="billId"><?=$out_trade_no?></dd>
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

function query(orderid){
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
//	  $('#barCode').empty();                   
//      $('#barCode').qrcode({
//			render : "canvas", //table方式 
//			width : 300, //宽度 
//			height : 300, //高度 
//			text : '<?=$qrcode?>', //任意内容 background: "#ffffff",
//			background : "#ffffff",
//		});
        //$('#barCode').
	  setInterval("query('<?=$out_trade_no?>')",5000); 
  })    
</script>
</html>

