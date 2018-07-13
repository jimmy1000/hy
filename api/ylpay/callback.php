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
// | CreateDate: 2018年1月16日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
require_once './config.php';

//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
$ip = get_client_ip();
if($_REQUEST['payResult'] == '1'){
    if(check_sign($pub_key)){
        $order = $database->get(DB_PREFIX.'preorder', '*' , array('order_id'=>$_REQUEST['orderNo']));
        if(empty($order)){
            file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t". var_export($_REQUEST ,true).PHP_EOL , FILE_APPEND);
            die('success');
        }
        if(strval($order['order_money']*100) == strval($_REQUEST['orderAmount'])){
            $order = $database->get(DB_PREFIX . 'order', '*', array('order_id' => $order['order_id'] ));
            if(empty($order)){
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
                var_dump($database->last_query());
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['orderNo']));
                var_dump($database->last_query());
            }
            
        }else{
            file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额错误\t". var_export($_REQUEST , true).PHP_EOL , FILE_APPEND);
        } 
    }else {
        exit('签名错误!');
    }
}else{
    exit('未支付!');
}




