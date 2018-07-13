<?php


/*
===============================================================================
接收银行支付的下行数据

orderid	
上行过程中传入的orderid

opstate	
操作结果状态:
	0 在线支付成功
	-1 请求参数无效
	-2 签名错误
	-5 在线支付失败

ovalue	
实际支付的金额，单位元

sysorderid
	网银接口v1.2 版本新增
	此次卡消耗过程中平台的订单Id。为保持和以前版本兼容，该值不加入返回结果签名验证。

systime
	网银接口v1.2 版本新增
	此次卡消耗过程中平台的订单结束时间。格式为年/月/日 时：分：秒，如2010/04/05 21:50:58。
	为保持和以前版本兼容，该值不加入返回结果签名验证。
================================================================================
*/
header('Content-Type:text/html;charset=GB2312');
require_once("./config.php");
require_once("./lib/class.bankpay.php");
require_once '../../pay_mgr/init.php';

//将异步通知记录下来
$form = '<form action="" >';
foreach($_REQUEST as $k => $v){
    $form .= "<input type='' name='$k' value='$v' />";
}
$form .="<form/>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);

//获取返回的下行数据
$orderid        = trim($_GET['orderid']);
$opstate        = trim($_GET['opstate']); //返回支付状态 0:成功 1:失败 2:签名错误
$ovalue         = trim($_GET['ovalue']); //金额
$sysorderid		= trim($_GET['sysorderid']);
$completiontime	= trim($_GET['systime']);
$sign		    =  isset($_GET['sign']) ? trim($_GET['sign']) : '';

//进行爱扬签名认证
$bankpay		= new bankpay();
$bankpay->key	= $merchant_key;
$bankpay->recive();

//////////////////////////////////////////////////////////////////////////
// 进入到这一步，说明签名已经验证成功，
// 你可以在这里加入自己的代码, 例如：可以将处理结果存入数据库



if ($opstate == 0) {
    //支付成功
    //检查订单号
    $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$orderid));

    if ( $info ) {
        //检查支付金额
        if ( floatval($info['order_money']) == floatval($ovalue) ) {

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
                    'notify_time' => $completiontime,
                );

                $database->insert(DB_PREFIX . 'order', $insertArr); //新增订单数据
                $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$orderid)); //确认该订单已完成支付


                #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
                echo "success";
                echo "<br />交易成功";
                echo  "<br />在线支付服务器返回";
            }

        } else {
            file_put_contents('./money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额{$r3_Amt},数据库存储金额{$info['order_money']}错误\t".serialize($_GET));
        }

    } else {
        file_put_contents('./notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".serialize($_GET));
    }

} else {
    //支付失败
}






//////////////////////////////////////////////////////////////////////////
//完成之后返回成功
/*
	协议:
	0 成功
	-1 请求参数无效
	-2 签名错误
*/
die("opstate=0");
?>