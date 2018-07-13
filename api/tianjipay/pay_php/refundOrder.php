<?php
	/* *
 * 退款调试入口页面
 * 版本：1.0
 * 日期：2017.1.5
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */
	$time		= time();
	$tradeDate	= date("Ymd",$time);
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>商户支付接口示例 - 退款申请</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<div class="container">
    <div class="header">
        <h3>支付接口 - 退款申请示例：</h3>
    </div>
    <div class="main">
        <form method="post" action="doRefundOrder.php">
            <ul>
                <li>
                    <label>订单号</label>
                    <input type="text" name="tradeNo" />
                </li>
                <li>
                    <label>交易日期</label>
                    <input type="text" name="tradeDate" />
                </li>
                <li>
                    <label>订单金额</label>
                    <input type="text" name="amount" value="0.01" />
                </li>
                <li>
                    <label>交易摘要</label>
                    <input type="text" name="summary" value="退款测试" />
                </li>
                <li style="margin-top: 50px">
                    <label></label>
                    <button type="submit">退款</button>
                </li>
            </ul>
        </form>
    </div>
</div>
</body>
</html>
