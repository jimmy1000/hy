<?php
require_once '../../pay_mgr/init.php';
$OrderId = $_REQUEST['OrderId'];
//var_dump($OrderId);
$info = $database->get(DB_PREFIX.'order','*',array('order_id'=>$OrderId));
//echo $database->last_query();
if(!$info){
    $return = array('status' => 0);
}else{
    $return =  array('status'  => 1);
}
echo json_encode($return);