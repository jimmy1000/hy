<?php 
header("Content-type: text/html; charset=utf-8");
include 'global.php';

if(isset($_GET['action'])){
	
	
	if($_GET['action'] == 'lock'){
		$id = $_GET['id'];
		if(!empty($id)){
		   //
		   $info = $database->get(DB_PREFIX.'order','*',array('id'=>$id));
			if($info['state'] == "5"){
				message('温馨提示',"当前订单已经是锁定状态,无法进行锁定操作",'deal_list.php');
				return;
			}
			if($info['state'] != "0"){
				message('温馨提示',"当前订单已经处理过了,无法进行锁定操作",'deal_list.php');
				return;
			}
		   $database->update(DB_PREFIX."order",array('state'=>5,'lock_id'=>$_SESSION['card_admin']['username']),array('id'=>$id));
		   header("Location:deal_list.php");
		}
	}
	
	if($_GET['action'] == 'unlock'){
		$id = $_GET['id'];
		if(!empty($id)){
		   //
		   $info = $database->get(DB_PREFIX.'order','*',array('id'=>$id));
			if($info['state'] == "5"){
				$database->update(DB_PREFIX."order",array('state'=>0,'lock_id'=>''),array('id'=>$id));
				header("Location:deal_list.php");
			}else{
				message('温馨提示',"当前订单不是锁定状态,无法进行解锁操作",'deal_list.php');
				return;
			}
		   
		}
	}
	
}

if(isset($_GET['act'])){

	$act = $_GET['act'];
	if($act == "set1"){	  
	  $interval = isset($_GET['interval'])?$_GET['interval']:10;
	  $_SESSION['interval'] = $interval;
	  
    }
	
	if($act == "set2"){	  
	  $auto_refresh = isset($_GET['auto_refresh'])?$_GET['auto_refresh']:'1';
	  $_SESSION['auto_refresh'] = $auto_refresh;	  
    }

}
if($_POST['ids']) {
 $tmp = count($_POST['ids']);
 if($tmp <=0) {
	message('温馨提示',"请先选择订单",'deal_list.php');
	return;
 }
 $ids = implode(",",$_POST['ids']);
 //update vc_order set state=5 WHERE id in(1,2,3,4,5)
 $sql="UPDATE vc_order set state=5,lock_id='".$_SESSION['card_admin']['username']."' WHERE id in(".$ids.") and state=0";
 $database->query($sql);
 
 echo "<script>alert('锁定成功，共处理了".$tmp."条订单');location.href='deal_list.php'</script>";
 
 exit;
}

	
?>

<?php include 'base.php';?>

<?php

$sql_order = " ORDER BY id ASC ";
$pageNumber = isset($_GET['pageNo']) ? $_GET['pageNo'] : 1;

$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
if(empty($keywords)){
	$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
}

$order_state = isset($_POST['order_state']) ? $_POST['order_state'] : '';
if($order_state == ''){
	$order_state = isset($_GET['order_state']) ? $_GET['order_state'] : '1';
}

$sql_where = " where state = 0 ";

if( ($order_state != 'all') && ($order_state != '') ){
	$sql_where .= " and order_state = '".$order_state."' ";
}
$pay_type = isset($_POST['pay_type'])?$_POST['pay_type']:$_GET['pay_type'];
if($pay_type !='') {
	$sql_where .= " and pay_type = '".$pay_type."' ";
}

$pay_api = isset($_POST['pay_api'])?$_POST['pay_api']:$_GET['pay_api'];
if($pay_api !='') {
	$sql_where .= " and pay_api = '".$pay_api."' ";
}


