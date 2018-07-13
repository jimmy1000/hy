<?php
require_once '../../pay_mgr/init.php';
include 'config.php';

$post = file_get_contents("php://input");
file_put_contents(dirname(__FILE__).'/log.text', var_export($post , true).PHP_EOL,  FILE_APPEND);

//$database->insert(DB_PREFIX.'callback_log' , ['time'=>date('Y-m-d H:i:s'),
//                                              'contents'=> var_export($post , true),
//                                               'third'=>'xuefu' 
//                                                ]);

$res =  json_decode($post , true);
//$str = 'opay_status=0&';
$str .= getSignStr($res);
$web_public_key = openssl_get_publickey($server_public_key);
$flag = openssl_verify($str, base64_decode($res['sign']), $web_public_key, OPENSSL_ALGO_MD5);
$ip = get_client_ip();
if($flag && $res['pay_status'] == 1){
     $order = $database->get(DB_PREFIX.'preorder', '*' , array('order_id'=>$res['out_trade_no']));
    if(empty($order)){
        file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t". var_export($res ,true).PHP_EOL , FILE_APPEND);
        die('success'); 
    }
     
    if(floatval($order['order_money']*100) == floatval($res['total_fee'])){
        $order_ = $database->get(DB_PREFIX . 'order', '*', array('order_id' => $order['order_id'] ));
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
                    $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$res['out_trade_no']));
        }
        
    }else{
         file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额错误\t". var_export($res , true).PHP_EOL , FILE_APPEND);
    } 
    die('success');   
}
echo 'success';





