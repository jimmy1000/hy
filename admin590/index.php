<?php 
include 'global.php';
?>

<?php include 'base.php';?>

<?php 

$sql_size = " SELECT count(*) AS total FROM ".DB_PREFIX."order ";
$size = $database->query($sql_size)->fetchAll();
$totalNum = $size[0]["total"];

$sql_size = " SELECT COUNT(*) AS total FROM ".DB_PREFIX."order  where  datediff(FROM_UNIXTIME(order_time,'%Y-%m-%d'),now()) = 0  ";
$size = $database->query($sql_size)->fetchAll();
$todayNum = $size[0]["total"];


$sql_size = " SELECT sum(order_money) AS total FROM ".DB_PREFIX."order ";
$size = $database->query($sql_size)->fetchAll();
$totalMoney = $size[0]["total"] >0 ? $size[0]["total"] :  0;

$sql_size = " SELECT sum(order_money) AS total FROM ".DB_PREFIX."order  where datediff(FROM_UNIXTIME(order_time,'%Y-%m-%d'),now()) = 0 ";
$size = $database->query($sql_size)->fetchAll();
$todayMoney = $size[0]["total"] > 0 ? $size[0]["total"] : 0;


?>

<!-- page start -->
<div class="content">
    <div class="header">        
        <h1 class="page-title">控制面板</h1>
    </div>
    <ul class="breadcrumb">
        <li><a href="index.php">主页</a>  <span class="divider">/</span>
        </li>
        <li class="active">控制面板</li>
    </ul>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="row-fluid">
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>&nbsp;提&nbsp;醒&nbsp;：</strong> 在统计管理中，可以看到更详细的数据统计
				</div>
                <div class="block"> <a href="#page-stats" class="block-heading" data-toggle="collapse">统计信息</a>
                    <div id="page-stats" class="block-body collapse in">
                        <div class="stat-widget-container">
                            <div class="stat-widget">
                                <div class="stat-button">
                                    <p class="title"><?php echo $todayNum;?></p>
                                    <p class="detail">今日订单</p>
                                </div>
                            </div>
							<div class="stat-widget">
                                <div class="stat-button">
                                    <p class="title">￥<?php echo $todayMoney;?></p>
                                    <p class="detail">今日订单总金额</p>
                                </div>
                            </div>

                            <div class="stat-widget">
                                <div class="stat-button">
                                    <p class="title"><?php echo $totalNum;?></p>
                                    <p class="detail">总订单</p>
                                </div>
                            </div>
                            <div class="stat-widget">
                                <div class="stat-button">
                                    <p class="title">￥<?php echo $totalMoney;?></p>
                                    <p class="detail">总订单金额</p>
                                </div>
                            </div>
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

<!-- page end -->

<?php include 'foot.php';?>
