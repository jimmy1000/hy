<?php

require_once '../../pay_mgr/init.php';
include 'config.php';

//日志
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . file_get_contents("php://input") . PHP_EOL,FILE_APPEND);
file_put_contents(dirname(__FILE__).'/log', date('Y-m-d H:i:s'). '   |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

//比较签名密钥结果是否一致，一致则保证了数据的一致性
if ($bRet && $r1_Code=='1' ) {

    //这个第三方平台 只提供一个参数位提供回调处理地址,所以这里同步和异步都进行处理
    if ( $r9_BType=="1" ) {
        //同步处理
        echo "success";
    } elseif($r9_BType=="2") {
        //异步处理
        #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
        echo "success";
        //商户自行处理自己的业务逻辑
        $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$r6_Order));

        // 判断金额
        if(abs($info['order_money'] - $r3_Amt) > 1){
            file_put_contents(dirname(__FILE__).'/err.log', date('Y-m-d H:i:s'). ' 回调金额与请求金额不一致不对 |  ' . var_export($response, true) . PHP_EOL . PHP_EOL,FILE_APPEND);
            echo 'error';die;
        }

        if($info){
            $infos = $database->get(DB_PREFIX . 'order', '*', array(
                'order_id' => $info['order_id'],
            ));

            if(!$infos && $info['order_money'] == $r3_Amt){
                $insertArr = array(
                    'order_id' => $info['order_id'],
                    'user_name' => $info['user_name'],
                    'order_money' => $info['order_money'],
                    'order_time' => time(),
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
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST['$r6_Order']));
            }

        } else {
            //     file_put_contents(dirname(__FILE__).'/notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".urldecode($str));
        }
    }

} else {
    echo "error";
}
?>