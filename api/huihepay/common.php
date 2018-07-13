<?php
/**
 * 常用函数文件
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:52
 */


/**
 * 获取支付类型
 *
 */

    $payType = '' ;
    $words   = '' ;
    $type = $_REQUEST['type'] ;
    switch ($type) {

        case 'WECHAT' :
            //$payType = 10000103 ;
            $payType = 10000403 ;
            $words = '微信';
            $isWAP='0';
            $isScan='1';
            break ;
        case 'WAP' :
            $payType = 10000503 ;
            $words = '微信';
            $isScan='1';
            $isWAP='1';
            break ;

        case 'ALIPAY' :
            //$payType = "20000303" ;
            $payType = "20000304" ;//暂时改为收银台支付
            $words = '支付宝';
            $isScan='1';
            $isWAP='0';
            break ;

        case 'ALIPAYWAP' :
            //$payType = 20000203 ;
            $payType = 20000305;
            $words = '支付宝';
            $isScan='1';
            $isWAP='1';
            break ;


        case 'QQPAY' :
//            $payType = "70000203" ;
            $payType = "70000205" ;
            $words = 'QQ';
            $isWAP='0';
            $isScan='1';
            break ;
        case 'QQPAYWAP' :
//            $payType = 70000204;
            $payType = 70000206;
            $words = 'QQ';
            $isScan='1';
            break ;

        case 'JDPAY' :
            $payType = 80000203 ;
            $words = '京东';
            $isScan='1';
            $isWAP='0';
            break ;

        case 'BANK' :
            $payType = 60000103 ;
            $words = '银联';
            break ;
        case 'BANKWAP' :
            $payType = 1 ;
            $words = '银联';
            $isWAP='1';
            break ;
        case 'BANKSCAN' :
            $payType = 30104 ;
            $words = '银联扫码';
            $isScan='1';
            $isWAP='0';
            break ;
        case 'BANKQUICK' :
            $payType = 12 ;
            $words = '快捷支付';
            $isWAP='0';
            break ;


        default:
                break ;
    }
function getLocalRequestUrl()
{
    $pay_url = '' ;
    //记录支付处理地址
    if ( !$_SERVER['HTTP_REFERER'] ) {
        $pay_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'] ;
    } else {
        $pay_url = $_SERVER['HTTP_REFERER'] ;
    }
    return $pay_url ;
}
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
function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;

}
function post($url,$data){ #POST访问
    $ch = curl_init();                                              //初始化cURL会话
    curl_setopt($ch, CURLOPT_URL, $url);                    //需要获取的URL地址,也可以在curl_init()初始化会话的时候
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); //HTTP请求时,用Method来代替"GET"或"HEAD",有效值为"GET","POST.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//  把curl_exec获取的数据返回 而不是直接输出
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//TRUE时将会根据服务器返回HTTP头中的"Location:"重定向.
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);//true时根据location:重定向时
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);//false禁止cURL验证对等证书(peer's certificate).
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);   //检查服务器SSL证书中是否存在一个公用名 0位不检查  1检查  2检查共用名提供主机名匹配
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');//在HTTP请求中包含一个"User-Agent: "头的字符串。
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//提交的post数据 http_build_query()函数是使关联数组生成一个经过URL-encode的请求字符串.

    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    return $tmpInfo;
}
