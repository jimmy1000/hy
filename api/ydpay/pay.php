<?php
// 易到
require_once '../../pay_mgr/init.php';
require_once  'config.php' ;
require_once  'common.php' ;
require_once("yidao.php");

$orderNo  = date("YmdHis").rand(100000,999999) ;
$payType  = get_paytype() ;
$ydpay     = new yidao() ;
$postData  = $ydpay->pay($orderNo,"Online_pay",$_REQUEST['coin'],$payType) ;

//数据入库
$insertArr = [
    'order_id'    => $orderNo ,
    'user_name'   => $_REQUEST['username'],
    'pay_type'    => $_REQUEST['type'],
    'pay_ip'      => get_client_ip(),
    'sign'        => $order_info['sign'],
    'order_money' => $_REQUEST['coin'],
    'order_time'  => time() ,
    'pay_api'     => '易到',
    'pay_url'     => $_SERVER['REMOTE_ADDR'] , //获取客户支付地址
] ;
if (!$database->insert(DB_PREFIX.'preorder',$insertArr)) {
    exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

$res      = submitPostData($pay_url,$postData) ;
$response = json_decode($res,true) ;

if (!$response['responseObj']['qrCode']){
    exit("<script>alert('支付错误!');history.go(-1);</script>") ;
}

$txnTime    = $response['responseObj']['txnTime'];//存储交易时间，查询订单接口必要参数。
$orgOrderNo = $response['responseObj']['orgOrderNo'];//订单号，查询订单接口必要参数。
$qrCode     = $response['responseObj']['qrCode'];
?>
<img src="http://qr.liantu.com/api.php?text=<?php echo $qrCode?>"/>
<script src="http://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    var getting = {
        url:'queryOrder.php',
        data:{"txnTime":"<?php echo $txnTime ?>","order_id":"<?php echo $orgOrderNo ?>"},
        dataType:'json',
        success:function(res) {
            if (res==1){
                alert("支付成功");
                window.location.href='/user.php?act=order_list';
            }
        }
    };
    //关键在这里，Ajax定时访问服务端，不断获取数据 ，这里是1.5秒请求一次。
    window.setInterval(function(){$.ajax(getting)},200);
</script>
