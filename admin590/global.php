<?php
include 'init.php';
 
$appSet['company']  = '支付平台';
$appSet['company_year'] = date('Y');
$appSet['company_url'] = '#';
$appSet['app_name'] = '订单管理系统';

$sysSet = $database->get(DB_PREFIX.'set','*',array('id'=>1));

$pageSize = ($sysSet['page_size'] > 0) ? $sysSet['page_size'] : 10;

if(isset($_GET['go'])){
	if($_GET['go']=='logout'){
		$_SESSION['card_admin'] = null;
		unset($_SESSION['card_admin']);
		echo '<script>window.location.href="login.php";</script>';
		return;
	}
}
?>