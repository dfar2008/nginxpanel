<?php include('header.php'); ?>

<style>
#STable td.td_block {
	padding:10px 20px;
	text-align:left;
	line-height:23px;
}
</style>
<div id="body">
<h2>AMH » Backup </h2>
<div id="category">
<a href="index.php?c=backup&a=backup_list" id="backup_list">备份列表</a>
<a href="index.php?c=backup&a=backup_list&category=backup_remote" id="backup_remote" >远程设置</a>
<a href="index.php?c=backup&a=backup_list&category=backup_now" id="backup_now" >即时备份</a>
</div>
<script> G('<?php echo $category;?>').className = 'activ'; </script>
<?php
	if(isset($category))
		include($category . '.php');
?>

</div>
<?php include('footer.php'); ?>
