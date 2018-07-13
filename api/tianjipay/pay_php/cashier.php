<?php
/* *
 * 功能：一般支付调试入口页面
 * 版本：1.0
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */
 	require_once("lib/pay.Config.php");
	$time		= time();
	$orderNo	= date("YmdHis",$time);
	$tradeDate	= date("Ymd",$time);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>收银台支付</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>
	<script type="text/javascript">
		$(function(){
			$(".bank-list img").on("click", function() {
				$(".bank-list input[type='radio']").prop("checked", false);
				$(this).prev("input[type='radio']").prop("checked", true);
			})
			$("#ip").val(returnCitySN["cip"]);  
		})
	</script>
</head>
<body>
   <div class="container">
	   <div class="header">
		   <h3>支付接口 - 收银台支付示例：</h3>
	   </div>

	<div class="main">
		 <form target="_blank" method="post" action="doCashier.php">
			<ul>
				<li>
					<label>订单号</label>
					<input type="text" name="tradeNo" value='<?php echo $orderNo; ?>' />

				</li>
				<li>
					<label>交易日期</label>
					<input type="text" name="tradeDate" value='<?php echo $tradeDate; ?>'/>

				</li>
				<li>
					<label>订单金额</label>
					<input type="text" name="amount" value="10.01" />
				</li>
				<li>
					<label>商户参数</label>
					<input type="text" name="extra" value="canshu" />
				</li>
				<li>
					<label>交易摘要</label>
					<input type="text" name="summary" value="zhaiyao" />
				</li>
				<li>
					<label>超时时间</label>
					<input type="text" name="expireTime" value="" />
				</li>
				<li>
					<label>客户端IP</label>
					<input type="text" name="clientIp" id="ip" value="" />
				</li>
				<li style="margin-top: 50px">
					<label></label>
					<button type="submit">确定支付</button>
				</li>
			</ul>
		</form>
	  </div>
    </div>
  </body>
</html>
