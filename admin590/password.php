<?php 
include 'global.php';


if(isset($_GET['action'])){
	if($_GET['action'] == 'edit'){
		$current = $_SESSION['card_admin']['username'];
		$count = $database->count(DB_PREFIX.'sys',array('AND'=>array('sys_user'=>$current,'sys_password'=>@$_POST["old_pass"])));
		if($count > 0){
			 $database->update(DB_PREFIX.'sys',array('sys_password'=>@$_POST["new_pass"]),array('sys_user'=>$current));
			 echo '{"stat":0}';
			return;
		}else{
			echo '{"stat":1}';
			return;
		}
	}
}

?>

<?php include 'base.php';?>

<!-- page start -->


<div class="content">
    <div class="header">
        <h1 class="page-title">修改密码</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        </li>
        <li class="active">修改密码</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
            <div id="tipSuc"></div>
            
            <div class="well">

                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="home">
                       
                            <label>原始密码</label>
							<input type="password" id="old_pass" class="input-xlarge">
							<label>新密码</label>
							<input type="password" id="new_pass" class="input-xlarge">
							<label>新密码确认</label>
							<input type="password" id="new_pass1" class="input-xlarge">
							<div>
								<button id="btnPass" data-loading-text="保存中..." class="btn btn-primary">修改密码</button>
							</div>
                        
                    </div>
                    
                </div>
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
</div>

<script type="text/javascript">
	$(function(){
		$("#btnPass").click(function(){
			$("#tipSuc").html('');
			if($("#old_pass").val() == ""){
				alert("原始密码不能为空!")
				return false;
			}

			if($("#new_pass").val() == "" || $("#new_pass1").val() =="" ){
				alert("新密码不能为空!");
				return false;
			}
			if($("#new_pass").val().length < 6 || $("#new_pass").val().length > 16){
				alert("密码长度为6-16");
				return false;
			}

			if($("#new_pass").val() != $("#new_pass1").val()){
				alert("两次密码输入不一致!");
				return false;
			}

			if($("#new_pass").val() == $("#old_pass").val()){
				alert("新密码和原始密码一样!");
				return false;
			}
			$(this).button('loading').delay(1000);
			$.post("password.php?action=edit",{old_pass:$("#old_pass").val(),new_pass:$("#new_pass").val()},function(obj){
				if(obj.stat == 0){
					var sHtml = '<div class="alert alert-success "><button type="button" class="close" data-dismiss="alert">×</button>密码修改成功</div>';
					$("#tipSuc").html(sHtml);
					$("#old_pass").val('');
					$("#new_pass").val('');
					$("#new_pass1").val('');
				}else{
					var sHtml = '<div class="alert alert-error "><button type="button" class="close" data-dismiss="alert">×</button>原始密码错误</div>';
					$("#tipSuc").html(sHtml);
				}
				$("#btnPass").button('reset');
			},"json");
			
		});
	})
</script>

<!-- page end -->

<script type="text/javascript">
$(function(){
	$("#legal-menu").addClass('in');
})
</script>

<?php include 'foot.php';?>
