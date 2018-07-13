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
// | CreateDate: 2018年1月7日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
include 'config.php';
require_once '../../pay_mgr/init.php';
$form = "<form action='http://www.sky1005.com/api/doudoupay/callback.php'>";
foreach($_REQUEST as $k => $v){
    $form .= "<input name='$k' value='$v' />";
}
$form .= "</form>";
file_put_contents(dirname(__FILE__).'/log.html', $form."\r\n",FILE_APPEND);

$ReturnArray = array( // 返回字段
    "memberid" => $_REQUEST["memberid"], // 商户ID
    "orderid" => $_REQUEST["orderid"], // 订单号
    "amount" => $_REQUEST["amount"], // 交易金额
    "datetime" => $_REQUEST["datetime"], // 交易时间
    "returncode" => $_REQUEST["returncode"],
    'transaction_id' =>$_REQUEST['transaction_id'],
);

$returnArray = array( // 返回字段
    "memberid" => $list["pay_memberid"], // 商户ID
    "orderid" => $list['out_trade_id'], // 订单号
    'transaction_id'=>$list["pay_orderid"], //支付流水号
    "amount" => $list["pay_amount"], // 交易金额
    "datetime" => date("YmdHis"), // 交易时间
    "returncode" => "00", // 交易状态
);

ksort($ReturnArray);
reset($ReturnArray);
$md5str = "";
foreach ($ReturnArray as $k => $v) {
    $md5str = $md5str . $k . "=" . $v . "&";
}
echo $md5str . "key=" . $key;
$sign = strtoupper(md5($md5str . "key=" . $key));
$ip = get_client_ip();
if ($sign == $_REQUEST["sign"]) {
    
    if ($_REQUEST["returncode"] == "00") {
        // 用户完成支付，此处添加您的逻辑代码
        $info = $database->get(DB_PREFIX.'preorder','*',array('order_id'=>$_REQUEST["orderid"]));
        echo $database->last_query();
        var_dump($info);
        if ( $info ) {
            //检查支付金额
            if ( floatval($info['order_money']) == floatval($_REQUEST["amount"]) ) {
                
                $orderInfo = $database->get(DB_PREFIX . 'order', '*', array(
                    'order_id' => $info['order_id'],
                ));
                
                if(!$orderInfo){
                    $insertArr = array(
                        'order_id' => $info['order_id'],
                        'user_name' => $info['user_name'],
                        'order_money' => $info['order_money'],
                        'order_time' => strtotime($_REQUEST["datetime"]),
                        'order_state' => 1,
                        'state' => 0,
                        'pay_type' => $info['pay_type'],
                        'pay_api' => $info['pay_api'],
                        'pay_order' => $info['order_id'],
                    );
                    
                    $updateArr = array(
                        'notify_ip' => get_client_ip(),
                        'notify_time' => time(),
                    );
                    
                    $database->insert(DB_PREFIX . 'order', $insertArr); //新增订单数据
                    $database->update(DB_PREFIX . 'preorder', $updateArr,array('order_id'=>$_REQUEST["orderid"])); //确认该订单已完成支付
                    
                    
                    #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
                    echo "success";
                    echo "<br />交易成功";
                    echo  "<br />在线支付服务器返回";
                }
                
            } else {
                file_put_contents('./money.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据金额{$_REQUEST["amount"]},数据库存储金额{$info['order_money']}错误\t".serialize($_GET));
            }
            
        } else {
            file_put_contents('./notfound.log', date('Y-m-d H:i:s')."\tIP:".$ip."提交数据订单不存在错误\t".serialize($_GET));
        }
        exit("ok"); // 支付成功后ok必须显示，让我方服务器知道通知成功
    }
} else {
    exit('交易信息被篡改');
}