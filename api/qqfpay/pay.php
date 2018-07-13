<?php
// +----------------------------------------------------------------------
// | FileName: pay.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年11月25日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
include './config.php';
$data['mer_no'] = $mer_no;
$data['mer_order_no'] = strtoupper(uniqid('qqf'));
$data['trade_amount'] = $_REQUEST['coin'];
$data['service_type'] = 'quick-web';
$data['order_date'] = date('Y-m-d H:i:s');
$data['page_url'] = $hrefbackurl;
$data['back_url'] = $callbackurl;

ksort($data);
reset($data);
$signStr = '';
foreach($data as $k => $v){
    $signStr .= "$k=$v&";
}
$signStr .= "key=$key";
$data['sign_type'] = 'MD5';
$data['sign'] = strtoupper(md5($signStr));
if(!$_SERVER['HTTP_REFERER']){
  $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
}else{
  $pay_url = $_SERVER['HTTP_REFERER'];
}
$insertArr = array('order_id'=>$data['mer_order_no'],'user_name'=>$_REQUEST['username'],'pay_type'=>$_REQUEST['type'],'pay_ip'=>get_client_ip(),'sign'=>$data['sign'],'order_money'=>$_REQUEST['coin'],'order_time'=>time(),'pay_api'=>'全球付支付','pay_url'=>$pay_url);
if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
  //exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线支付</title>
</head>
<body onLoad="document.diy.submit();">
<form name='diy' id="diy" action='<?php echo $gateWay; ?>' method='post'>
<?php 
foreach($data as $k => $v){
?>
<input type="hidden" name="<?php echo $k;?>" value="<?php echo $v;?>" />
<?php 
}
?>
</form>
</body>
</html>