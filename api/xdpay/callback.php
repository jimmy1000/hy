<?php
// +----------------------------------------------------------------------
// | FileName: callback.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年12月14日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
require_once '../../pay_mgr/init.php';
include 'config.php';
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);

$signStr = '';
ksort($_REQUEST);
foreach ($_REQUEST as $k => $v){
    if($k != 'sign' && $k !='sign_type' ){
        $signStr .= "$k=$v&";
    }
}
$signStr .= "key=$key";
$mySign = strtoupper(md5($signStr));
if($mySign == $_REQUEST['sign']){
    if($_REQUEST['tradestate'] == '100'){
        $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$_REQUEST['outorderno']));
        if($info){
            if(floatval($info['order_money']) == floatval($_REQUEST['totalfee']/100)){
                $infos = $database->get(DB_PREFIX . 'order', '*', array(
                    'order_id' => $info['order_id'],
                ));
                if(!$infos){
                    $insertArr = array(
                        'order_id' => $info['order_id'],
                        'user_name' => $info['user_name'],
                        'order_money' => $info['order_money'],
                        'order_time' => date('Y-m-d H:i:s'),
                        'order_state' => 1,
                        'state' => 0,
                        'pay_type' => $info['pay_type'],
                        'pay_api' => $info['pay_api'],
                        'pay_order' => $info['order_id'],
                    );
                    $updateArr = array(
                        'notify_ip' => get_client_ip(),
                        'notify_time' => date('Y-m-d H:i:s'),
                    );
                    $database->insert(DB_PREFIX . 'order', $insertArr);
                    $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['outorderno']));
                    
                }
            }else{
                file_put_contents(dirname(__FILE__).'/money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额{$_REQUEST['totalfee']},数据库存储金额{$info['pay_money']}错误\t".urldecode($str));
            }
        }else{
            file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
        }
    }
}