<?php
// +----------------------------------------------------------------------
// | FileName:汇付宝
// +----------------------------------------------------------------------

require_once '../../pay_mgr/init.php';
require_once './common.php' ;

$user_ip         = get_client_ip();
$version         = 1;
$agent_bill_id   = date("YmdHis").rand(100000,999999) ; //订单号;
$agent_bill_time = date('YmdHis', time());
$pay_amt         = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$notify_url      = $pay_callbackurl;
$return_url      = $pay_returnurl;
$goods_name      = 'online-pay';
$remark          = $_REQUEST['username'].'-'.$_REQUEST['type'];
$pay_type        = getPayType() ; //支付类型


/*************创建签名***************/
$sign_str  = '';
$sign_str  = $sign_str . 'version=' . $version;
$sign_str  = $sign_str . '&agent_id=' . $agent_id;
$sign_str  = $sign_str . '&agent_bill_id=' . $agent_bill_id;
$sign_str  = $sign_str . '&agent_bill_time=' . $agent_bill_time;
$sign_str  = $sign_str . '&pay_type=' . $pay_type;
$sign_str  = $sign_str . '&pay_amt=' . $pay_amt;
$sign_str  = $sign_str .  '&notify_url=' . $notify_url;
$sign_str  = $sign_str . '&return_url=' . $return_url;
$sign_str  = $sign_str . '&user_ip=' . $user_ip;
$sign_str  = $sign_str . '&key=' . $key;

$sign   = (md5($sign_str)); //签名值

$pay_url = getLocalRequestUrl() ;
$insertArr = array(
    'order_id'=>$agent_bill_id,
    'user_name'=>$_REQUEST['username'],
    'pay_type'=>$_REQUEST['type'],
    'pay_ip'=>get_client_ip(),
    'sign'=>$sign,
    'order_money'=>$_REQUEST['coin'],
    'order_time'=>time(),
    'pay_api'=>'汇付宝支付',
    'pay_url'=>$pay_url
);
if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

?>
<html>
<head>
</head>
<body onLoad="document.frmSubmit.submit();">
<form id='frmSubmit' method='post' name='frmSubmit' action='<?php echo $url;?>'>
<input type='hidden' name='version' value='<?php echo $version;?>' />
<input type='hidden' name='agent_id' value='<?php echo $agent_id;?>' />
<input type='hidden' name='agent_bill_id' value='<?php echo $agent_bill_id;?>' />
<input type='hidden' name='agent_bill_time' value='<?php echo  $agent_bill_time;?>' />
<input type='hidden' name='pay_type' value='<?php echo $pay_type;?>' />
<!--<input type='hidden' name='pay_code' value='--><?php //echo $pay_code;?><!--' />-->
<input type='hidden' name='pay_amt' value='<?php echo $pay_amt;?>' />
<input type='hidden' name='notify_url' value='<?php echo $notify_url;?>' />
<input type='hidden' name='return_url' value='<?php echo $return_url;?>' />
<input type='hidden' name='user_ip' value='<?php echo $user_ip;?>' />
<input type='hidden' name='goods_name' value='<?php echo ($goods_name);?>' />
<!--<input type='hidden' name='goods_num' value='--><?php //echo  urlencode($goods_num);?><!--' />-->
<!--<input type='hidden' name='goods_note' value='--><?php //echo urlencode($goods_note);?><!--' />-->
<input type='hidden' name='remark' value='<?php echo ($remark);?>' />
<!--<input type='hidden' name='is_phone' value='--><?php //echo $is_phone;?><!--' />-->
<!--<input type='hidden' name='is_frame' value='0' />-->
<input type='hidden' name='sign' value='<?php echo $sign;?>' />
</form>
</body>
</html>