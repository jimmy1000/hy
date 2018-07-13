<?php

/*
 * @Description 银宝接口 
 * @2017-01-04
 * @Author BY K
 */

#	商户编号p1_MerId,以及密钥merchantKey 需要从普讯科技101卡平台获得
$partner		= "13501";																										#测试使用
$merchantKey	= "76fede988683a787a143414a9ac46169";

//8883106
//商户密钥：
//cbeedab7ee064a00bc437c174e1e83a4

//网关地址
$gateWay = 'https://wgtj.gaotongpay.com/PayBank.aspx';

//同步时返回的商家域名 app1.nweui.com 
$hostUrl ='';

//日志文件名称
$logName	= "log.log";

//异步回调地址
$callbackurl = "http://pay-900.com/api/gtpay/callbackurl.php";

//同步回调地址
$hrefbackurl = "http://pay-900.com/api/gtpay/return_url.php";

$myKey='g1g3g5fdg';


function is_mobile(){

    // returns true if one of the specified mobile browsers is detected
    // 如果监测到是指定的浏览器之一则返回true
    
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    
   $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";   
    
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    
    $regex_match.=")/i";
    
    // preg_match()方法功能为匹配字符，既第二个参数所含字符是否包含第一个参数所含字符，包含则返回1既true
    return preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}
     
?> 