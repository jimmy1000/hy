<?php
include 'global.php';
if($_SESSION['card_admin']['level'] != '1'){
    exit("<script>alert('没有权限!');history.go(-1);</script>");
}

if(isset($_GET['action'])){
    if($_GET['action'] == "save"){
        $sys_order = trim($_POST['sys_order']);
        if($_POST['confirm'] == 1){
            if($sys_order == ''){
                exit("<script>alert('请输入银行流水号!');history.go(-1);</script>");
            }
            $data['sys_order'] = $sys_order;
            $data['status'] = 1;
        }else{
            $data['status'] = $_POST['confirm'];
        }
        $data['confirm'] = $_POST['confirm'];
        $data['confirm_time'] = date('Y-m-d H:i:s');
        $data['confirm_user'] = $_SESSION['card_admin']['username'];
        if($database->update(DB_PREFIX.'xiafa',$data,['orderid'=>$_POST['orderid']])){
            echo json_encode(['stat'=>0,'message'=>'操作成功!']);
        }else{
            echo json_encode(['stat'=>1,'message'=>'操作失败!']);
        }
        exit();
    }
}



$orderid = $_REQUEST['id'];
$rows = $database->get(DB_PREFIX.'xiafa','*',array('orderid'=>$orderid));
if(!$rows){
    exit("<script>alert('查无此订单!');history.go(-1);</script>");
}

?>

<?php include 'base.php';?>

<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">代付管理</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">代付管理</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
              	
			
			<form class="form-horizontal" onsubmit="return false;">
			  <div class="control-group">
				<label class="control-label"  for="lhh">订单号<font color="red">*</font></label>
				<div class="controls">
				  <?php echo $rows['orderid'];?>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="sys_user">第三方</label>
				<div class="controls">
				  <!-- <input type="text" id="sys_user" value=""> -->
				  <?php echo $rows['pay_api'];?>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="money">代付金额<font color="red">*</font></label>
				<div class="controls">
				  <?php echo $rows['money'];?>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="bankname">银行名称<font color="red">*</font></label>
				<div class="controls">
				  <?php echo $rows['bankname'];?>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="bank_account">银行卡号<font color="red">*</font></label>
				<div class="controls">
				  <?php echo $rows['bank_account'];?>
				</div>
			  </div>
			  
			  <div class="control-group">
				<label class="control-label"  for="account_name">户名<font color="red">*</font></label>
				<div class="controls">
				   <?php echo $rows['account_name'];?>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="sys_order">到账状态<font color="red">*</font></label>
				<div class="controls">
				  <label class="radio inline"><input type="radio" name="confirm" <?php if($rows['confirm'] == 1){?> checked="checked" <?php }?> value="1">已到账</label><label class="radio inline"><input type="radio" <?php if($rows['confirm'] == 0){?> checked="checked" <?php }?>  name="confirm" value="0">未到账</label>
				</div>
			  </div>	
			  <div class="control-group">
				<label class="control-label"  for="sys_order">银行流水号<font color="red">*</font></label>
				<div class="controls">
				  <input type="text" id="sys_order" value="<?php echo $rows['sys_order'];?>">
				</div>
			  </div>
			  
			  <div class="control-group">
				<div class="controls">
				  <button id="btnSave" class="btn btn-success">确认到账</button>
				</div>
			  </div>
			   
			</form>
					
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

<!-- page end -->

<script type="text/javascript">
$(function(){
	$("#money-menu").addClass('in');
})
</script>

<script type="text/javascript">
	$(function(){
		$("#btnSave").click(function(){
			var _sys_order = $("#sys_order").val();
			var _confirm = $('input[name=confirm]:checked').val();
			if(_confirm == '1'){
				if(_sys_order == ""){
					alert("银行流水号不能为空!");
					return;
				}
			}
			
			$.post("daifu_edit.php?action=save",{sys_order:_sys_order,confirm:_confirm,orderid:'<?php echo $rows['orderid']?>'},function(obj){
				if(obj.stat == 0){
					alert('提交成功!');
					window.location.href = 'daifu_list.php';
				}else{
					alert('提交失败!'+obj.message);
				}

			},"json");
			
		})
	})
</script>


<?php include 'foot.php';?>
