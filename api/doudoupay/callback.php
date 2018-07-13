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
// | CreateDate: 2018年1月6日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require 'api_sdk/init.php';
require_once '../../pay_mgr/init.php';
$form = "<form action='http://www.sky1005.com/api/doudoupay/callback.php'>";
foreach($_REQUEST as $k => $v){
    $form .= "<input name='$k' value='$v' />";
}
$form .= "</form>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
ksort($_REQUEST);
reset($_REQUEST);
$signstr = '';
foreach($_REQUEST as $k =>$v){
    if($v != '' && $k != 'sign'){
        $signstr .="$k=$v&";
    }
}
$signstr .= "key=".SING_KEY;
$mySign = md5($signstr);
$ip = get_client_ip();
if($mySign == $_REQUEST['sign']){
    if($_REQUEST['trade_result'] == 'SUCCESS'){
        $order = $database->get(DB_PREFIX.'preorder', '*' , array('order_id'=>$_REQUEST['out_trade_no']));
        if(empty($order)){
            file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t". var_export($_REQUEST).PHP_EOL , FILE_APPEND);
            die('success');
        }
        
        if(floatval($order['order_money']*100) == floatval($_REQUEST['trade_amount'])){
            $order_ = $database->get(DB_PREFIX . 'order', '*', array('order_id' => $order['out_trade_no'] ));
            if(empty($order_)){
                $insertArr = array(
                    'order_id' => $order['order_id'],
                    'user_name' => $order['user_name'],
                    'order_money' => $order['order_money'],
                    'order_time' => time(),
                    'order_state' => 1,
                    'state' => 0,
                    'pay_type' => $order['pay_type'],
                    'pay_api' => $order['pay_api'],
                    'pay_order' => $order['order_id'],
                );
                $updateArr = array(
                    'notify_ip' => get_client_ip(),
                    'notify_time' => date('Y-m-d H:i:s'),
                );
                $database->insert(DB_PREFIX . 'order', $insertArr);
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['out_trade_no']));
            }
            
        }else{
            file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额错误\t". var_export($_REQUEST).PHP_EOL , FILE_APPEND);
        }
        die('success');  
    }
}else{
    die('签名错误!');
}