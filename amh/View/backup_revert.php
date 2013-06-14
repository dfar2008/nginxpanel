<?php

$revert_file = isset($revert['backup_file']) ? true : false;
$revert_pass = ($revert_file && strpos($revert['backup_file'], 'tar.gz') === false) ? true : false;

?>

<script>
var backup_revert_submit = function ()
{
	var pass_required = G('pass_required');
	if (pass_required && pass_required.value == '')
	{
		alert('请输入密码。');
		return false;
	}


	if (!confirm('确认还原数据吗?')) return false;

}
</script>

<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>

<p>一键还原数据:</p>
<form action="" method="POST"  id="backup_revert&" onsubmit="return backup_revert_submit();"/>
<table border="0" cellspacing="1"  id="STable" style="width:800px;">
	<tr>
	<th>一键还原恢复数据</th>
	</tr>
	<tr>
	<td class="td_block">
	备份文件： <?php echo $revert_file ? $revert['backup_file']: '未选择';?><br />
	备注说明： <?php echo ($revert_file && !empty($revert['backup_comment']) ) ? $revert['backup_comment']: '<i>无记录</i>';?><br />
	创建时间： <?php echo $revert_file ? $revert['backup_time']: '<i>无记录</i>';?><br />
	<br />
	输入密码:<br />
	<input type="password" class="input_text" name="backup_password" <?php echo $revert_pass ? 'id="pass_required"' : '';?> /> (<?php echo $revert_pass ? '有设置密码 请输入密码' : '无需密码';?>) <br /> 

	<button type="submit" class="primary button" name="revert_submit" <?php echo $revert_file ?  '' : 'disabled' ;?> ><span class="check icon"></span>确认还原</button> 

	</td>
	</tr>
</table>
</form>
<br />


<div id="notice_message" style="width:730px;">
<h3>» 一键还原</h3>
1) 从数据备份记录列表选择需还原的备份文件。<br />
2) 还原需谨慎操作，当前AMH面板所有数据： <br />
(网站数据，MySQL数据，Nginx、PHP、FTP配置数据与任务计划、模块扩展程序) 将还原至您所选的备份文件数据。<br />
3) 如有必要请先备份当前数据再进行还原操作。<br />

<h3>» SSH 一键还原</h3>
<ul>
<li>一键还原命令: amh revert [/home/backup/备份文件] [备份时设置的密码]</li>
</ul>
使用示例:<br />
20121101-172607.amh 数据文件还原恢复: amh revert 20121101-172607.amh amh_pass
</div>

<?php if (isset($_POST['revert_submit'])) {?>
<script>
// 面板php与所有虚拟主机php重载
Ajax.get('./index.php?c=host&a=host&run=amh-web&m=php&g=reload&confirm=y');
Ajax.get('./index.php?m=php&g=reload');
</script>
<?php } ?>