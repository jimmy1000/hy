<?php

include 'config.php' ;

/**
 *
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 15:13
 */


/**
 *  创建签名
 * @param $data
 * @param $key
 * @return string
 */
function create_sign($data,$key)
{
    $signStr = '' ;
    foreach($data as $k=>$v){
        $signStr .= "{$k}={$v}&";
    }
    $signStr .= 'key='.$key;
    return strtoupper(md5($signStr)) ;
}

/**
 *  将我们平台的支付Cod,e转换为第三方能识别的支付Code
 */
function getPayType($payType)
{
    $type = '' ;
    //1 代表微信, 2 代表支付宝，3代表QQ钱包，4代表京东钱包
    switch ($payType) {
        case 'WECHAT' :
             $type = 1 ;break ;
        case 'WAP' :
            $type = 1 ;break ;
        case 'ALIPAY' :
            $type = 2 ;break ;
        case 'ALIPAYWAP' :
            $type = 2 ;break ;
        case 'QQPAY' :
            $type = 3 ;break ;
        case 'QQPAYWAP' :
            $type = 3 ;break ;
        case 'JDPAY' :
            $type = 4 ;break ;
        case 'JDPAYWAP' :
            $type = 4 ;break ;
    }
    return $type ;
}

/**
 * POST访问
 * @param $url
 * @param $data
 * @return mixed|string
 */
function submitPostData($url,$data) {
    $ch = curl_init() ;
    curl_setopt($ch, CURLOPT_URL, $url) ;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST") ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE) ;
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)') ;
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1) ;
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1) ;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data) ;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
    $tmpInfo = curl_exec($ch) ;
    if ( curl_errno($ch) ) {
        return curl_error($ch) ;
    }
    return $tmpInfo;
}


/**
 * 表单方式
 */
function submitFormMethod($params, $gateway, $method = 'post', $charset = 'utf-8') {
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Content-type:text/html;charset={$charset}");
    $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";

    foreach ($params as $k => $v) {
        $sHtml.= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
    }

    $sHtml = $sHtml . "</form>正在提交支付数据 ...";

    $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";

    return $sHtml;
}