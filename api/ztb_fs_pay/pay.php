<?php
$payType = $_REQUEST['type'] ;
$words   = '' ;  //支付类型
$coin    = $_REQUEST['coin'] ;  //支付金额
if ( $payType=='WEIXINCODE' ) {
    $words = '微信付款码';
} elseif( $payType=='ALIPAYCODE' ) {
    $words = '支付宝付款码';
}


?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="zh-cn">
    <title><?=$words?>扫码支付</title>
    <link href="./wechat_pay.css" rel="stylesheet" media="screen">
    <script src="js/jquery-1.8.2.min.js" type="text/javascript"> </script>
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
            <span>￥</span><?=$coin?>
        </div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 155px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>请填写付款码</dt>
                <dd>
                    <form action="paysubmit.php" action="POST">
                        <input  type='hidden' name='username' value="<?=$_REQUEST['username']?>" />
                        <input  type='hidden' name='coin'      value="<?=$_REQUEST['coin']?>" />
                        <input  type='hidden' name='type'      value="<?=$_REQUEST['type']?>" />
                        <input  type='type'   name='payCode'   value=''  style="width:210px;height:28px;font-size:14px"/>

                </dd>
            </dl>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan">
            </div>
            <div class="tip-text">
                <p>
                    <input type="submit"  value="提交数据" style="width:100px;height:25px;"/>
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
</form>
</body>
</html>