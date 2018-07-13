<?php
require("config.php");

$form = "<form>\r\n";
foreach($_GET as $k=>$v){
  $form .= "<input type='hidden' name='$k' value='$v' />\r\n";
}
$form .= "</form>\r\n";
file_put_contents(dirname(__FILE__).'/form.html',$form,FILE_APPEND);

$merchant_code	= $_REQUEST["merchant_code"];	//Z800020009002
$notify_type = $_REQUEST["notify_type"];
$notify_id = $_REQUEST["notify_id"];
$interface_version = $_REQUEST["interface_version"];
$sign_type = $_REQUEST["sign_type"];
$dinpaySign = base64_decode($_REQUEST["sign"]);
$order_no = $_REQUEST["order_no"];
$order_time = $_REQUEST["order_time"];
$order_amount = $_REQUEST["order_amount"];
$extra_return_param = $_REQUEST["extra_return_param"];
$trade_no = $_REQUEST["trade_no"];
$trade_time = $_REQUEST["trade_time"];
$trade_status = $_REQUEST["trade_status"];
$bank_seq_no = $_REQUEST["bank_seq_no"];
$signStr = "";
if($bank_seq_no != ""){
    $signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
}
if($extra_return_param != ""){
    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
}
$signStr = $signStr."interface_version=".$interface_version."&";
$signStr = $signStr."merchant_code=".$merchant_code."&";
$signStr = $signStr."notify_id=".$notify_id."&";
$signStr = $signStr."notify_type=".$notify_type."&";
$signStr = $signStr."order_amount=".$order_amount."&";
$signStr = $signStr."order_no=".$order_no."&";
$signStr = $signStr."order_time=".$order_time."&";
$signStr = $signStr."trade_no=".$trade_no."&";
$signStr = $signStr."trade_status=".$trade_status."&";
$signStr = $signStr."trade_time=".$trade_time;
/////////////////////////////   RSA-S验签  /////////////////////////////////
$dinpay_public_key = openssl_get_publickey($dinpay_public_key);
$flag = openssl_verify($signStr,$dinpaySign,$dinpay_public_key,OPENSSL_ALGO_MD5);

file_put_contents(dirname(__FILE__).'/log.txt',$flag,FILE_APPEND);

if($flag){
   	require_once '../../pay_mgr/init.php';
    $order_state = 1;
    $orderid = $order_no;
    $attach = $extra_return_param;
    $tmp = explode("-", $attach);
    $user_name = $tmp[0];
    $ovalue = $order_amount;
  	$convert = array('alipay_scan'=>'ALIPAY','weixin_scan'=>'WECHAT','tenpay_scan'=>'QQPAY');
    $attach = $tmp[1];
    $attach = $convert[$attach];
  	$info = $database->get(DB_PREFIX.'order','*',array('order_id'=>$orderid));
    if (! $info) {
      
        $arrInsert = array(
            'order_id' => $orderid,
            'user_name' => $user_name,
            'order_money' => "$ovalue",
            'order_time' => time(),
            'order_state' => $order_state,
            'state' => 0,
            'pay_type' => $attach,
            'pay_api' => '速汇宝支付',
            'pay_order' => $orderid
        );
        $database->insert ( DB_PREFIX . 'order', $arrInsert );
       	echo "SUCCESS";
    }
}else{
    echo "Signature error";
}