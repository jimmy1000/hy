<?php

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;

//艾米森
$words ='' ;
$data['src_code']     = $src_code;  //商户唯一标识
$data['out_trade_no'] = date("YmdHis").rand(100000,999999) ; //订单号
$data['total_fee']    = $_REQUEST['coin'] * 100 ; //单位分
$data['time_start']   = date('YmdHms',time()) ; //发起交易的时间
$data['goods_name']   = 'online_pay'; //在线支付
$data['trade_type']   = getPayType($words) ;  //交易类型
$data['finish_url']   = $pay_callbackurl; //回调地址
$data['mchid']        = $merchant_id; //商户号
$data['sign']         = create_sign($data,$key) ;


$pay_url = getLocalRequestUrl() ; //获取客户支付地址
//数据入库
$insertArr = [
    'order_id'    => $data['out_trade_no'] ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $data['sig'],
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '艾米森',
    'pay_url'     => $pay_url
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

$result = submitPostData($url,$data) ;
$data   = json_decode($result,true) ;

if ($data['respcd'] != '0000') {
    exit("<script>alert('网络延迟,请重试...');history.go(-1);</script>") ;
}

$code_url = $data['data']['pay_params'] ;
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?=$words?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"> </script>
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
            <span>￥</span><?=$_REQUEST['coin']?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$data['out_trade_no'] ?></dd>
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
    function query(orderid) {
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
        $('#barCode').empty();
        $('#barCode').qrcode({
            render : "canvas", //table方式
            width : 300, //宽度
            height : 300, //高度
            text : '<?=$code_url?>', //任意内容 background: "#ffffff",
            background : "#ffffff",
        });
        setInterval("query('<?=$data['out_trade_no']?>')",5000);
    })

</script>
</html>
