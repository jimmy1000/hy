<?php 
include 'global.php';

if(isset($_GET['action'])){
	if($_GET['action'] == "delete"){
		
	}
	
}

?>

<?php include 'base.php';?>
	
<?php

$sql_order = " ORDER BY id DESC ";
$pageNumber = isset($_GET['pageNo']) ? $_GET['pageNo'] : 1;
$start_time = isset($_POST['start_time']) ? $_POST['start_time'] : '';
$end_time = isset($_POST['end_time']) ? $_POST['end_time'] : '';

$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
if(empty($keywords)){
	$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
}

$order_state = isset($_POST['order_state']) ? $_POST['order_state'] : '';
if($order_state == ''){
	$order_state = isset($_GET['order_state']) ? $_GET['order_state'] : '';
}

$state = isset($_POST['state']) ? $_POST['state'] : '';
if($state == ''){
	$state = isset($_GET['state']) ? $_GET['state'] : '';
}

$pay_type = isset($_POST['pay_type']) ? $_POST['pay_type'] : '';
if($pay_type == ''){
	$pay_type = isset($_GET['pay_type']) ? $_GET['pay_type'] : '';
}

if(empty($start_time)||empty($end_time)){	
	$start_time = @$_GET["start_time"];
	$end_time = @$_GET["end_time"];
	
	if(!empty($start_time)&&!empty($end_time)){
		$start_time = date('Y-m-d H:i:s',$start_time);
		$end_time = date('Y-m-d H:i:s',$end_time);
	}
}

$sql_where = " where 1 = 1 ";

if( ($order_state != 'all') && ($order_state != '') ){
	$sql_where .= " and order_state = '".$order_state."' ";
}
if( ($state != 'all') && ($state != '') ){
	$sql_where .= " and state = '".$state."' ";
}
if( ($pay_type != 'all') && ($pay_type != '') ){
	$sql_where .= " and pay_type = '".$pay_type."' ";
}

if(!empty($start_time)&&!empty($end_time)){
	$sql_where .= " and order_time >= '".strtotime($start_time)."' and order_time < '".strtotime($end_time)."' ";
}

if(!empty($keywords)){
	$sql_where .= " and (order_id = '".$keywords."' or user_name = '".$keywords."'  or lock_id = '".$keywords."' or order_desc like '%".$keywords."%') ";
}

$sql_limit = " limit ".($pageNumber-1)*$pageSize.",".$pageSize." ";

$sql = " select * from ".DB_PREFIX."order $sql_where $sql_order $sql_limit  ";
$sql_size = " select count(*) as total from ".DB_PREFIX."order $sql_where "; 
$sql_x = " SELECT sum(order_money) AS total FROM ".DB_PREFIX."order $sql_where ";

$size = $database->query($sql_size)->fetchAll();
$record = $size[0]["total"]; 

$x = $database->query($sql_x)->fetchAll();
$xiaofei = ($x[0]["total"]>0)?$x[0]["total"]:0;

$datas = $database->query($sql)->fetchAll();

$tongji = '<li><a href="javascript:;">总金额：'.$xiaofei.'</a></li>';

$extraStr = '';
if(!empty($keywords)){
	$extraStr .= '&keywords='.$keywords;
}
if($order_state != ''){
	$extraStr .= '&order_state='.$order_state;
}
if($state != ''){
	$extraStr .= '&state='.$state;
}
if($pay_type != ''){
	$extraStr .= '&pay_type='.$pay_type;
}

if(!empty($start_time)&&!empty($end_time)){
	$extraStr .= '&start_time='.strtotime($start_time).'&end_time='.strtotime($end_time);
}

function payState($flag){
	switch($flag){
		case 0:
			return '<font color=blue>支付处理中</font>';
		case 1:
			return '<font color=green>支付成功</font>';
	}
}

function dealState($flag,$user){
	switch($flag){
		case 0:
			return '<font>待处理</font>';
		case 1:
			return '<font color=green>已确认</font>';
		case 2:
			return '<font color=red>已取消</font>';
		case 3:
			return '<font color=brown>待确认</font>';
		case 5:
			return '<font color=blue>已锁定('.$user.')</font>';
	}
}

function payType($flag){
	switch($flag){
		case 'WECHAT':
			return '<font color=green>微信</font>';
        	break;
		case 'ALIPAY':
			return '<font color=red>支付宝</font>';
        	break;
		case 'ALIPAYWAP':
			return '<font color=blue>支付宝APP</font>';
        	break;
		case 'WAP':
			return '<font color=green>微信APP</font>';
        	break;
		case 'BANK':
			return '<font color=blue>网银</font>';
        	break;
		case 'QQPAY':
			return '<font color=blue>QQ扫码支付</font>';
        	break;
        case 'QQPAYWAP':
        case 'QQWAP':
		case 'QQWAPPAY':
			return '<font color=blue>QQ手机支付</font>';
        	break;
        case 'TENPAY':
			return '<font color=blue>财付通</font>';
        	break;
        case 'JDPAY':
			return '<font color=blue>京东支付</font>';
        	break;
      case 'DIANKAPAY' :
        	return '<font color=blue>点卡支付</font>';
        	break;
      case 'BANKWAP':
			return '<font color=blue>手机网银</font>';
        	break;
      case 'JDPAYWAP':
			return '<font color=blue>京东APP</font>';
        	break;
      case 'BANKSCAN':
			return '<font color=blue>网银扫码</font>';
        	break;
    case 'ALIPAYCODE':
        return '<font color=blue>支付宝付款码</font>';
        break;
    case 'WEIXINCODE':
        return '<font color=blue>微信付款码</font>';
        break;

	}
}
?>
<script type="text/javascript" src="lib/datepicker/WdatePicker.js"></script>

