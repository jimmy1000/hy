<?php 
include 'global.php';

if(isset($_GET['action'])){
	if($_GET['action'] == "delete"){
		$id = $_GET['id'];
		if(!empty($id)){
		   //
		}
	}

}

?>

<?php include 'base.php';?>

<?php

$sql_order = " ORDER BY id DESC ";
$pageNumber = isset($_GET['pageNo']) ? $_GET['pageNo'] : 1;


$sql_where = " where 1 = 1 ";

$sql_limit = " limit ".($pageNumber-1)*$pageSize.",".$pageSize." ";

$sql = " select * from ".DB_PREFIX."log $sql_where $sql_order $sql_limit  ";
$sql_size = " select count(*) as total from ".DB_PREFIX."log $sql_where "; 

$size = $database->query($sql_size)->fetchAll();
$record = $size[0]["total"];

$datas = $database->query($sql)->fetchAll();

$tongji = '';

?>
<script type="text/javascript" src="lib/datepicker/WdatePicker.js"></script>

<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">系统管理</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">登录日志</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
				
					<table class="table table-hover table-bordered">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>用户名</th>
						  <th>登录时间</th>
						  <th>登录IP</th>
						</tr>
					  </thead>
					  <tbody>
					  <?php 
					  foreach($datas as $item){						  
					  ?>
					  <tr>
						  <td><?php echo $item['id'];?></td>
						  <td><?php echo $item['user_name'];?></td>
						  <td><?php echo $item['login_time'];?></td>
						  <td><?php echo $item['login_ip'];?></td>
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

<!-- page end -->

<script type="text/javascript">
$(function(){
	
	$("#legal-menu").addClass('in');
	
	$('#myModal').on('hide.bs.modal', function () {
		//关闭模态框
	})
	
})
</script>

<?php include 'foot.php';?>
