<?php
/* *
 * 功能：一般支付处理文件
 * 版本：1.0
 * 日期：2012-03-26
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码。
 */

require_once("lib/pay.Config.php");
require_once("lib/Pay.class.php");

// 请求数据赋值

// 商户APINMAE，WEB渠道一般支付
$data['service'] = $APINAME_QUICKPAY_APPLY;
// 商户API版本
$data['version'] = $API_VERSION;
// 商户在支付平台的的平台号
$data['merId'] = $MERCHANT_ID;
//商户订单号
$data['tradeNo'] = $_POST["tradeNo"];

// 商户订单日期
$data['tradeDate'] = $_POST["tradeDate"];
// 商户交易金额
$data['amount'] =sprintf("%.2f", $_POST["amount"]);
// 商户通知地址
$data['notifyUrl'] = $MERCHANT_NOTIFY_URL;
// 商户扩展字段
$data['extra'] = $_POST["extra"];
// 商户交易摘要
$data['summary'] = $_POST["summary"];
//超时时间
$data['expireTime'] ="";
//客户端ip
$data['clientIp'] ="152.23.63.25";
// 接收银行代码
$data['cardType'] = $_POST["cardType"];
//银行卡号
$data['cardNo'] = $_POST["cardNo"];
//开户姓名
$data['cardName'] = $_POST["cardName"];
//身份证号
$data['idCardNo'] = $_POST["idCardNo"];
//预留手机号
$data['mobile'] = $_POST["mobile"];
//信用卡安全
$data['cvn2'] = "";
//签名
$data['validDate'] = "";
//var_dump($data);
//return;

// 对含有中文的参数进行UTF-8编码
// 将中文转换为UTF-8
if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['notifyUrl']))
{
    $data['notifyUrl'] = iconv("GBK","UTF-8", $data['notifyUrl']);
}


if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['extra']))
{
    $data['extra'] = iconv("GBK","UTF-8", $data['extra']);
}

if(!preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $data['summary']))
{
    $data['summary'] = iconv("GBK","UTF-8", $data['summary']);
}

//echo $data;
// 初始化
$pPay = new Pay($KEY,$GATEWAY_URL);
// 准备待签名数据

$str_to_sign = $pPay->prepareSign($data);
// 数据签名
$signMsg = $pPay->sign($str_to_sign);
//echo  $signMsg;


$data['sign'] = $signMsg;
// 初始化
$pPay = new Pay($KEY,$GATEWAY_URL);
// 准备请求数据
$to_requset = $pPay->prepareRequest($str_to_sign, $signMsg);
//请求数据
$resultData = $pPay->request($to_requset);


//echo  $resultData."<p>换行</p>";

preg_match('{<code>(.*?)</code>}', $resultData, $match);

$code = $match[1];
echo  $code;
echo  "<p></p>";
preg_match('{<opeNo>(.*?)</opeNo>}', $resultData, $match);

$opeNo = $match[1];
echo $opeNo;
echo  "<p></p>";
preg_match('{<opeDate>(.*?)</opeDate>}', $resultData, $match);

$opeDate = $match[1];
echo $opeDate;
echo  "<p></p>";
preg_match('{<sessionID>(.*?)</sessionID>}', $resultData, $match);

$sessionID = $match[1];
echo   $sessionID;

//opeNo
//echo $respCode;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>快捷支付验证码确定</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#button").click(function(){
                var code= $("#code");
                alert(code);
                if(code!= 00){
                    return false;
                }
                $("#quickConfirm").submit();

            }
         })
    </script>
</head>
<body>
<div class="container">
    <div class="header">
        <h3>支付接口 - H5接口示例：</h3>
    </div>
    <input type="hidden"  id="code"  name="code" value ='<?php echo $code; ?>' />


    <div class="main">
        <form method="post" action="quickPayConfirm.php" id="quickConfirm">


             <input type="hidden"  name="opeNo" value ='<?php echo $opeNo; ?>' />
             <input type="hidden"  name="opeDate" value='<?php echo $opeDate; ?>'/>
             <input type="hidden"  name="sessionID" value='<?php echo $sessionID; ?>' />

           <ul>
               <li>
                   <label>短信验证码</label>
                   <input type="text" name="dymPwd"  value="">
               </li>

                 <li style="margin-top: 50px">
                 <button id="submit">确认支付</button>
              </li>

          </ul>

        </form>
  </div>






</div>
</body>
</html>





