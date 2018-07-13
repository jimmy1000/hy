<?php
	/* *
 * 演示入口页面
 * 版本：1.0
 * 日期：2015-03-26
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="pragma" content="no-cache"/>
      <meta http-equiv="cache-control" content="no-cache"/>
      <meta http-equiv="expires" content="0"/>
      <meta http-equiv="keywords" content="keyword1,keyword2,keyword3"/>
      <meta http-equiv="description" content="This is my page"/>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  </head>
  
  <body>
    <div class="container">

          <div class="header">
          <h3>收银台模式：</h3>
         </div>


         <div class="main">
             <p><a class="no_text_decor">一.直连模式</a></p>
             <p><a href="bankPay.php" class="no_text_decor">1、网银支付</a></p>
             <p><a href="scanPay.php" class="no_text_decor">2、扫码支付</a></p>
             <p><a href="quickPay.php" class="no_text_decor">3、快捷支付</a></p>
			 <p><a href="bankQuickPay.php" class="no_text_decor">4、网银快捷支付</a></p>
             <p><a href="queryOrder.php" class="no_text_decor">5、支付订单查询</a></p>
             <p><a href="refundOrder.php" class="no_text_decor">6、退款申请</a></p>
             <p><a href="singleSett.php" class="no_text_decor">7、单笔委托结算</a></p>
             <p><a href="singleSettQuery.php" class="no_text_decor">8、单笔委托结算查询</a></p>
             <p><a href="yueQuery.php" class="no_text_decor">9、余额查询</a></p>
             <p><a href="cashier.php" class="no_text_decor">二.收银台模式</a></p>
             <p><a href="h5Pay.php" class="no_text_decor">三.H5支付（仅限手机浏览器使用）</a></p>
        </div>


   </div>


  </body>
</html>
