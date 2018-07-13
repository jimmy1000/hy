<?php

/*
 * @Description 汇通科技产品通用接口范例 
 * @V3.0
 * @Author rui.xin
 */
//参数定义


#	商户编号p1_MerId,以及密钥merchantKey 需要从汇通科技汇通平台获得
$p1_MerId	 = "1056";																										#测试使用
$merchantKey = "KCfgka7JZjFsb7apze1yreB6zQZxMS6E";		#测试使用

$logName	= "log.log";


#	产品通用接口正式请求地址
$reqURL_onLine = "https://www.gtonepay.com/hspay/node/";

# 业务类型
# 支付请求，固定值"Buy" .
$p0_Cmd = "Buy";

#	送货地址
$p9_SAF = "0";

//回调地址
//$callbackUrl =  'https://pay1.zf590.com/api/gtonepay/callback.php' ;
$callbackUrl =  'http://payhy.8889s.com/api/gtonepay/callback.php' ;


?> 