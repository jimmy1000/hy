<?php

include 'common.php';

/**
 * 配置文件定义
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 12:48
 */
$merchant_id     = '800066001001' ;//商户ID

$url             = 'https://api.feimiaofu.com/gateway/api/scanpay'  ; //PC支付请求地址

$pay_callbackurl = 'http://payhy.8889s.com/api/ztbpay/callback.php' ; //异步回调地址

$pay_returnurl   = 'http://payhy.8889s.com' ; //同步回调地址

/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
 */
$merchant_private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQDkeTQWk79jOfZdxY0nBoqR/7IIikmJSm2BynW0aMYvuQ5Kgpo0
sMTq8CY57WQZ0yLIubV0Izah/s8R27qgD592XshodoAhs8uBLcWybtUot0PlH/F3
wAid2MHGuczUPwKxTWDMG2z40l9DTN6FF0krfJllyDwwuAOc2i34/i2O3wIDAQAB
AoGAZLj6OanRChGXhyd8XuQHWu36ssEkQh5JwJpc4bf/BzLyFe8VxHzZkylj8M2y
/5+RCiS01gpgH3KSHzFbSQ0aIuPQ7Y24gU8xWCTfd/ar40MlMMEW7As+8of/PqOB
+fNKwxWprQn50KKh8+wFCF5I8U2HgaSXM78rUbvv7yMXYuECQQDym6SzyWdln14s
v3xE9IeqzpO5CuB+h/z/ATnbPB2icPHCh0MTyIYPVrURUQ78puuQB66nMwFgrf9f
4RT5IiYlAkEA8RXSRLvE58GINSnS3uH+hMfvWSbonL1ufxMtrq1fjdGqRfFvVlWj
B8TEaA9IfuyKXdXy/YPiIHFJoLR/MeRnswJBAOMW/49uicNc7skSIF9nSQqAPVRr
MwIdhpqn6iEl31NrR1FgTVBaVepLrkdcSZwDHuJ93mURYEu/8xgrVvdwmKUCQQCg
XY2S3vwp1ViPVJTKYbX2CTCzMuiEapW3vcAjc3weUBtdjy4qVaiuoDqtpYzpsNqR
dKG/9RxTzEfXuHjrbS11AkEArXMOlfim1mBuOazBwfXt4fHZg51RDafeBPQMrn6s
U2T8JzRwBjT8lG38Gul9cBYTUkMONYo9++E3uiYSPL4LIA==
-----END RSA PRIVATE KEY-----';


/**
1)dinpay_public_key，智通宝公钥，每个商家对应一个固定的智通宝公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为智通宝商家后台"公钥管理"->"智通宝公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的智通宝公钥，请自行复制对应商户号的智通宝公钥进行调整和替换。
3）使用智通宝公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
 */
$dinpay_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCRzMKUAnrTEwyxaKAkaiF0Ur5J
b9I5Fh48kAQtRes7NGfkIMolqvWCBHX24SvJHVdP0MTslYzz3ZUOssDVLceTI7uP
CSUX9Fc3ux5VDEGdfNwNAB04i9x4CNPeSBjgXg+vy+yqPGb3eR8LM6gZW61ESjZn
wToL4Tnj2FZ72Iu4eQIDAQAB 
-----END PUBLIC KEY-----';


