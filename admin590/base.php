<?php 

if(!isset($_SESSION['card_admin'])){
	echo '<script>window.location.href="login.php";</script>';
	return;
}

$user = $_SESSION['card_admin']['username'];
$level = $_SESSION['card_admin']['level'];
$nickname = $_SESSION['card_admin']['nickname'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $appSet['app_name'];?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="stylesheets/theme.css">
    <link rel="stylesheet" href="lib/font-awesome/css/font-awesome.css">
    <script src="lib/jquery-1.7.2.min.js" type="text/javascript"></script>
	<script src="lib/bootstrap/js/bootstrap.js"></script>
    <!-- Demo page code -->
    <style type="text/css">
        .brand { font-family: georgia, serif; }
        .brand .first {
            color: #ccc;
            font-style: italic;
        }
        .brand .second {
            color: #fff;
            font-weight: bold;
        }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="lib/html5.js"></script>
    <![endif]-->

  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!--> 
  <body class=""> 
  <!--<![endif]-->
    
    <div class="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">
                    <li><a href="index.php" class="hidden-phone visible-tablet visible-desktop" role="button">控制面板</a></li>
                    <li><a href="javascript:;" class="hidden-phone visible-tablet visible-desktop" role="button"><?php echo $nickname;?></a></li>
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> <?php echo $user;?>
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="password.php">修改密码</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" class="visible-phone" href="#">Settings</a></li>
                            <li class="divider visible-phone"></li>
                            <li><a tabindex="-1" href="?go=logout">退出系统</a></li>
                        </ul>
						
                    </li>
                    
                </ul>
                <a class="brand" href="javascript:;"><span class="second">订单管理系统-支付平台</span></a>
        </div>
    </div>
    


    
    <div class="sidebar-nav">
	
        <a href="#client-menu" class="nav-header" data-toggle="collapse"><i class="icon-magnet"></i>订单管理</a>
        <ul id="client-menu" class="nav nav-list collapse">
            <li><a href="deal_list.php">待处理订单</a></li>
            <li><a href="my_list.php">我的订单</a></li>	
			<li><a href="order_list.php">订单查询</a></li>
			<li><a href="list_mgr.php">订单记录</a></li>
			<?php if($level == 1){ ?>
			
			
			<li><a href="mgr_list.php">订单管理</a></li>			
			<?php }	?>
        </ul>

		<!--
		
        <a href="#accounts-menu" class="nav-header" data-toggle="collapse"><i class="icon-magic"></i>统计管理</a>
        <ul id="accounts-menu" class="nav nav-list collapse">
            <li ><a href="#">订单统计图</a></li>
            <li ><a href="#">订单趋势图</a></li>
        </ul>
		
		-->

        <a href="#legal-menu" class="nav-header" data-toggle="collapse"><i class="icon-legal"></i>系统管理</a>
        <ul id="legal-menu" class="nav nav-list collapse">
			<?php if($level == 1){ ?>
			<li ><a href="sys_user.php">用户管理</a></li>			
			<li ><a href="clear.php">清空订单</a></li>
			<li ><a href="setting.php">系统设置</a></li>
			<?php }	?>
			<li ><a href="log_list.php">登录日志</a></li>
            <li ><a href="password.php">修改密码</a></li>
        </ul>
        <?php if($level == 1){ ?>
        <a href="#money-menu" class="nav-header" data-toggle="collapse"><i class="icon-money"></i>下发管理</a>
        <ul id="money-menu" class="nav nav-list collapse">
        	<li><a href="daifu_list.php">下发列表</a></li>
        </ul>
        <?php }?>

        <a href="#" class="nav-header" ><i class="icon-comment"></i>工作汇报</a>

<!--        <a href="#pay-menu" class="nav-header" data-toggle="collapse"><i class="icon-lemon"></i>支付管理</a>
        <ul id="pay-menu" class="nav nav-list collapse">
            <li><a href="pay_type_list.php">支付类型</a></li>
            <li><a href="pay_merchant_list.php">支付接口商</a></li>
            <li><a href="pay_type_merchant_list.php">支付方式</a></li>
        </ul>-->

        <a href="#tool-menu" class="nav-header" data-toggle="collapse"><i class="icon-cogs"></i>工具箱</a>
        <ul id="tool-menu" class="nav nav-list collapse">
            <li><a href="generation.php">支付api代码生成</a></li>
        </ul>
    </div>
	

   
    



