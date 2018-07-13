<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link href="./css/wechat_pay.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="./js/jquery-1.8.2.min.js"></script>
    <script src="./js/jquery.qrcode.min.js"></script>
    <title>扫码支付</title>
</head>
<body>
<?php
require_once '../../pay_mgr/init.php';
include 'config.php';;
$total_fee = $_REQUEST['coin'];//订单金额
$PaymentType = isset($_REQUEST["type"])? trim($_REQUEST["type"]):"";//支付编码，当为空时代表银行网关间连
$orderid = date("YmdHis").rand(100,100000);//订单号
$isApp  = false;

if(!key_exists($PaymentType,$convert)){
    exit("pay type error!");
}

if($_REQUEST['banktype']=="BANK"){
			$PaymentType="ICBC";
}else{
$PaymentType = $convert[$PaymentType];
}
$data =array();
$data["X1_Amount"] = $total_fee; //订单金额
$data["X2_BillNo"] = $orderid;//订单号
$data["X3_MerNo"] = $partner;//商户号
$data["X4_ReturnURL"] = $callbackurl;

ksort($data);
$prestr = "X1_Amount=$total_fee&X2_BillNo={$orderid}&X3_MerNo=$partner&X4_ReturnURL=$callbackurl";     	//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串

$data["X6_MD5info"] = strtoupper(md5($prestr."&". strtoupper(md5($key))));

$data["X5_NotifyURL"] = $hrefbackurl;
$data["X7_PaymentType"] = $PaymentType;
$data["X8_MerRemark"] = "虚拟商品";
$data["isApp"] = $isApp; //固定值： 值为"app",表示app接入； 值为空，表示web接入

//echo "发送地址：",$request_url,"\n";
$postData = $data;
$postDataString = http_build_query($postData);//格式化参数

  
 if($data["X7_PaymentType"]=="ALIPAYH5"|$data["X7_PaymentType"]=="WXH5"|$data["X7_PaymentType"]=="BSM"|$data["X7_PaymentType"]=="KJZF"|$_REQUEST['banktype']=="BANK"){
     $html= form($data,$apiurl);
   $insertArr = array(
    'order_id'    =>     $orderid,              //订单id
    'user_name'   => 	$_REQUEST['username'],		//会员名
    'pay_type'    =>	$_REQUEST['type'],	//支付类型
    'pay_ip'      =>	get_client_ip(),			//订单ip
    'sign'        => 	$data["X6_MD5info"],			//签名
    'order_money' => 	$total_fee,		//订单金额
    'order_time'  => 	time(),			//订单时间
    'pay_api'     =>		'佰钱支付',		//支付名称
    'pay_url'     =>	"http://payhy.8889s.com"			//支付的url
);

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
  echo "创建订单失败";
  exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

	 echo $html;
   exit;
      
 }
  
  
  
  
  
  
  
  

//die();
$curl = curl_init(); // 启动一个CURL会话
curl_setopt($curl, CURLOPT_URL, $apiurl); // 要访问的地址
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
curl_setopt($curl, CURLOPT_POSTFIELDS, $postDataString); // Post提交的数据包
curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环返回
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

$tmpInfo = curl_exec($curl); // 执行操作
if (curl_errno($curl)) {
    $tmpInfo = curl_error($curl);//捕抓异常
}
curl_close($curl); // 关闭CURL会话
$info = json_decode($tmpInfo, true);

if(!isset($info['status']) || $info['status']!='88'){
    exit("<script>alert('该通道暂时不可用');window.close();</script>");
}
$qrcode = $info['imgUrl'];
///数据入库
$insertArr = array(
    'order_id'    =>     $orderid,              //订单id
    'user_name'   => 	$_REQUEST['username'],		//会员名
    'pay_type'    =>	$_REQUEST['type'],	//支付类型
    'pay_ip'      =>	get_client_ip(),			//订单ip
    'sign'        => 	$data["X6_MD5info"],			//签名
    'order_money' => 	$total_fee,		//订单金额
    'order_time'  => 	time(),			//订单时间
    'pay_api'     =>		'佰钱支付',		//支付名称
    'pay_url'     =>	"http://pay77158.com"			//支付的url
);

if (!$database->insert(DB_PREFIX . 'preorder', $insertArr)) {
  echo "创建订单失败";
  exit("<script>alert('创建订单失败!');history.go(-1);</script>") ;
}

?>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text">扫码支付</span>
    </h1>
    <div class="mod-ct">
        <!--<div class="order">
        </div>-->
        <div class="amount">
            <span>￥</span><?=number_format($total_fee,2)?>	</div>
        <div class="qr-image" style="">
            <div id="barCode" style="margin-left: 0px;min-height:300px;">

            </div>
        </div>
        <!--detail-open 加上这个类是展示订单信息，不加不展示-->
        <div class="detail detail-open" id="orderDetail" style="">
            <dl class="detail-ct" style="display: block;">
                <dt>交易单号</dt>
                <dd id="billId"><?=$orderid?></dd>
            </dl>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan">
            </div>
            <div class="tip-text">
                <p>
                    请使用支付宝扫一扫
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
        $('#barCode').empty();
        $('#barCode').qrcode({
            render : "canvas", //table方式
            width : 300, //宽度
            height : 300, //高度
            text : '<?=$qrcode?>', //任意内容 background: "#ffffff",
            background : "#ffffff",
        });
        setInterval("query('<?=$orderid?>')",5000);
    })
</script>
</body>
</html>
