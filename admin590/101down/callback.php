<?php
$p1_MerId		= "8882131";																										#测试使用
$merchantKey	= "b917e04047c94b9bbc198fd73b7f437b";

//异步回调地址
$bankNotify = "http://pay1.zf590.com/admin590/101down/callback.php";
 
        $bankOrderId = $_REQUEST["bankOrderId"];            //商户订单号
       $bankState = $_REQUEST["bankState"];                //代付状态 2 成功 3失败
        $bankMoney = $_REQUEST["bankMoney"];                //代付金额 不包含手续费 单位元
        $sign = $_REQUEST["sign"];                          //数据签名

if($bankMoney <=0) {
	die('代付金额有误');
	
}


$mySign = md5($p1_MerId . $bankOrderId .$bankState .$bankMoney .$merchantKey);
if($bankMoney <=0) {
	die('代付金额有误');
	
}
        if ($sign != $mySign) { //数据签名有误
            die('数据签名有误');
        }
$str="\t\n -----下发数据---------------------------------------------------";		
if($bankState =='2') {
	//应该写入下发记录
	$str .="\t\n 订单号:".$bankOrderId." 成功 金额:".$bankMoney."  时间 :".date("Y-m-d H:i:s");
	
	$str .="\t\n -----------------------------------------------------------------";		

	file_put_contents('log.txt',$str,FILE_APPEND);
	echo 'success';
	exit;
}else {
	$str .="\t\n 订单号:".$bankOrderId." 失败 金额:".$bankMoney."  时间 :".date("Y-m-d H:i:s");
	
	$str .="\t\n -----------------------------------------------------------------";		

	
	echo 'success';
	exit;
}
		

?>