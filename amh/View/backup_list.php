<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>数据备份列表:</p>
<table border="0" cellspacing="1"  id="STable" style="width:900px;">
	<tr>
	<th>ID</th>
	<th>备份文件</th>
	<th>文件大小</th>
	<th>是否加密</th>
	<th>远程备份</th>
	<th width="180">说明备注</th>
	<th>备份时间</th>
	<th>操作</th>
	</tr>
	<?php 
	if(!is_array($backup_list['data']) || count($backup_list['data']) < 1)
	{
	?>
		<tr><td colspan="8" style="padding:10px;">暂无备份数据记录.</td></tr>
	<?php	
	}
	else
	{
		foreach ($backup_list['data'] as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $val['backup_id'];?></th>
			<td><?php echo $val['backup_file'];?></td>
			<td><?php echo $val['backup_size'];?>MB</td>
			<td><?php echo empty($val['backup_password']) ? '无' : '有加密';?></th>
			<td><?php echo ($val['backup_remote'] == '0') ? '无' : '有远程备份';?></th>
			<td class="comment"><?php echo !empty($val['backup_comment']) ? $val['backup_comment'] : '<i>无</i>';?>
			</td>
			<td><?php echo $val['backup_time'];?></td>
			<td>
			#
			</td>
			</tr>
	<?php
		}
	}
	?>
</table>
<div id="page_list">总<?php echo $total_page;?>页 - <?php echo $backup_list['sum'];?>份文件 » 页码 <?php echo $page_list;?> </div>
<br />
