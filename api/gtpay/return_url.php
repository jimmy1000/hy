<?php
include './config.php';
/*
 * @Description 银宝接口 
 * @2017-01-04
 * @Author BY K
 * 这是下行异步通知 地址
 */

$form = "<Form action='http://hg.dd788799.com/pay/notify/thirdtype/wfpay/' method='POST'>";
        foreach ($_REQUEST as $k=>$v){
            $form .= "<input name='$k' value='$v' />";
        }
        $form .='</form>';
        file_put_contents(dirname(__FILE__).'/gtlog1.html', $form."\r\n",FILE_APPEND);

 require_once '../../pay_mgr/init.php'; 
$ips = array('47.89.21.252 ','47.89.13.75','47.52.102.52','47.52.59.162','47.91.159.69','47.52.60.66');
$ip = get_client_ip();
if(!in_array($ip,$ips)){
  file_put_contents(dirname(__FILE__).'/warn1.log',date('Y-m-d H:i:s')."\t".'非法IP:'.$ip.'提交了数据:'.$form."\r\n",FILE_APPEND);
  exit();
}

if(!$_GET['partner'] || !$_GET['ordernumber'] || !$_GET['orderstatus'] || !$_GET['paymoney']) {
	die("签名数据不正确1");
}


if($_GET['orderstatus'] !='1') {
	die("支付失败");
}
$partner = $_GET['partner'] ;
$orderno = $ordernumber = $_GET['ordernumber'] ;
$orderstatus = $_GET['orderstatus'] ;
$value = $paymoney = $_GET['paymoney'] ;

$sign = md5("partner={$partner}&ordernumber={$ordernumber}&orderstatus={$orderstatus}&paymoney={$paymoney}".$merchantKey);

 

if($sign != $_GET['sign']) {
	die("验签失败2");
}
	
	$order_state = 1;

	$tmp = explode("|", $_GET['attach']);
	$user_name = $tmp[0];
	$attach = $tmp[1];
	if($attach =='WEIXIN') {
		$attach ='WECHAT';
	}
	// ---  
	 $info = $database->get(DB_PREFIX.'order','*',array('pay_order'=>$orderno));
		if(!$info){
			//订单不存在
			//$arrInsert = array('order_id'=>$orderno,'user_name'=>$user_name,'order_money'=>"$value",'order_time'=>time(),'order_state'=>$order_state,'state'=>0,'pay_type'=>$attach,'pay_api'=>'银宝','pay_order'=>$orderno);
			//$database->insert(DB_PREFIX.'order',$arrInsert);
			echo "支付成功".'<br>';
		echo "商户订单号 ".$orderno.'<br>';
		echo "支付系统订单号 ".$_GET['sysnumber'].'<br>';
		echo "支付金额 ".$paymoney."元".'<br>';
		echo "订单状态 成功 <BR>";
		echo "<a href='http://pay1.zf590.com/'>返回首页</a> <BR>";
		exit; 
		}



		echo "".'<br>';
		echo "商户订单号 ".$orderno.'<br>';
		echo "支付系统订单号 ".$_GET['sysnumber'].'<br>';
		echo "支付金额 ".$paymoney."元".'<br>';
		echo "订单状态完成1 <BR>";
		 




?>