<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">订单管理</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">订单记录</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
              <div class="btn-toolbar" style="height:30px;">	
					
					<form action="list_mgr.php" method="post" class="form-search pull-right">
					
					  <label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;">时间范围：</label>
					  <input id="start_time" name="start_time" type="text" class='Wdate' style='width:150px;margin-right:10px;' value="" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:'#F{$dp.$D(\'end_time\')}'})"></input>
					  <label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;">到：</label>
					  <input id="end_time" name="end_time" type="text" class='Wdate' style='width:150px;margin-right:10px;' value="" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'start_time\')}'})"></input>					  
					  
					  <label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;" >支付方式：</label>
					
					  <select name="pay_type" id="pay_type" class="search-query" style="width:120px;margin-right:10px;">
						<option value='all' selected>全部</option>
						<option value='ALIPAY'>支付宝</option>
						<option value='ALIPAYWAP'>支付宝APP</option>
						<option value='WECHAT'>微信</option>
						<option value='WAP'>微信APP</option>
						<option value='BANK'>网银</option>
					  </select>
					  
					  <label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;" >订单状态：</label>
					
					  <select name="order_state" id="order_state" class="search-query" style="width:120px;margin-right:10px;">
						<option value='all' selected>全部状态</option>
						<option value='0'>支付处理中</option>
						<option value='1'>支付成功</option>
					  </select>
					  
					  <label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;" >处理状态：</label>
					  
					  <select name="state" id="state" class="search-query" style="width:120px;margin-right:10px;">
						<option value='all' selected>全部状态</option>
						<option value='0'>待处理</option>
						<option value='5'>已锁定</option>
						<option value='1'>已确认</option>
						<option value='2'>已取消</option>
						<option value='3'>待确认</option>
					  </select>
					  
					  <input type="text" style="width:300px" name="keywords" id="keywords"  placeholder="用户名、订单号、操作员、备注" class="search-query">
					  <button type="submit" class="btn">搜索</button>
					  
					  <a href="export.php?<?php echo $extraStr;?>" target='_blank' class="btn btn-primary">导出Excel</a>
					 
					</form>
				</div>				
				
				
					<table class="table table-hover table-bordered">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>订单编号</th>
						  <th>用户名</th>
						  <th>支付方式</th>
						  <th>订单金额</th>
						  <th>订单时间</th>
						 
						  <th>订单来源</th>
						 
						  <th>订单状态</th>
						  <th>处理状态</th>
						  <th>备注</th>
						  <th>操作员</th>
						  <th>操作</th>
						</tr>
					  </thead>
					  <tbody>
					  <?php 
					  foreach($datas as $item){						  
					  ?>
					  <tr>
						  <td><?php echo $item['id'];?></td>
						  <td><?php echo $item['order_id'];?></td>
						  <td><?php echo $item['user_name'];?></td>
						  <td><?php echo payType($item['pay_type']);?></td>
						  <td><?php echo $item['order_money'];?></td>
						  <td><?php echo date('Y-m-d H:i:s',$item['order_time']);?></td>
						 
						  <td><?php echo $item['pay_api'];?></td>
						  
						  <td><?php echo payState($item['order_state']);?></td>
						  <td id="father_state<?php echo $item['id'];?>"><?php echo dealState($item['state'],$item['lock_id']);?></td>
						  <td id="father_desc<?php echo $item['id'];?>"><?php echo $item['order_desc'];?></td>
						  <td><?php echo $item['lock_id'];?></td>
						  <td>
						  
						  <a title='编辑' data-toggle="modal" data-target="#myModal" href="order_edit.php?action=edit&id=<?php echo $item['id'];?>">更改状态</a>
						  
						  </tr>
					  <?php
					  }
					  ?>
						
						
					  </tbody>
					</table>
				
				<?php 
				echo bootpage($record,$pageSize,$pageNumber,"",$extraStr,$tongji);
				?>
            <footer>
                <hr>
                <p class="pull-right">
                    <a href="javascript:;">
                        <?php echo $appSet[ 'app_name'];?>
                    </a>
                </p>
                <p>&copy;
                    <?php echo $appSet[ 'company_year'];?>
                    <a href="<?php echo $appSet['company_url'];?>" title="<?php echo $appSet['company'];?>" target="_blank">
                        <?php echo $appSet[ 'company'];?>
                    </a>
                </p>
            </footer>
        </div>
    </div>
</div>

<!-- 弹出model -->
<div id="myModal" class="modal hide fade in" style="display: none; ">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3>处理订单</h3>
</div>
<div class="modal-body"></div>
</div>

<!-- page end -->

<script type="text/javascript">
$(function(){
	
	<?php 
        if($state!=""){
             echo '$("#state option[value=\''.$state.'\']").attr("selected", "selected");';          
        }
		if($pay_type!=""){
             echo '$("#pay_type option[value=\''.$pay_type.'\']").attr("selected", "selected");';          
        }
		if($order_state!=""){
		     echo '$("#order_state option[value=\''.$order_state.'\']").attr("selected", "selected");';  
	    }
        if(!empty($keywords)){
             echo '$("#keywords").val("'.$keywords.'");';
        }
		if(!empty($start_time)&&!empty($end_time)){
             echo '$("#start_time").val("'.$start_time.'");';
		     echo '$("#end_time").val("'.$end_time.'");';
        }
    ?>
	
	$("#client-menu").addClass('in');
	
	$('#myModal').on('hide.bs.modal', function () {
		//关闭模态框
	})
	
})
</script>

<?php include 'foot.php';?>
