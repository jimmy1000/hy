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
// | FileName: callback.php
// +----------------------------------------------------------------------
// | CreateDate: 2018年3月1日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once 'common.php';
require_once 'config.php';
require_once '../../pay_mgr/init.php';
require_once './lib/payNotify.class.php';
error_reporting(E_ALL);
ini_set('display_errors', true);
$form = "<form>";
foreach ($_REQUEST as $k => $v){
    $form .= "<input name='$k' value='$v' />";
}
$form .= "<input type='submit'/></form>";
echo '1111';
file_put_contents(dirname(__FILE__).'/nclog.html', $form."\r\n",FILE_APPEND);
file_put_contents(dirname(__FILE__).'/nclog2.html', file_get_contents("php://input")."\r\n",FILE_APPEND);
$pay = new payNotify($pay_config);
$result = $pay->notify();

if ($result) {
    // 响应报文, 处理成功请返回success
    if ($result['return_code']) {
        die($result['return_code']);
    }else{
        
    }
}
