<?php
require_once '../../pay_mgr/init.php';
$OrderId = $_REQUEST['OrderId'];
$info = $database->get(DB_PREFIX.'order','*',array('pay_order'=>$OrderId));
if(!$info){
    $return = array('status' => 0);
}else{
   $return =  array('status'  => 1);
}
echo json_encode($return);