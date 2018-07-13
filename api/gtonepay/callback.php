<?php

/*
 * @Description 汇通支付B2C在线支付接口范例 
 * @V3.0
 * @Author admin
 */

require_once '../../pay_mgr/init.php';
include 'Common.php';

//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);
	
#	只有支付成功时汇通才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	以上代码和变量不需要修改.

#	校验码正确.
if($bRet){

	if($r1_Code=="1"){
		
	#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
	#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.

		if($r9_BType=="1"){

			echo "交易成功22";
			echo  "<br />在线支付页面返回";

		}elseif($r9_BType=="2"){

		    //检查订单号
            $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$r6_Order));

            if ( $info ) {
                //检查支付金额
                if ( floatval($info['order_money']) == floatval($r3_Amt) ) {

                    $orderInfo = $database->get(DB_PREFIX . 'order', '*', array(
                        'order_id' => $info['order_id'],
                    ));

                    if(!$orderInfo){
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

                        $database->insert(DB_PREFIX . 'order', $insertArr); //新增订单数据
                        $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$r6_Order)); //确认该订单已完成支付


                        #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
                        echo "success";
                        echo "<br />交易成功";
                        echo  "<br />在线支付服务器返回";
                    }

                } else {
                    file_put_contents('./money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额{$r3_Amt},数据库存储金额{$info['order_money']}错误\t".serialize($return));
                }

            } else {
                file_put_contents('./notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".serialize($return));
            }

		}
	}
	
}else{
     file_put_contents('./dangerous.log', date('Y-m-d H:i:s')."\tIP:".$ip."交易信息被篡改\t".serialize($return));
	echo "交易信息被篡改";
}
   
?>
<html>
<head>
<title>Return from  Page</title>
</head>
<body>
</body>
</html>