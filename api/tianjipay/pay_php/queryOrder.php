
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>商户支付接口示例 - 订单查询</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<div class="container">
    <div class="header">
        <h3>支付接口 - 订单查询示例：</h3>
    </div>
    <div class="main">
        <form method="post" action="doQueryOrder.php">
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
                <li style="margin-top: 50px">
                    <label></label>
                    <button type="submit">查询</button>
                </li>
            </ul>
        </form>
    </div>
</div>
</body>
</html>
