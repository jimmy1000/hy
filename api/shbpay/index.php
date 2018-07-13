<?php
if($_REQUEST['paytype'] == 'alipay_scan' || $_REQUEST['paytype'] == 'weixin_scan'){
	$gateway = 'scan_pay.php';
}else{
	$gateway = 'bank_pay.php';
}
?>
<form action="/<?=$gateway?>" method="post" id="payform" name="payform" >
<?php
foreach ($_REQUEST as $k=>$v){ ?>
<input type='hidden' name='<?=$k?>' value='<?=$v?>' />
<?php
}
?>
</form>

<script type="text/javascript">
document.payform.submit();
</script>