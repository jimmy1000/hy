<?php 
include 'global.php';

$user = $_SESSION['card_admin']['username'];

$action = isset($_GET['action']) ? $_GET['action'] : 'add';

if(isset($_GET['act'])){
	if($_GET['act'] == 'edit'){
		$id = @$_POST['id'];
		
		$info = $database->get(DB_PREFIX.'order','*',array('id'=>$id));
		
		if($info['state'] != "3"){
		
			if($info['state'] != "5"){
				echo json_encode(array('stat'=>1));
				return;
			}
			if($user != $info['lock_id']){
				echo json_encode(array('stat'=>2));
				return;
			}
		
		}
		
		$database->update(DB_PREFIX.'order',array('order_desc'=>@$_POST['order_desc'],'state'=>@$_POST['state']),array('id'=>$id));
		echo json_encode(array('stat'=>0));
		return;
	}
	
}

?>


<!-- page start -->

<?php 
if($action == 'edit'){
	$id = $_GET['id'];
	$info = $database->get(DB_PREFIX.'order','*',array('id'=>$id));
?>


    <div class="container-fluid">
        <div class="row-fluid">
		
			<form class="form-horizontal" onsubmit="return false;">
			  <div class="control-group">
				<label class="control-label"  for="order_id">订单编号</label>
				<div class="controls">
				  <input type="text" id="order_id" readonly value="<?php echo $info['order_id'];?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="user_name">会员帐号</label>
				<div class="controls">
				  <input type="text" id="user_name" readonly value="<?php echo $info['user_name'];?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="order_money">订单金额</label>
				<div class="controls">
				  <input type="text" id="order_money" readonly value="<?php echo $info['order_money'];?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" readonly for="order_state">订单状态</label>
				<div class="controls">
				  <span><?php if($info['order_state']==0){echo '<font color=blue>支付处理中</font>';};?> <?php if($info['order_state']==1){echo '<font color=red>成功/success</font>';};?></span>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" readonly for="ipt_state">处理状态</label>
				<div class="controls">
				    <select name="ipt_state" id="ipt_state" class="span" style="width:220px;">
						<option value='1'>已确认</option>
						<option value='3'>待确认</option>
						<option value='2'>已取消</option>
					</select>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" readonly for="order_desc">备注说明</label>
				<div class="controls">
				  <input type="text" id="order_desc" name="order_desc" value="">
				</div>
			  </div>
			  <div class="control-group">
				<div class="controls">
				  <input type="hidden" id="hid_id" name="hid_id" value="<?php echo $info['id'];?>" />
				  <button type="submit" id="btnSave" class="btn btn-success">提交修改</button>
				</div>
			  </div>
			</form>
			
        </div>
    </div>


<script type="text/javascript">
	$(function(){
		$("#btnSave").click(function(){
			
			$('#father_desc<?php echo $info['id'];?>').html($('#order_desc').val());
			
			var type = $('#ipt_state').val();
			var html = $('#ipt_state').find("option:selected").text();
			
			switch(type){
				case '1':
					html = '<font color=green>'+html+'</font>';
					break;
				case '2':
					html = '<font color=red>'+html+'</font>';
					break;
				case '3':
					html = '<font color=brown>'+html+'</font>';
					break;
			}

			$.post("order_edit.php?act=edit",{id:$("#hid_id").val(),order_desc:$('#order_desc').val(),state:$('#ipt_state').val()},function(obj){
				if(obj.stat == 0){
					//
					alert('操作成功');
					$('#father_state<?php echo $info['id'];?>').html(html);
					$('#myModal').modal('hide');
				}
				if(obj.stat == 1){
					//
					alert('当前订单未锁定,无法进行操作!');
					$('#myModal').modal('hide');
				}
				if(obj.stat == 2){
					//
					alert('当前订单不是您的锁定订单,无法进行操作!');
					$('#myModal').modal('hide');
				}
			},"json");
			
		})
	})
</script>

<?php 
}
?>
