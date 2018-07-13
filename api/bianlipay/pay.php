<?php

include 'common.php';
require_once '../../pay_mgr/init.php';

	//订单号
	$pay_orderid   = date("YmdHis").rand(100000,999999);
	//提交时间
	$pay_applydate = date('Y-m-d H:i:s',time());
	$pay_applydate = date('YmdHis',time());

	//银行编码
	$pay_bankcode  = 'QQ_NATIVE';
	//订单金额
	$pay_amount    = number_format($_REQUEST['coin'],2);

	//通道名称
	$pay_tongdao   = 'QQpay';

    $words          = 'QQ扫码支付' ; //用于展示二维码扫码支付

	//扩展字段
	$pay_reserved1 = $_REQUEST['username'].'-'.$pay_bankcode.'-'.$pay_tongdao;
	//商品名称
	$pay_productname = 'online-pay';
	//商品数量
	$pay_productnum = 1;
	//商品描述
	$pay_productdesc = 'online-pay';
	//商品链接地址
	$pay_producturl = '';

	$jsapi = array(
	    "pay_memberid"    => $pay_memberid,
	    "pay_orderid"     => $pay_orderid,
	    "pay_amount"      => $pay_amount,
	    "pay_applydate"   => $pay_applydate,
	    "pay_bankcode"    => $pay_bankcode,
	    "pay_notifyurl"   => $pay_notifyurl,
	    "pay_callbackurl" => $pay_callbackurl
	);

	ksort($jsapi) ;
	$md5str = ""  ;
	foreach ($jsapi as $key => $val) {
	    $md5str = $md5str . $key . "=" . $val . "&";
	}

//补充参数,方便下一步向第三方提交支付数据
	$sign                     = strtoupper(md5($md5str . "key=" . $pay_md5sign));
	$jsapi["sign"]            = $sign;
	$jsapi["pay_tongdao"]     = $pay_tongdao; //通道
	$jsapi["pay_productname"] = $pay_productname; //商品名称
    $jsapi['pay_reserved1']   = $pay_reserved1   ; //扩展字段
    $jsapi['pay_productnum']  = $pay_productnum  ; //商品数量
    $jsapi['pay_productdesc'] = $pay_productdesc ; //商品描述
//    $jsapi['pay_producturl']  = $pay_producturl  ; //商品链接地址

//记录支付处理地址
if (!$_SERVER['HTTP_REFERER']) {
    $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
} else {
    $pay_url = $_SERVER['HTTP_REFERER'];
}

//数据入库
$insertArr = [
    'order_id'    => $pay_orderid,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $sign,
    'order_money' => $pay_amount,
    'order_time'  => time(),
    'pay_api'     => '便利付',
    'pay_url'     => $pay_url
];

if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}


$result = submitPostData($pay_requestUrl,$jsapi) ;
$result = json_decode($result,true) ;

if ($result['successno'] == 100001) {
    $data   = $result['data'] ;
    $qrUrl  = $data['pay_QR'] ;
    $url    = $data['pay_url'] ;
} else {
    exit("<script>alert('".$data['msg']."');history.go(-1);</script>");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?=$words?>支付</title>
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
            <span>￥</span><?=$pay_amount?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left:0px;">
                <?php
                if($qrUrl){
                    echo "<img src='$qrUrl' />";
                }else{
                    echo  "无法获取支付信息!";
                }
                ?>
            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$data['pay_orderid'] ?></dd>
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

</html>