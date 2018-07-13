<?php
// 
//                                  _oo8oo_
//                                 o8888888o
//                                 88" . "88
//                                 (| -_- |)
//                                 0\  =  /0
//                               ___/'==='\___
//                             .' \\|     |// '.
//                            / \\|||  :  |||// \
//                           / _||||| -:- |||||_ \
//                          |   | \\\  -  /// |   |
//                          | \_|  ''\---/''  |_/ |
//                          \  .-\__  '-'  __/-.  /
//                        ___'. .'  /--.--\  '. .'___
//                     ."" '<  '.___\_<|>_/___.'  >' "".
//                    | | :  `- \`.:`\ _ /`:.`/ -`  : | |
//                    \  \ `-.   \_ __\ /__ _/   .-` /  /
//                =====`-.____`.___ \_____/ ___.`____.-`=====
//                                  `=---=`
// 
// 
//               ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
//                          佛祖保佑         永不宕机/永无bug
// +----------------------------------------------------------------------
// | FileName: config.php
// +----------------------------------------------------------------------
// | CreateDate: 2018年1月6日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
define('PID', '10803158000000165666');//签约PID
define('MERCHANT_NO', '1180105233133853');//商户号
define('SECRET_KEY', '4e584da9e8a3973f4bef4ed184b784e1');//secret
define('SING_KEY', '148b169b93e620ee38eae4db91f53d51');//MD5签名密钥
define('PUBLIC_KEY_FILE', DOUDOUPAY_PATH . '/../pem/public_key.pem');  //公钥文件路径
define('PRIVATE_KEY_FILE', DOUDOUPAY_PATH . '/../pem/private_key.pem'); //私钥文件路径
define('PRIVATE_KEY_PASSWORD', 256365); //私钥密码
define('RETURN_CIPHERTEXT', true); //是否返回密文(true返回密文，false返回明文)
//define('SERVER_URL', 'https://api.doudoupay.com/v1'); //正式环境URL
define('SERVER_URL', 'https://api.doudoupay.com/v1'); //正式环境URL
define('NOTIFY_URL', 'http://payhy.8889s.com/api/doudoupay/callback.php'); //回调地址
define('GETTIME', time()); //时间

