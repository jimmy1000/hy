<?php
// +----------------------------------------------------------------------
// | FileName:付汇宝--网银支付
// +----------------------------------------------------------------------
require_once  '../../pay_mgr/init.php';
include       './common.php' ;


//组合支付参数
$user_ip         = str_replace('.','_',get_client_ip()) ;
$version         = 3;
$agent_id        = $mer_no;
$agent_bill_id   = date("YmdHis").rand(100000,999999) ; //订单号;
$agent_bill_time = date('YmdHis', time());
$bank_card_type	 = -1 ; //银行类型：未知=-1，储蓄卡=0，信用卡=1。
$pay_type        = 20; //支付类型  默认20 , 0表示跳转到汇付宝选择支付银行
$pay_code        = getBankCode(); //银行对应代码
$pay_amt         = sprintf("%.2f",$_REQUEST['coin']); //支付金额保留两位小数
$notify_url      = $pay_callbackurl ;
$return_url      = $pay_returnurl ;
$goods_name      = 'online-pay';
$remark          = $_REQUEST['username'].'-'.$_REQUEST['type'] ;
$sign_key        = $key; //签名密钥


//组合签名
$sign_str  = '';
$sign_str  = $sign_str . 'version=' . $version;
$sign_str  = $sign_str . '&agent_id=' . $agent_id;
$sign_str  = $sign_str . '&agent_bill_id=' . $agent_bill_id;
$sign_str  = $sign_str . '&agent_bill_time=' . $agent_bill_time;
$sign_str  = $sign_str . '&pay_type=' . $pay_type;
$sign_str  = $sign_str . '&pay_amt=' . $pay_amt;
$sign_str  = $sign_str . '&notify_url=' . $notify_url;
$sign_str  = $sign_str . '&return_url=' . $return_url;
$sign_str  = $sign_str . '&user_ip=' . $user_ip;
$sign_str  = $sign_str . '&bank_card_type=' . $bank_card_type;
$sign_str  = $sign_str . '&key=' . $sign_key;

$sign      = md5($sign_str); //计算签名值

$pay_url   = getLocalRequestUrl() ; //获取用户支付地址
$insertArr = array(
    'order_id'=>$agent_bill_id,
    'user_name'=>$_REQUEST['username'],
    'pay_type'=>$_REQUEST['type'],
    'pay_ip'  =>get_client_ip(),
    'sign'    =>$sign,
    'order_money'=>$pay_amt,
    'order_time'=>time(),
    'pay_api'=>'汇付宝',
    'pay_url'=>$pay_url
);
if(!$database->insert(DB_PREFIX.'preorder',$insertArr)){
    exit("<script>alert('创建订单失败!');history.go(-1);</script>");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>网银支付跳转中</title>
</head>
<body>
<form id='frmSubmit' method='post' name='frmSubmit' action='<?php echo $url;?>'>
    <input type='hidden' name='version' value='<?php echo $version;?>' />
    <input type='hidden' name='agent_id' value='<?php echo $agent_id;?>' />
    <input type='hidden' name='agent_bill_id' value='<?php echo $agent_bill_id;?>' />
    <input type='hidden' name='agent_bill_time' value='<?php echo  $agent_bill_time;?>' />
    <input type='hidden' name='bank_card_type' value='<?php echo  $bank_card_type;?>' />
    <input type='hidden' name='pay_type' value='<?php echo $pay_type;?>' />
    <input type='hidden' name='pay_code' value='<?php echo $pay_code;?>' />
    <input type='hidden' name='pay_amt' value='<?php echo $pay_amt;?>' />
    <input type='hidden' name='notify_url' value='<?php echo $notify_url;?>' />
    <input type='hidden' name='return_url' value='<?php echo $return_url;?>' />
    <input type='hidden' name='user_ip' value='<?php echo $user_ip;?>' />
    <input type='hidden' name='goods_name' value='<?php echo urlencode($goods_name);?>' />
    <input type='hidden' name='remark' value='<?php echo urlencode($remark);?>' />
    <?php
    //如果是移动端,则is_phone 字段值改变为1
    if (isMobile()) {
        echo "<input type='hidden' name='is_phone' value='1' />" ;
    }
    ?>
    <input type='hidden' name='sign' value='<?php echo $sign;?>' />
</form>
<script type="text/javascript">
    document.frmSubmit.submit();
</script>
</body>
</html>