<?php
// 随便付
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once 'function.php';
require_once  'common.php' ;



$mchid = $merchant_id;
$api_url = 'http://api.suibpay.com/trade/pay';
$orderNo=date("YmdHis").rand(100000,999999);
$total_fee=$_REQUEST['coin']*100;

switch ($_REQUEST['coin']){
    case 20:
    case 50:
    case 100:
    case 200:
    case 500:
    case 1000 :
    case 1500:
    case 2000:
    case 2500:
    case 3000:
        $total_fee=$_REQUEST['coin']*100;
        break;
    default:
        echo  "支付金额必需为 20 50  100  200 500  1000 1500 2000 2500  3000元";die;
}


//下单信息
$order_info = array();
$order_info['src_code'] = $SRC_CODE; //商户唯1标识
$order_info['mchid'] = $mchid;  //平台商户号
$order_info['out_trade_no'] = $orderNo;//订单号
$order_info['total_fee'] = $total_fee; //单位分
$order_info['time_start'] = date('YmdHis');
$order_info['goods_name'] = 'pay';
$order_info['trade_type'] = $payType;//交易类型，
$order_info['finish_url'] = $hrefbackurl;

//把md5校验值加入参数数组
$order_info['sign'] = get_md5($order_info, $SRC_KEY);

//调用接口
$res = http($api_url, $order_info);

if($res['http_code'] == 200){
    $return_info = json_decode($res['http_data'],true);
    print_r($return_info);exit;
    $payCodeUrl=$return_info['data']['pay_params'];
}
else{
    echo '接口错误，记录等操作';
}



//数据入库
$insertArr = [
    'order_id'    => $orderNo ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $order_info['sign'],
    'order_money' =>$_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '随便付',
    'pay_url'     => $_SERVER['REMOTE_ADDR'] , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}
if($isWAP){
    echo  $payCodeUrl;die;
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
            <span>￥</span><?= $_REQUEST['coin'] ?>        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?= $orderNo ?></dd>
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
