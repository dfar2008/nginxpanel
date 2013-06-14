<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo isset($title) ? $title : 'AMH';?></title>
<base href="<?php echo _Http;?>" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-cn">
<link type="text/css" rel="stylesheet" href="View/css/index.css" />
<link type="text/css" rel="stylesheet" href="View/css/buttons.css" />
<script src="View/js/index.js"></script>
<style>
<?php if($_SESSION['amh_config']['HelpDoc']['config_value'] == 'no') { ?>
#notice_message {display:none;}
<?php }?>
</style>
</head>
<body>
<div id="header">
&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.c3crm.com" target="_blank"><font style="font-size: 18px;">Lnmp虚拟主机控制面板</font></a>
<div id="navigation">
<font>Hi, <?php echo $_SESSION['amh_user_name'];?></font>
<a href="index.php" id="home">首页</a>
<a href="index.php?c=host" id="host" >虚拟主机</a>
<a href="index.php?c=mysql" id="mysql" >MySQL</a>
<a href="index.php?c=backup" id="backup" >备份</a>
<a href="index.php?c=task" id="task" >计划任务</a>
<a href="index.php?c=account" id="account" >账户</a>
<a href="index.php?c=index&a=logout" >注销</a>
<script>
var action = '<?php echo $_GET['c'];?>';
var action_dom = G(action) ? G(action) : G('home');
action_dom.className = 'activ';
</script>
</div>
</div>