if(!empty($keywords)){
	$sql_where .= " and (order_id = '".$keywords."' or user_name = '".$keywords."' ) ";
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
if($pay_type != ''){
	$extraStr .= '&pay_type='.$pay_type;
}
if($pay_api != ''){
	$extraStr .= '&pay_api='.$pay_api;
}
//--------------
if(isset($_SESSION['interval'])){
	$interval = $_SESSION['interval'];
}else{
	$interval = 10;
}

if(empty($interval)){
	$interval = 10;
}

if(isset($_SESSION['auto_refresh'])){
	$auto_refresh = $_SESSION['auto_refresh'];
}else{
	$auto_refresh = '1';
}

$flag = false;
if(isset($_SESSION['totalcount'])){
	$totalcount = $_SESSION['totalcount'];
}else{
	$totalcount = $database->count(DB_PREFIX.'order',array('state'=>0));
}
$count = $database->count(DB_PREFIX.'order',array('state'=>0));
$_SESSION['totalcount'] = $count;
if($count > $totalcount){
	$flag = true;
}

//---------------

function payState($flag){
	switch($flag){
		case 0:
			return '<font color=blue>支付处理中</font>';
		case 1:
			return '<font color=green>成功/OK</font>';
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
		case 'QQWAPPAY':
     	case 'QQWAP':
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


<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">订单管理</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">待处理订单</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
              <div class="btn-toolbar" style="height:30px;">	
					 
                    
                    
					<form action="deal_list.php" method="post" class="form-search pull-right">
                     <select name="pay_type" id="pay_type" class="span" style="width:140px;">
						<option value='' selected>支付方式</option>
						<option value='ALIPAY'   >支付宝</option>
                        <option value='ALIPAYWAP'   >支付宝APP</option>
                        <option value='WECHAT'   >微信</option>
                        <option value='WAP'   >微信APP</option>
                        <option value='TENPAY'   >财付通</option>
					</select>
                    
					     <select name="pay_api" id="pay_api" class="span" style="width:140px;">
                       <option value='' selected  >选择支付接口</option>
						<option value='优付支付' >优付支付</option>
						<option value='聚源支付' >聚源支付</option>
						<option value='启讯支付' >启讯支付</option>
						<option value='101卡支付' >101卡</option>
                        <option value='口袋支付' >口袋支付</option>
					    <option value='华势支付' >华势支付</option>
						<option value='银宝支付' >银宝支付</option>
                        <option value='旺实富支付'>旺实富支付</option>
                        <option value='仁信支付'>仁信支付</option>
                        <option value='多宝支付'>多宝支付</option>
                        <option value='龙卡宝支付'>龙卡宝支付</option>
                        <option value='通宝支付'>通宝支付</option>
                          <option value='云盛支付'>云盛支付</option>
                           <option value='金付卡支付'>金付卡支付</option>
                           <option value='外易付支付'>外易付支付</option>
                           <option value='星和易通'>星和易通</option>
					</select>
 					  <label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;" >订单状态：</label>
					  <select name="order_state" id="order_state" class="search-query" style="width:120px;margin-right:10px;">
						<option value='all' selected>全部状态</option>
						<option value='0'>支付处理中</option>
						<option value='1'>成功/OK</option>
					  </select>
					  
					  <input type="text" name="keywords" id="keywords"  placeholder="用户名、订单编号" class="search-query">
					  <button type="submit" class="btn">搜索</button>
					  
						<label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;" >自动刷新：</label>
					
						<select ID="auto_refresh" name="auto_refresh" style="width:120px;margin-right:10px;">
                        <option Value="2" >关闭</option>
						<option Value="1" selected>开启</option>
						</select>
						
						<label style="height:30px;line-height:30px;display: inline-block;vertical-align: middle;" >刷新间隔：</label>
					
						<select ID="interval" name="interval" style="width:120px;margin-right:10px;">
						<option Value="5" >5秒</option>
						<option Value="10" selected="selected">10秒</option>
                        <option Value="15" >15秒</option>
						<option Value="20" >20秒</option>
						<option Value="30" >30秒</option>
						<option Value="60" >60秒</option>
						</select>&nbsp;&nbsp;
					 
					</form>
				</div>				
				
				
					<table class="table table-hover table-bordered">
					  <thead>
						<tr>
                          <th><input type="checkbox" name="all" id="Checkbox2" onclick="switchAll('ids[]')" /> 全选/反选 </th>
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
						  <th>操作</th>
						</tr>
					  </thead>
                     <form action="deal_list.php" method="post" name='t2' id='t2'  class="form-search pull-right">
					  <tbody>
					  <?php 
					  foreach($datas as $item){						  
					  ?>
					  <tr>
                        <th> <input name="ids[]"  class="ids" type="checkbox" value="<?php echo $item['id'];?>" /></th>
						  <td><?php echo $item['id'];?></td>
						  <td><?php echo $item['order_id'];?></td>
						  
						  <td><?php echo $item['user_name'];?></td>
						  <td><?php echo payType($item['pay_type']);?></td>
						  <td><?php echo $item['order_money'];?></td>
						  <td><?php echo date('Y-m-d H:i:s',$item['order_time']);?></td>
						  
						  <td>
						  <?php if($item['pay_api']=='worth') :?>
						  huashi
						  <?php else:?>
						  <?php echo $item['pay_api'];?>
						  <?php endif;?></td>
						  
						  <td><?php echo payState($item['order_state']);?></td>
						  <td id="father_state<?php echo $item['id'];?>"><?php echo dealState($item['state'],$item['lock_id']);?></td>
						  <td id="father_desc<?php echo $item['id'];?>"><?php echo $item['order_desc'];?></td>
						  <td>
						  <?php 
						  if($item['state'] == "0"){
						  ?>
						  <a title='锁定' onclick="confirmLock('?action=lock&id=<?php echo $item['id'];?>')" href='javascript:;'>锁定</a>
						  <?php
						  }
						  ?>
						  
						  <?php 
						  if($item['state'] == "5"){
						  ?>
						  <a title='解锁' onclick="confirmUnLock('?action=unlock&id=<?php echo $item['id'];?>')" href='javascript:;'>解锁</a>&nbsp;|&nbsp;<a title='编辑' data-toggle="modal" data-target="#myModal" href="order_edit.php?action=edit&id=<?php echo $item['id'];?>">更改状态</a>
						  <?php
						  }
						  ?>						  
						  </td>
					  </tr>
					  <?php
					  }
					  ?>
						<tr><td colspan="12"> <input type="button"  id="d2" name='d2' value='批量锁定' /></td></tr>
						 </form> 
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




<script type="text/javascript">  
  
  
        //复选框选择转换  
        function switchAll(formvalue) {  
            var roomids = document.getElementsByName(formvalue);  
            for (var j = 0; j < roomids.length; j++) {  
                roomids.item(j).checked = !roomids.item(j).checked;  
            }  
        }  
    </script> 


<!-- 弹出model -->
<div id="myModal" class="modal hide fade in" style="display: none; ">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3>处理订单</h3>
</div>
<div class="modal-body"></div>
</div>

<!-- page end -->

<audio id="music_win" src="zhong.mp3" >你的浏览器不支持audio标签。</audio>

<script type="text/javascript">
$(function(){
	//判断
	$("#d2").click(function(){
	 
	   if($("input[name='ids[]']:checked").length <= 0) {
	     alert('请先选择订单');
		 return false;
	   } 
	   $("#t2").submit();
});
	
	
	<?php 
        
		if($order_state!=""){
		     echo '$("#order_state option[value=\''.$order_state.'\']").attr("selected", "selected");';  
	    }
		if($pay_type!=""){
		     echo '$("#pay_type option[value=\''.$pay_type.'\']").attr("selected", "selected");';  
	    }
		if($pay_api!=""){
		     echo '$("#pay_api option[value=\''.$pay_api.'\']").attr("selected", "selected");';  
	    }
        if(!empty($keywords)){
             echo '$("#keywords").val("'.$keywords.'");';
        }
		
		if($flag){
			echo "document.getElementById('music_win').play();";
		}
		
		echo '$("#interval").val("'.$interval.'");';
		
		echo '$("#auto_refresh").val("'.$auto_refresh.'");';
		
    ?>
	
	$("#client-menu").addClass('in');
	
	$('#myModal').on('hide.bs.modal', function () {
		//关闭模态框
	})
	
	<?php 
	if($auto_refresh == '1'){
	?>
		setTimeout(refresh,<?php echo $interval*1000;?>);
		
	<?php
	}
	?>
	
	$('#interval').change(function(){
		var interval = $('#interval').val();
		window.location.href = 'deal_list.php?act=set1&interval='+interval;
	})
		
	$('#auto_refresh').change(function(){
		var auto_refresh = $('#auto_refresh').val();
		window.location.href = 'deal_list.php?act=set2&auto_refresh='+auto_refresh;
	})
		
})

	function refresh(){
		var auto_refresh = $('#auto_refresh').val();
		if(auto_refresh == "1"){
			window.location.href = 'deal_list.php';
		}
	}

</script>

<?php include 'foot.php';?>
