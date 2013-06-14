<?php include('header.php'); ?>

<div id="body">
<h2>AMH » Account </h2>
<div id="category">
<a href="index.php?c=account&a=account_my&category=account_info" id="account_info">账号信息</a>
<a href="index.php?c=account&a=account_my&category=account_config" id="account_config" >面板配置</a>
</div>
<script> G('<?php echo $category;?>').className = 'activ'; </script>
<?php
	if(isset($category))
		include($category . '.php');
?>
</div>
<?php include('footer.php'); ?>
