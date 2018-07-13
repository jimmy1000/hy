<?php 
include 'global.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'add';

if(isset($_GET['act'])){
	if($_GET['act'] == 'edit'){
		$id = @$_POST['id'];
		
		$info = $database->get(DB_PREFIX.'sys','*',array('id'=>$id));
		if($info['sys_level'] == 1){
			echo json_encode(array('stat'=>1));
			return;
		}
		
		$upArr = array('nick_name'=>@$_POST['nick_name'],'sys_password'=>@$_POST['sys_password']);
		
		$database->update(DB_PREFIX.'sys',$upArr,array('id'=>$id));
		
		echo json_encode(array('stat'=>0));
		
		return;
	}
	
	if($_GET['act'] == 'add'){
	
		$username = trim(@$_POST['sys_user']);
	
		$isExist = $database->get(DB_PREFIX.'sys','*',array('sys_user'=>$username));
		if($isExist){
			echo json_encode(array('stat'=>1));
			return ;
		}
	
		$database->insert(DB_PREFIX.'sys',array('sys_level'=>2,'sys_user'=>$username,'sys_password'=>@$_POST['sys_password'],'nick_name'=>@$_POST['nick_name'],'last_login'=>date('Y-m-d H:i:s')));
		
		echo json_encode(array('stat'=>0));
		return;
	}
}

?>

<?php include 'base.php';?>

<!-- page start -->


<?php 
if($action == 'add'){
?>
<div class="content">
    <div class="header">
        <h1 class="page-title">添加操作员</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li><a href="sys_user.php">用户管理</a>  <span class="divider">/</span>
        </li>
        <li class="active">添加操作员</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid" style="padding-top:20px;">
		
			<form class="form-horizontal" onsubmit="return false;">
			  <div class="control-group">
				<label class="control-label"  for="sys_user">用户名</label>
				<div class="controls">
				  <input type="text" id="sys_user" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="sys_password">用户密码</label>
				<div class="controls">
				  <input type="text" id="sys_password" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="r_password">确认密码</label>
				<div class="controls">
				  <input type="text" id="r_password" value="">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="nick_name">用户昵称</label>
				<div class="controls">
				  <input type="text" id="nick_name" value="">
				</div>
			  </div>			  
			  <div class="control-group">
				<div class="controls">
				  <button id="btnSave" class="btn btn-success">确定添加</button>
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
			
			var _sys_user = $("#sys_user").val();
			if(_sys_user == ""){
				alert("用户帐号不能为空");
				return;
			}

			var _sys_password = $("#sys_password").val();
			if(_sys_password == ""){
				alert("用户密码不能为空");
				return;
			}
			
			var r_password = $("#r_password").val();
			if(r_password!=_sys_password){
				alert("两次输入密码不一致");
				return;
			}
			
			var _nick_name = $("#nick_name").val();
			if(_nick_name == ""){
				alert("用户昵称不能为空");
				return;
			}

			$.post("sys_edit.php?act=add",{sys_user:_sys_user,sys_password:_sys_password,nick_name:_nick_name},function(obj){
				if(obj.stat == 0){
					alert('添加成功!');
					window.location.href = 'sys_user.php';
				}else{
					alert('用户名已存在!');
				}

			},"json");
			
		})
	})
</script>


<?php 
}	
if($action == 'edit'){
	$id = $_GET['id'];
	$info = $database->get(DB_PREFIX.'sys','*',array('id'=>$id));
	if($info['sys_level'] == 1){
		exit;
	}
?>
<div class="content">
    <div class="header">
        <h1 class="page-title">修改操作员信息</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li><a href="sys_user.php">用户管理</a>  <span class="divider">/</span>
        </li>
        <li class="active">修改信息</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid" style="padding-top:20px;">
		
			<form class="form-horizontal" onsubmit="return false;">
			  <div class="control-group">
				<label class="control-label"  for="sys_user">用户名</label>
				<div class="controls">
				  <input type="text" id="sys_user" readonly value="<?php echo $info['sys_user'];?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="sys_password">用户密码</label>
				<div class="controls">
				  <input type="text" id="sys_password" value="<?php echo $info['sys_password'];?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="r_password">确认密码</label>
				<div class="controls">
				  <input type="text" id="r_password" value="<?php echo $info['sys_password'];?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label"  for="nick_name">用户昵称</label>
				<div class="controls">
				  <input type="text" id="nick_name" value="<?php echo $info['nick_name'];?>">
				</div>
			  </div>			  
			  <div class="control-group">
				<div class="controls">
				  <input type="hidden" id="hid_id" name="hid_id" value="<?php echo $info['id'];?>" />
				  <button id="btnSave" class="btn btn-success">提交修改</button>
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

			var _sys_password = $("#sys_password").val();
			if(_sys_password == ""){
				alert("用户密码不能为空");
				return;
			}
			
			var r_password = $("#r_password").val();
			if(r_password!=_sys_password){
				alert("两次输入密码不一致");
				return;
			}
			
			var _nick_name = $("#nick_name").val();
			if(_nick_name == ""){
				alert("用户昵称不能为空");
				return;
			}

			$.post("sys_edit.php?act=edit",{id:$("#hid_id").val(),sys_password:_sys_password,nick_name:_nick_name},function(obj){
				if(obj.stat == 0){
					alert('修改成功!');
					window.location.href = 'sys_user.php';
				}else{
					alert('修改失败!');
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

<?php 
}
?>

<?php include 'foot.php';?>
