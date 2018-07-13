<?php
include 'global.php';

include ExcelLib_PATH.'PHPExcel/PHPExcel.php';
include ExcelLib_PATH.'PHPExcel/PHPExcel/IOFactory.php';

$file_date = date('Ymd');

if(!isset($_SESSION['card_admin'])){
	echo '<script>window.location.href="login.php";</script>';
	return;
}



function payState($flag){
	switch($flag){
		case 0:
			return '支付处理中';
		case 1:
			return '支付成功';
	}
}

function dealState($flag){
	switch($flag){
		case 0:
			return '待处理';
		case 1:
			return '已确认';
		case 2:
			return '已取消';
		case 3:
			return '待确认';
		case 5:
			return '已锁定';
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
	  /* div垂直居中样式 */
		#log{position:fixed;margin:auto;left:0; right:0; top:0; bottom:0;width:600px; height:200px;background:#F2FDFF;line-height:200px;text-align:center;font-size:18px;font-weight:bold;font-family:Arial;}
	</style>	
</head>
<body>
     <div id="log">
     <?php
     if(@$_GET['p']=='' || @$_GET['p']==null){
     //p 就是代表生成的序号从哪里开始，类似分页一样的
     echo '正在生成 500 条... 起始位置 0';
    }else{
    	 echo '正在生成 500 条...  起始位置 '.$_GET['p'];
    } ?>	
     </div>
<?php
/* --start 
	首次判断，p等于空的时候，如果存在xls文件，就删除，重新创建新的，
	主要作用是可以多次，反复执行这个程序。	*/

if(@$_GET['p']=='' || @$_GET['p']==null){
    if(file_exists("./export/".$file_date.".xls")){
        unlink("./export/".$file_date.".xls");
    }
    file_put_contents("./export/".$file_date.".xls","");
}
/* --end  */

$objReader = PHPExcel_IOFactory::createReader('Excel5');/*Excel5 for 2003 excel2007 for 2007*/
// 这个 $objReader 好像没用到，我没深究。
if(@$_GET['s']=="1"){
	$objPHPExcel = PHPExcel_IOFactory::load("./export/".$file_date.".xls"); //读取这个xls文件
}else{
	$objPHPExcel = PHPExcel_IOFactory::load("./export/template.xls"); //读取这个xls文件
}
$objPHPExcel->setActiveSheetIndex(0); //设置第一张表为当前活动表
$objPHPExcel->getActiveSheet()->freezePane('A2'); //设置第一行固定，不随滚动条滚动

/*  start
		设置PHPExcel的样式，$sharedStyle1用于第一行的标题,这个颜色是绿色
		*/
$sharedStyle1 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
    array('fill' => array(
                'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'	=> array('argb' => 'FFCCFFCC')
            ),
          'borders' => array(
                'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
                'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
                )
         ));
/* end */

/*  start
		设置PHPExcel的样式，$sharedStyle2用于单元行的设置
		*/
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle2->applyFromArray(
    array('fill' => array(
                'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'	=> array('argb' => 'fff4f4f4')
            )
         ));
/* end */

if(@$_GET['p']=='' || @$_GET['p']==null){

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
     
    $objPHPExcel->getActiveSheet()->setTitle('All Lottery Info');

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1,'编号');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1,'订单编号');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1,'千网订单号');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1,'用户名');
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1,'订单金额');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1,'订单时间');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1,'订单状态');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1,'处理状态');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1,'备注');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1,'');
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1,'');
}
                        
    $sheet = $objPHPExcel->getSheet(0);  
    $AllRow = $sheet->getHighestRow();

$sql_where = " WHERE 1 = 1 ";
$sql_order = " ORDER BY id DESC ";	
	
$start_time = @$_GET["start_time"];
$end_time = @$_GET["end_time"];
$state = isset($_GET['state']) ? $_GET['state'] : '';
$order_state = isset($_GET['order_state']) ? $_GET['order_state'] : '';

$_url = '';
$sql_limit = " limit ".(empty($_GET['p'])?0:$_GET['p']).",500 ";

if( ($order_state != 'all') && ($order_state != '') ){
	$sql_where .= " and order_state = '".$order_state."' ";
	$url .= '&order_state='.$order_state;
}
if( ($state != 'all') && ($state != '') ){
	$sql_where .= " and state = '".$state."' ";
	$url .= '&state='.$state;
}

if(!empty($start_time)&&!empty($end_time)){
	$sql_where .= " and order_time >= '".$start_time."' and order_time < '".$end_time."' ";
	$_url .=  '&start_time='.$start_time.'&end_time='.$end_time;
}


$sql = "SELECT * FROM ".DB_PREFIX."order  $sql_where $sql_order  $sql_limit ";

$sql_size = " SELECT COUNT(*) AS total FROM ".DB_PREFIX."order $sql_where ";

$size = $database->query($sql_size)->fetchAll();

$numall = $size[0]["total"];

if($numall == 0){
	echo '<script>document.getElementById("log").innerHTML="没有可以导出的数据";</script>'; //循环操作
	exit;
}

	$datas = $database->query($sql)->fetchAll();

	$num = $AllRow; //得到当前excel里的总行数

	if(($num > $numall) && $num != 1 ){
		//判断，当excel里的总行数大于等于数据表里的总行数时，出现下载地址，并退出程序
		echo '<script>document.getElementById("log").innerHTML="已经导出完成。<br>点击 <a href=\"./export/'.$file_date.'.xls\">这里</a> 下载;"</script>';
		exit;
	}

	for($m = 0; $m < count($datas); $m ++){

		$arr = $datas[$m];
		
		$num++; //从总行数的下一行开始操作

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $num,$arr['id']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $num,$arr['order_id']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num,$arr['pay_order'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $num,$arr['pay_order']);
		//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $num,$arr['user_name']);
		$objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num,$arr['user_name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $num,$arr['order_money']);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $num,date('Y-m-d H:i:s',$arr['order_time']));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $num,payState($arr['order_state']));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $num,dealState($arr['state']));
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $num,$arr['order_desc']);


	}
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save ("./export/".$file_date.".xls"); //数据保存到到excel中
	
	if($numall <= 500){
		echo '<script>document.getElementById("log").innerHTML="已经导出完成。<br>点击 <a href=\"./export/'.$file_date.'.xls\">这里</a> 下载;"</script>';
		exit;
	}
	
    echo '<script>window.location.href="./export.php?s=1&p='.($num-1).$_url.'";</script>'; //循环操作
?>
  </body>
</html>