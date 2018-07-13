<?php
// 随便付

require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once  'common.php' ;
$mchid = $merchant_id;
$orderNo=date("YmdHis").rand(100000,999999);
$total_fee=sprintf("%.2f",$_REQUEST['coin']);
//下单信息
//$order_info = array();
//$order_info['pay_memberid'] = $merchant_id;
//$order_info['pay_orderid'] = $orderNo;  //平台商户号
//$order_info['pay_amount'] = $orderNo;//订单号
//$order_info['total_fee'] = $total_fee; //单位分
//$order_info['pay_applydate'] = date('Y-m-d H:i:s');
//$order_info['pay_bankcode'] = getPayType();
//$order_info['pay_notifyurl'] = $pay_callbackurl;//交易类型，
//$order_info['pay_callbackurl'] = $hrefbackurl;
//ksort($order_info);
//$md5str = "";
//foreach ($order_info as $key => $val) {
//    if(!empty($val)){
//        $md5str = $md5str . $key . "=" . $val . "&";
//    }
//}
//$sign = strtoupper(md5($md5str . "key=" . $appKey));
//$order_info["pay_md5sign"] = $sign;
//print_r($order_info);
// $return=submitPostData($url,$order_info);
//var_dump($return);exit;
//
//
//
//$str = '<form id="Form1" name="Form1" method="post" action="' . $url . '">';
//foreach ($order_info as $key => $val) {
//    $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
//}
//$str = $str . '<input type="submit" value="提交">';
//$str = $str . '</form>';
//$str = $str . '<script>';
//var_dump($str);exit;
////        $str = $str . 'document.Form1.submit();';
//$str = $str . '</script>';
//exit($str);die;
//$payCodeUrl='';
$pay_memberid = $merchant_id;   //商户ID
$pay_orderid =$orderNo;    //订单号
$pay_amount = $total_fee;    //交易金额
$pay_applydate = date("Y-m-d H:i:s");  //订单时间
$pay_bankcode =$payType;   //银行编码
$pay_notifyurl =$pay_callbackurl ;   //服务端返回地址
$pay_callbackurl = $hrefbackurl  ;  //页面跳转返回地址
$Md5key = "dz0tw66jojseou60ew2knvqancnnkodt";   //商户密钥
$tjurl = "http://m.jialianjinfu.com/Pay_index";   //提交地址
$requestarray = array(
    "pay_memberid" => $pay_memberid,
    "pay_orderid" => $pay_orderid,
    "pay_amount" => $pay_amount,
    "pay_applydate" => $pay_applydate,
    "pay_bankcode" => $pay_bankcode,
    "pay_notifyurl" => $pay_notifyurl,
    "pay_callbackurl" => $pay_callbackurl
);
ksort($requestarray);
$md5str = "";
foreach ($requestarray as $key => $val) {
    if(!empty($val)){
        $md5str = $md5str . $key . "=" . $val . "&";
    }
}
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$requestarray["pay_md5sign"] = $sign;


//数据入库


$insertArr = [
    'order_id'    => $pay_orderid ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => '',
    'sign'        => $sign,
    'order_money' =>$_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '嘉联付',
    'pay_url'     => $_SERVER['REMOTE_ADDR'] , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}
if($isApp=='1'){
$str = '<form id="Form1" name="Form1" method="post" action="' . $tjurl . '">';
foreach ($requestarray as $key => $val) {
    $str = $str . '<input type="hidden" name="' . $key . '" value="' . $val . '">';
}
$str = $str . '<input type="submit" value="提交">';
$str = $str . '</form>';
$str = $str . '<script>';
$str = $str . 'document.Form1.submit();';
$str = $str . '</script>';
echo($str);die;
}
$postDate=submitPostData($tjurl,$requestarray);
$return=json_decode($postDate,true);

if($return['status']=='0')
{
    $payCodeUrl=$return['codeUrl'];
}
?>




<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?= $words ?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script src="js/jquery.qrcode.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text"><?= $words ?>支付</span>
    </h1>
    <div class="mod-ct">
        <div class="order">
        </div>
        <div class="amount">
            <span>￥</span><?= $total_fee ?>        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?= $pay_orderid ?></dd>
            </dl>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan">
            </div>
            <div class="tip-text">
                <p>
                    请使用<?= $words ?>扫一扫
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
            success: function (data) {
                if (data.result == '1') {
                    alert('支付成功');
                    window.location.href = '/';
                }
            }
        });
    }

    $(function () {
        $('#barCode').empty();
        $('#barCode').qrcode({
            render: "canvas", //table方式
            width: 300, //宽度
            height: 300, //高度
            text: '<?= $payCodeUrl ?>', //任意内容
            background: "#ffffff",
        });
        setInterval("query('" + "<?= $orderNo ?>" + "')", 5000);
    })

</script>
</html>
