

<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>即时备份:</p>
<table border="0" cellspacing="1"  id="STable" style="width:800px;">
	<tr>
	<th>立刻创建数据备份</th>
	</tr>
	<tr>
	<td class="td_block">
	<form action="index.php?c=backup&a=backup_list&category=backup_now" method="POST"  id="backup_now" />
	<input type="hidden" class="input_text" name="backup_password" value="" />	
	<input type="hidden" class="input_text" name="backup_password2" value="" />
	<input type="checkbox" name="backup_retemo" <?php echo (isset($_POST['backup_retemo']) && $_POST['backup_retemo'] == 'on' ) ? 'checked=""' : '';?> /> 同时备份至远程 <br />
	
	

	添加备注:<br />
	<input type="text" class="input_text" name="backup_comment" value="" /> <br/>
	<button type="submit" class="primary button" name="backup_now"><span class="check icon"></span>备份</button> 
	</form>
	</td>
	</tr>
</table>
<br />

<div id="notice_message" style="width:660px;">
<h3>» 即时备份</h3>
1) 选中同时备份至远程数据将传输至远程设置的FTP/SSH服务器(需设置开启状态)。<br />
2) 建议设置密码备份数据，同时密码不可找回，请牢记备份密码。<br />
<h3>» SSH 即时备份</h3>
<ul>
<li>查看备份文件: amh ls_backup </li>
<li>备份命令: amh backup [y/n 远程备份] [密码/n] [备注]</li>
</ul>
使用示例:<br />
本地备份: amh backup<br />
本地与远程备份: amh backup y<br />
本地与远程备份并设置密码: amh backup y amh_password<br />
本地备份与添加备注: amh backup n n 2012backup<br />
</div>