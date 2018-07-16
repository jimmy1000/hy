<?php
// +----------------------------------------------------------------------
// | FileName:通汇--福卡通
// +----------------------------------------------------------------------

require_once  '../../pay_mgr/init.php';
include       './common.php' ;
include       "./helper.php";
include      'AES.php' ;

//支付金额需要使用第三方的加密处理
$money = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$aes   = new CryptAES();
$aes->set_key($key);
$aes->require_pkcs5();
$orderAmount  = $aes->encrypt($money);
$bankCode     = getBankCode() ;
$orderNo      = date("YmdHis").rand(100000,999999) ; //订单号;
$referer      = 'http://'.$_SERVER['HTTP_HOST'];
$customerIp   = get_client_ip();
$returnParams = $_REQUEST['username'].'-'.$_REQUEST['pay_type'];
$currentDate  = (new DateTime())->format('Y-m-d H:i:s');
$pay_type     = 1 ;
$char_set     = 'UTF-8' ;

//签名
$kvs = new KeyValues();
$kvs->add(AppConstants::$INPUT_CHARSET, 'UTF-8');
$kvs->add('inform_url', $pay_callbackurl); //异步回调
$kvs->add(AppConstants::$RETURN_URL, $pay_returnurl); //同步回调
$kvs->add(AppConstants::$PAY_TYPE, $pay_type);
$kvs->add(AppConstants::$BANK_CODE, $bankCode);
$kvs->add(AppConstants::$MERCHANT_CODE, $mer_no);
$kvs->add(AppConstants::$ORDER_NO, $orderNo);
$kvs->add(AppConstants::$ORDER_AMOUNT, $orderAmount);
$kvs->add(AppConstants::$ORDER_TIME, $currentDate);
$kvs->add(AppConstants::$REQ_REFERER, $referer);
$kvs->add(AppConstants::$CUSTOMER_IP, $customerIp);
$kvs->add(AppConstants::$RETURN_PARAMS, $returnParams);
$sign = $kvs->sign();

//组合参数
$gatewayUrl = $url;
URLUtils::appendParam($gatewayUrl, AppConstants::$INPUT_CHARSET, 'UTF-8', false);
URLUtils::appendParam($gatewayUrl, AppConstants::$NOTIFY_URL, $pay_callbackurl, true, $char_set);
URLUtils::appendParam($gatewayUrl, AppConstants::$RETURN_URL, $pay_returnurl, true, $char_set);
URLUtils::appendParam($gatewayUrl, AppConstants::$PAY_TYPE, $pay_type);
URLUtils::appendParam($gatewayUrl, AppConstants::$BANK_CODE, $bankCode);
URLUtils::appendParam($gatewayUrl, AppConstants::$MERCHANT_CODE, $mer_no);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_NO, $orderNo);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_AMOUNT, $orderAmount);
URLUtils::appendParam($gatewayUrl, AppConstants::$ORDER_TIME, $currentDate);
URLUtils::appendParam($gatewayUrl, AppConstants::$REQ_REFERER, $referer, true, $char_set);
URLUtils::appendParam($gatewayUrl, AppConstants::$CUSTOMER_IP, $customerIp);
URLUtils::appendParam($gatewayUrl, AppConstants::$RETURN_PARAMS, $returnParams, true, $char_set);
URLUtils::appendParam($gatewayUrl, AppConstants::$SIGN, $sign);


$pay_url = getLocalRequestUrl() ;
$insertArr = array(
    'order_id'=>$orderNo,
    'user_name'=>$_REQUEST['username'],
    'pay_type'=>$_REQUEST['type'],
    'pay_ip'=>get_client_ip(),
    'sign'=>$sign,
    'order_money'=>$money,
    'order_time'=>time(),
    'pay_api'=>'通汇-福卡通',
    'pay_url'=>$pay_url
);
if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>网关支付</title>
</head>
<body>
<form action="<?=$url?>" method="post">
    <input type="hidden" name="<?=AppConstants::$INPUT_CHARSET?>" value="<?=$char_set?>"/>
    <input type="hidden" name="inform_url" value="<?=$pay_callbackurl?>"/>
    <input type="hidden" name="<?=AppConstants::$RETURN_URL?>" value="<?=$pay_returnurl?>"/>
    <input type="hidden" name="<?=AppConstants::$PAY_TYPE?>" value="<?=$pay_type?>"/>
    <input type="hidden" name="<?=AppConstants::$BANK_CODE?>" value="<?=$bankCode?>"/>
    <input type="hidden" name="<?=AppConstants::$MERCHANT_CODE?>" value="<?=$mer_no?>"/>
    <input type="hidden" name="<?=AppConstants::$ORDER_NO?>" value="<?=$orderNo?>"/>
    <input type="hidden" name="<?=AppConstants::$ORDER_AMOUNT?>" value="<?=$orderAmount?>"/>
    <input type="hidden" name="<?=AppConstants::$ORDER_TIME?>" value="<?=$currentDate?>"/>

    <input type="hidden" name="<?=AppConstants::$REQ_REFERER?>" value="<?=$referer?>"/>
    <input type="hidden" name="<?=AppConstants::$CUSTOMER_IP?>" value="<?=$customerIp?>"/>

    <input type="hidden" name="<?=AppConstants::$RETURN_PARAMS?>" value="<?=$returnParams?>"/>
    <input type="hidden" name="<?=AppConstants::$SIGN?>" value="<?=$sign?>"/>
</form>
<script type="text/javascript">
    document.forms[0].submit();
</script>
</body>
</html>