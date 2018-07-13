<?php
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once 'common.php';
$mchid = $config['merNo'];
$api_url = 'http://api.suibpay.com/';
$orderNo=date("YmdHis").rand(100000,999999);
$total_fee=$_REQUEST['coin']*100;
//下单信息
$Pay = array();
//var_dump($config);exit;
$Pay['merNo'] = $mchid; #商户号
$Pay['aptNo'] = $mchid; #资质编号
$Pay['payNetway'] =$payType;  #WX 或者 ZFB
$Pay['random'] = (string) rand(1000,9999);  #4位随机数    必须是文本型
$Pay['orderNum'] =$orderNo;  #商户订单号
$Pay['amount'] = $total_fee;  #默认分为单位 转换成元需要 * 100   必须是文本型
$Pay['stuffName'] = '在线支付';  #商品名称
$Pay['callBackUrl'] = $pay_callbackurl;  #通知地址 可以写成固定
$Pay['callBackViewUrl'] = $hrefbackurl ;  #前台跳转 可以写成固定
$Pay['ip'] = GetRemoteIP();  #客户请求I'P
ksort($Pay); #排列数组 将数组已a-z排序
$sign = md5(Util::json_encode($Pay) . $config['signKey']); #生成签名

$Pay['sign'] = strtouPPer($sign); #设置签名
$data = Util::json_encode($Pay); #将数组转换为JSON格式
write_log('通知地址：' . $Pay['callBackUrl']);
write_log('提交支付订单：' . $Pay['orderNum']);

$Post = array('data'=>$data);
echo '<pre>';var_dump($Post);echo '<hr>';
$return = wx_Post($config['PayUrl'],$Post); #提交订单数据
var_dump($return.'1123');
$row = Util::json_decode($return); #将返回json数据转换为数组

if ($row['status'] !== '00'){
    write_log('系统错误,错误号：' . $row['status'] . '错误描述：' . $row['msg']);
    echo '系统维护中.';
    exit();
}else{
    if (is_sign($row,$config['signKey'])){ #验证返回签名数据
        $qrcodeUrl = $row['payUrl'];
        $orderNum = $row['orderNum'];
        $msg = $row['msg'];
        /* echo $qrcodeUrl;
        exit; */
        write_log('创建订单成功!订单号：' . $orderNum . '系统消息：' . $msg);
        $gourlss="./qrcode.PhP";
        //  header("location:".qrcode.PhP?code=.$qrcodeUrl.&netway=.$Pay['PayNetway']);
        header("location:".$gourlss."?code=".$qrcodeUrl);
    }

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
            <span>￥</span><?= $coin ?>        </div>
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
