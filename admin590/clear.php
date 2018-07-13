<?php 
include 'global.php';

$action = 'edit';

if(isset($_GET['act'])){
	
	if($_GET['act'] == 'delete'){
		
		$database->delete(DB_PREFIX.'order');
		
		echo json_encode(array('stat'=>0));
		
		return;
	}
}

?>

<?php include 'base.php';?>

<!-- page start -->

<div class="content">
    <div class="header">
        <h1 class="page-title">系统设置</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">清空订单</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid" style="padding-top:20px;">
		
			<form class="form-horizontal" onsubmit="return false;">			  
			  <div class="control-group">
				<label class="control-label"  for="password">清空密码</label>
				<div class="controls">
				  <input type="text" id="password" value="">&nbsp;&nbsp;【密码是：xymf】
				</div>
			  </div>	  
			  <div class="control-group">
				<div class="controls">
				  <input type="hidden" id="hid_id" name="hid_id" value="1" />
				  <button id="btnSave" class="btn btn-success">确定清空</button>
				</div>
			  </div>
			</form>
			
        </div>
		
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

<script type="text/javascript">
	$(function(){
		$("#btnSave").click(function(){

			var password = $("#password").val();
			if(password == ""){
				alert("清空密码不能为空");
				return;
			}
			
			if(password!="xymf"){
				alert("密码错误");
				return;
			}

			$.post("clear.php?act=delete",{id:$("#hid_id").val()},function(obj){
				if(obj.stat == 0){
					alert('清理成功!');
					window.location.href = 'clear.php';
				}else{
					alert('清理失败!');
				}

			},"json");
			
		})
	})
</script>


<script type="text/javascript">
$(function(){
	$("#legal-menu").addClass('in');
})
</script>


<?php include 'foot.php';?>
