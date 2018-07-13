<?php
session_start();
if(!isset($_SESSION['card_admin'])){
	echo '<script>window.location.href="login.php";</script>';
	return;
}
$user = $_SESSION['card_admin']['username'];
$level = $_SESSION['card_admin']['level'];
$nickname = $_SESSION['card_admin']['nickname'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>101下发</title>


<style>

body{ font-family:"Microsoft YaHei UI", "微软雅黑", Arial}

</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="./down.php">
  <table width="500" border="0">
    <tr>
      <td height="33" colspan="2">101调用下发</td>
    </tr>
	  <tr>
      <td width="159" height="33">选择商家</td>
      <td width="331" height="33">
      <select name="pid" id="pid">
      <option value="8882131"> 8882131</option>
        <option value="8882705"> 8882705</option>
      </select></td>
    </tr>
    <tr>
      <td width="159" height="33">收款银行名称</td>
      <td width="331" height="33"><label for="textfield"></label>
	  <select name="bankName" id="bankName"> <option value='工商银行'>工商银行</option> </select> 
      </td>
    </tr>
    <tr>
      <td height="33">收款银行支行名称</td>
      <td height="33"><label for="textfield2"></label>
      <input type="text" name="bankSubName" id="bankSubName"  /></td>
    </tr>
    <tr>
      <td height="33">银行卡号</td>
      <td height="33"><label for="textfield3"></label>
      <input type="text" name="bankCardNum" id="bankCardNum" /></td>
    </tr>
    <tr>
      <td height="33">银行卡执卡人姓名</td>
      <td height="33"><label for="textfield4"></label>
      <input type="text" name="bankSigner" id="bankSigner" /></td>
    </tr>
    <tr>
      <td height="33">下发金额</td>
      <td height="33"><label for="textfield5"></label>
      <input type="text" name="bankMoney" id="bankMoney" />
      只能整数</td>
    </tr>
    <tr>
      <td height="33"><input type="hidden" name="md5" id="md5"   value="<?php echo md5("gk321dfff");?>" /></td>
      <td height="33"><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
</body>
</html>
