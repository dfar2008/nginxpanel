<p>最近操作记录:</p>
<table border="0" cellspacing="1"  id="STable" style="width:auto;">
	<tr>
	<th width="40">ID</th>
	<th width="50">用户</th>
	<th>操作</th>
	<th width="110">操作IP</th>
	<th width="130">操作时间</th>
	</tr>
<?php
	foreach ($log_list as $key=>$val)
	{
?>
	<tr>
	<th class="i"><?php echo $val['log_id'];?></th>
	<td><?php echo !empty($val['user_name']) ? $val['user_name'] : '<i style="font-size:11px">AMH系统</i>';?></td>
	<td>&nbsp; <?php echo nl2br($val['log_text']);?> &nbsp; </td>
	<td><?php echo $val['log_ip'];?></td>
	<td><?php echo $val['log_time'];?></td>
	</tr>
<?php
	}
?>
</table>
<br />

<p>最近登录记录:</p>
<table border="0" cellspacing="1"  id="STable" style="width:700px;">
	<tr>
	<th width="40">ID</th>
	<th>用户名</th>
	<th>登录IP</th>
	<th>登录状态</th>
	<th>登录时间</th>
	</tr>
<?php
	foreach ($login_list as $key=>$val)
	{
?>
	<tr>
	<th class="i"><?php echo $val['login_id'];?></th>
	<td><?php echo htmlspecialchars($val['login_user_name']);?></td>
	<td><?php echo $val['login_ip'];?></td>
	<td><?php echo $val['login_success'] ? '成功' : '失败';?></td>
	<td><?php echo $val['login_time'];?></td>
	</tr>
<?php
	}
?>
</table>
<br />

<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>更改账号密码:</p>
<form action="index.php?c=account&a=account_my" method="POST"  id="account"  autocomplete="off">
<table border="0" cellspacing="1"  id="STable" style="width:300px;">
	<tr>
	<th> &nbsp; </th>
	<th>值</th>
	</tr>
	<tr><td>旧密码</td>
	<td><input type="password" name="user_password" class="input_text" value="<?php echo isset($_POST['user_password']) ? $_POST['user_password'] : '';?>" /></td>
	</tr>
	<tr><td>新密码</td>
	<td><input type="password" name="new_user_password" class="input_text" value="<?php echo isset($_POST['user_password']) ? $_POST['new_user_password'] : '';?>" /></td>
	</tr>
	<tr><td>确认新密码</td>
	<td><input type="password" name="new_user_password2" class="input_text"  value="<?php echo isset($_POST['user_password']) ? $_POST['new_user_password2'] : '';?>" /></td>
	</tr>
</table>
<button type="submit" class="primary button" name="submit"><span class="check icon"></span>更改</button> 
</form>
