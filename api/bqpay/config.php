<?php
//商户号
$partner = '1697285733';

// MD5密钥，安全检验码，由数字和字母组成的字符串，请登录商户后台查看
$key	= '15229614812B2AB3DB3174A8EEECC4E4';

//网关接口地址
$apiurl = "https://bq.baiqianpay.com/webezf/web/?app_act=openapi/bq_pay/pay";

// 服务器异步通知页面路径  需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$callbackurl = 'http://payhy.8889s.com/api/bqpay/callback.php';

// 页面跳转同步通知页面路径 需http://格式的完整路径，不能加?id=123这类自定义参数，必须外网可以正常访问
$hrefbackurl = 'http://'.$_SERVER['SERVER_NAME'];

//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

$convert = array(
    'ALIPAY' => 'ALIPAYSM',
     'ALIPAYWAP' =>"ALIPAYH5",
    'WECHAT' => 'WXSM',
    'WAP'=> 'WXH5',
    'QQPAY'  => 'QQSM',
    'UNION' => 'BSM',
    'JDPAY' => 'JDSM',
	"BANK"=>"KJZF",
);
function form($params, $gateway, $method = 'post', $charset = 'utf-8') {
		header("Cache-Control: no-cache");
		header("Pragma: no-cache");
        header("Content-type:text/html;charset={$charset}");
        $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";

        foreach ($params as $k => $v) {
            $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        }

        $sHtml = $sHtml . "</form>正在跳转 ...";

        $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";

        return $sHtml;
    }