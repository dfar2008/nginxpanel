<?php include('header.php'); ?>

<div id="body">
<?php include('mysql_category.php'); ?>

<p>MySQL数据库列表:</p>
<table border="0" cellspacing="1"  id="STable" style="width:500px;margin-bottom:5px;">
	<tr>
	<th>ID</th>
	<th>数据库</th>
	<th>字符集</th>
	<th>表数量</th>
	</tr>
	<?php
		foreach ($databases as $key=>$val)
		{
	?>
	<tr>
	<th class="i"><?php echo $key+1;?></th>
	<td><a href="/phpmyadmin/index.php?db=<?php echo urlencode($val['Database']);?>" target="_blank"><?php echo $val['Database'];?></a></td>
	<td><?php echo $val['collations'];?></td>
	<td><?php echo $val['sum'];?></td>
	<?php
		}
	?>
</table>
<input type="button" value="PHPMyAdmin管理MySQL" onclick="window.open('/phpmyadmin/');"/>


<!--
<div id="notice_message" style="width:470px;">
<h3>» SSH MySQL</h3>
1) 有步骤提示操作: <br />
ssh执行命令: amh mysql <br />
然后选择对应的1~6的选项进行操作。<br />

2) 或直接操作: <br />
<ul>
<li>启动MySQL: amh mysql start</li>
<li>停止MySQL: amh mysql stop </li>
<li>重载MySQL: amh mysql reload </li>
<li>重启MySQL: amh mysql restart</li>
<li>强制重载MySQL: amh mysql force-reload </li>
</ul>
3) MySQL本地连接地址使用 127.0.0.1
</div>
</div>
-->
<?php include('footer.php'); ?>
