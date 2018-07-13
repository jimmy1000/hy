<?php

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$conf = [
    'member_code' => '20180113439',    //商户号    商户后台->安全设置->获取API接口信息->商户号
    'member_secret' => '02694109109cda4b0ca1fa9b789cddc0',  //交易密钥  商户后台->安全设置->获取API接口信息->交易密钥
    'private_path' => './private_key.pem',	//商户私钥   由商户自行生成
    'public_path' => './public_key.pem',   //平台公钥   商户后台->安全设置->获取API接口信息->平台公钥
    'url' => 'http://www.magopay.net', //支付地址
    'type_codes' => [
        "wxbs" => "微信被扫[wxbs]",
        "wxh5" => "微信H5[wxh5]",
        "zfbbs" => "支付宝被扫[zfbbs]",
        "qqbs" => "QQ钱包被扫[qqbs]",
        "qqh5" => "QQ钱包h5[qqh5]",
        "gateway" => "网关[gateway]",
        "sms" => "短信[sms]",
    ],
    'netbank' => [
        'pc' => '主机端[pc]',
        'h5' => '移动端[h5]',
    ],
];

$pay_callbackurl = 'http://payhy.8889s.com/api/mangopay/callback.php' ; //异步回调地址
$pay_returnurl = 'http://payhy.8889s.com' ; //同步回调地址


