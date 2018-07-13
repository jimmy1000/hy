<?php
// +----------------------------------------------------------------------
// | FileName: daifu_list.php
// +----------------------------------------------------------------------
// | CreateDate: 2017年11月22日
// +----------------------------------------------------------------------
// | Author: xiaoluo
// +----------------------------------------------------------------------
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

$keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';

if(!empty($keywords)){
	$sql_where .= " and sys_user like '%".$keywords."%'  ";
}

$sql_limit = " limit ".($pageNumber-1)*$pageSize.",".$pageSize." ";

$sql = "SELECT * FROM ".DB_PREFIX."xiafa $sql_where $sql_order  $sql_limit ";
$sql_size = " SELECT COUNT(*) AS total FROM ".DB_PREFIX."xiafa a $sql_where ";
$size = $database->query($sql_size)->fetchAll();
$record = $size[0]["total"];
$datas = $database->query($sql)->fetchAll();

?>

<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">代付管理</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">代付列表</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
              <div class="btn-toolbar" style="height:30px;">
					<button onclick="window.location.href='daifu.php'" class="btn btn-primary"><i class="icon-plus">发起代付</i> </button>
					<!-- <form action="sys_user_list.php" method="post" class="form-search pull-right">
					  <input type="text" name="keywords"  placeholder="输入用户名" class="input-xlarge search-query">
					  <button type="submit" class="btn">搜索</button>
					</form> -->
				</div>				
				
					<table class="table table-hover table-bordered">
					  <thead>
						<tr>
						  <th>#编号</th>
						  <th>第三方</th>
						  <th>订单号</th>
						  <th>申请金额</th>
						  <th>申请时间</th>
						  <th>银行名称</th>	
						  <th>下发卡号</th>	
						  <th>户名</th>
						  <th>状态</th>				  
						  <th>操作</th>
						</tr>
					  </thead>
					  <tbody>
					  <?php 
					  foreach($datas as $item){						  
					  ?>
					  <tr>
						  <td><?php echo $item['id'];?></td>
						  <td><?php echo $item['pay_api']?></td>
						  <td><?php echo $item['orderid'];?></td>
						  <td><?php echo $item['money'];?></td>
						  <td><?php echo $item['submit_date']?></td>
						  <td><?php echo $item['bankname']?></td>
						  <td><?php echo $item['bank_account']?></td>
						  <td><?php echo $item['account_name'];?></td>
						  <td><?php 
                        		switch($item['status']){
                                  case '2':
                                    echo "等待确认";
                                    break;
                                  case '1':
                                    echo '下发成功!银行流水号:'.$item['sys_order'];
                                    break;
                                  case '0':
                                    echo '下发失败';
                                    break;
                                }
                            ?>
                        </td>
				  
						  <td>
						  	<a title='编辑' href="daifu_edit.php?action=edit&id=<?php echo $item['orderid'];?>">状态确认</a>
						  </td>
					  </tr>
					  <?php
					  }
					  ?>
						
						
					  </tbody>
					</table>

				<?php 
				echo bootpage($record,$pageSize,$pageNumber,"","");
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
	$("#money-menu").addClass('in');
})
</script>

<?php include 'foot.php';?>
