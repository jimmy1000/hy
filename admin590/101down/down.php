<?php
session_start();
if(!isset($_SESSION['card_admin'])){
	echo '<script>window.location.href="login.php";</script>';
	return;
}
$user = $_SESSION['card_admin']['username'];
$level = $_SESSION['card_admin']['level'];
$nickname = $_SESSION['card_admin']['nickname'];




if(isset($_POST['pid'])) {
	if($_POST['pid'] =='8882131') {
		$p1_MerId ='8882131';
		$merchantKey ='b917e04047c94b9bbc198fd73b7f437b';
	}
	if($_POST['pid'] =='8882705') {
		$p1_MerId ='8882705';
		$merchantKey ='cdb82c6e39054ba1bd6d8c39da4118a4';
	}
}
//$p1_MerId		="8882705"; // "8882131";																										#测试使用
//$merchantKey	=  "cdb82c6e39054ba1bd6d8c39da4118a4"; // "b917e04047c94b9bbc198fd73b7f437b";

//异步回调地址
$bankNotify = "http://pay1.zf590.com/admin590/101down/callback.php";
 





if(isset($_POST['md5'])) {
	$bankName = $_POST['bankName'];
	$bankOrderId = "101d".rand(10,99)."-".date("YmdHis");
	$bankCardNum = $_POST['bankCardNum'];
	$bankMoney = $_POST['bankMoney'];
	$bankSubName = $_POST['bankSubName']; 
	$bankSigner = $_POST['bankSigner'];
	$url="http://api.101ka.com/gateway/bank/paytobank.aspx";
	//MD5(patter + bankOrderId + bankCardNum + bankMoney + key) .ToLower()  sign = MD5(patter + bankOrderId + bankCardNum + bankMoney + key); 
	$sign =  md5($p1_MerId . $bankOrderId . $bankCardNum .$bankMoney . $merchantKey);
	 
	
}

if(isset($sign)) {
//下发时写入 
	$str .="\t\n 订单号:".$bankOrderId." 下发 IP： ".$_SERVER['REMOTE_ADDR']." 金额:".$bankMoney."  时间 :".date("Y-m-d H:i:s");
	
	$str .="\t\n -----------------------------------------------------------------";		

	file_put_contents('action.txt',$str,FILE_APPEND);

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>101下发</title>
</head>

<body  onload="form1.submit()">
<form id="form1" name="form1" method="post" action="http://api.101ka.com/gateway/bank/paytobank.aspx">
  <table width="500" border="0">
    <tr>
      <td height="33" colspan="2">
      
      
      
      
      <input name="patter" type="hidden" value="<?=$p1_MerId?>" />
        <input name="bankOrderId" type="hidden" value="<?=$bankOrderId?>" />    
      <input name="bankName" type="hidden" value="<?=$bankName?>" /> 
       <input name="bankSubName" type="hidden" value="<?=$bankSubName?>" />
        <input name="bankCardNum" type="hidden" value="<?=$bankCardNum?>" />
      
        <input name="bankSigner" type="hidden" value="<?=$bankSigner?>" />
        <input name="bankMoney" type="hidden" value="<?=$bankMoney?>" />
            <input name="bankNotify" type="hidden" value="<?=$bankNotify?>" />  
            
            <input name="sign" type="hidden" value="<?=$sign?>" />      
      &nbsp;</td>
    </tr>
    <tr>
      <td width="159" height="33">&nbsp;</td>
      <td width="331" height="33">&nbsp;</td>
    </tr>
    <tr>
      <td height="33">&nbsp;</td>
      <td height="33">&nbsp;</td>
    </tr>
    <tr>
      <td height="33">&nbsp;</td>
      <td height="33">&nbsp;</td>
    </tr>
    <tr>
      <td height="33">&nbsp;</td>
      <td height="33">&nbsp;</td>
    </tr>
    <tr>
      <td height="33">&nbsp;</td>
      <td height="33">&nbsp;</td>
    </tr>
    <tr>
      <td height="33">&nbsp;</td>
      <td height="33">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
