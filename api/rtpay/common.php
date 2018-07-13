<?php

include 'config.php' ;
/**
 * 常用函数文件
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:52
 */

function getPayType($type)
{
    $payType = '' ;

    switch ($type) {
        case 'ALIPAY' :
            $payType =  1003 ;
            break ;
        case 'ALIPAYWAP' :
            $payType =  1006 ;
            break ;

        case 'WECHAT' :
            $payType =  1004 ;
            break ;
        case 'WAP' :
            $payType =  1007 ;
            break ;

        case 'JDPAY' :
            $payType =  1010 ;
            break ;

        case 'BANKWAP' :
            $payType =  1005 ;
            break ;

        case 'BANKSCAN' :
            $payType =  2000 ;
            break ;
    }
    return $payType ;
}

/**
 *  创 建 签 名
 * @param $data
 * @param $key
 */
function create_sign($data,$key)
{
    $str = sprintf( "parter=%s&type=%s&value=%s&orderid=%s&callbackurl=%s%s" ,$data['parter'],$data['type'],$data['value'],$data['orderid'],$data['callbackurl'],$key) ;
    return md5($str) ;
}

/**
 * 返回本地请求地址
 */
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


function dd($arr)
{
    echo '<pre>';
    print_r($arr) ;
    die;
}

