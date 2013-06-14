<?php include('header.php'); 
function real_server_ip()
{
    if (isset($_SERVER))
    {
        if (isset($_SERVER['SERVER_ADDR']))
        {
            $serverip = $_SERVER['SERVER_ADDR'];
        }
		elseif(isset($_SERVER['SERVER_NAME']))
		{
			$serverip = gethostbyname($_SERVER['SERVER_NAME']);
		}
        else
        {
            $serverip = '0.0.0.0';
        }
    }
    else
    {
        $serverip = getenv('SERVER_ADDR');
    }

    return $serverip;
}
?>
<script src="View/js/host.js"></script>
<style>
#STable th {
	padding:4px 6px;
}
#STable td {
padding: 4px 5px 3px 5px;
_padding: 2px 3px;
}
</style>

<div id="body">
<?php include('host_category.php'); ?>

<?php 
if(is_array($host_list) && count($host_list) > 0)
	$list_show = true;
?>


<?php
	if (!empty($top_notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $top_notice . '</p></div>';
?>
<p>虚拟主机列表:</p>
<table border="0" cellspacing="1"  id="STable"  style="width:<?php echo isset($list_show) ? 'auto':'1111px';?>">
	<tr>
	<th>ID</th>
	<th>域名</th>
	<th>IP</th>	
	<th>用户名</th>
	<th>密码</th>
	<th>备注</th>
	<th>添加时间</th>
	<th>运行维护</th>
	<th>操作</th>
	</tr>
	<?php 
	if(!isset($list_show))
	{
	?>
		<tr><td colspan="15" style="padding:10px;">暂无虚拟主机</td></tr>
	<?php	
	}
	else
	{
		foreach ($host_list as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $val['id'];?></th>
			<td><a href="http://<?php echo $val['domain'];?>" target="_blank"><?php echo $val['domain'];?></a></td>
			<td><?php echo $val['domainip'];?></td>
			<td><a href="/phpmyadmin/index.php?db=<?php echo $val['User'];?>&pma_username=<?php echo $val['User'];?>&pma_password=<?php echo $val['Password_src'];?>" target="_blank"><?php echo $val['User'];?></a></td>
			<td><?php echo $val['Password_src'];?></td>
			<td><?php echo $val['Comment'];?></td>
			<td><?php echo $val['createtime'];?></td>
			<td>
			<a href="index.php?c=host&a=vhost&run=<?php echo $val['domain'];?>&m=host&g=<?php echo $val['host_nginx'] ? 'stop' : 'start';?>" >
			<span <?php echo $val['host_nginx'] ? 'class="run_start" title="主机运行正常"' : 'class="run_stop" title="主机已停止"';?>>Host</span>
			</a>

			<td>
			<a href="index.php?c=host&a=vhost&backup=<?php echo $val['domain'];?>" class="button"><span class="pen icon"></span>备份</a>
			<a href="index.php?c=host&a=vhost&del=<?php echo $val['domain'];?>" class="button" onclick="return confirm('确认删除虚拟主机:<?php echo $val['domain'];?>?');"><span class="cross icon"></span>删除</a>
			</td>
			</tr>
	<?php
		}
	}
	?>

</table>
<br />
<br />

<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<a name="add_host"></a>
<p>
<?php echo isset($edit_host) ? '编辑' : '新增';?>虚拟主机: <?php echo isset($edit_host) ? $_POST['host_domain'] : '';?>
</p>
<form action="index.php?c=host&a=vhost" method="POST"  id="host_edit" />
<table border="0" cellspacing="1"  id="STable" style="width:950px;">
	<tr>
	<th width="80px"> &nbsp; </th>
	<th width="70px">值</th>
	<th>说明</th>
	</tr>

	<tr><td>域名</td>
	<td><input type="text" id="domain" name="domain" class="input_text <?php echo isset($edit_host) ? ' disabled' : '';?>" value="<?php echo $_POST['domain'];?>" <?php echo isset($edit_host) ? 'disabled=""' : '';?>/></td>
	<td><p> &nbsp; <font class="red">*</font> 用于唯一标识的主域名 </p>
	<p> &nbsp; 不需填写http:// 格式例如: <a href="http://www.c3crm.com" target="_blank">demo.c3crm.com</a></p>
	</td>
	</tr>
	<tr><td>域名绑定ip</td>
	<td><input type="text" id="domainip" name="domainip" class="input_text" value="<?php echo real_server_ip();?>" <?php echo isset($edit_host) ? 'disabled=""' : '';?>/></td>
	<td><p> &nbsp; 用于绑定域名的ip，一般是当前服务器的ip，域名和IP会自动通过dnspod接口绑定，默认支持crm123.cn的二级域名，如果需要修改其他域名，需要修改Model/hosts.php中的host_insert_ssh函数中dnspod集成的相关参数。</p>
	</td>
	</tr>

	<tr><td>FTP帐户名<br />数据库用户名<br />数据库名</td>
	<td><input type="text" id="username" name="username" class="input_text" value="<?php echo $_POST['username'];?>" </td>
	<td><p> &nbsp; 由1-15位的字母，数字，下划线组成，不能重复</p>
	<p> &nbsp; 例如: <a href="http://www.c3crm.com" target="_blank">c3crm</a>,crmone,songxia,changjiang </p>
	</td>
	</tr>
	
	<tr><td>FTP密码<br />数据库密码</td>
	<td><input type="password" id="password" name="password" class="input_text" value="<?php echo $_POST['password'];?>" </td>
	<td><p> &nbsp; 建议用字母，数字和~!-_的组合<br />
			<input type="button" value="生成密码" onClick="javascript:generate();"/>
			<label id="lbl_password"></label></p>
	</td>
	</tr>
	<tr><td>安装软件</td>
	<td><select name="install_soft" id="install_soft">
				<option value="no" >不安装任何软件</option>
				<option value="crm" >易客CRM</option>
				<option value="crmedu" >易客CRM教育版</option>
				<option value="crmfree" >易客CRM开源版</option>
			</select>
	</td>
	<td><p> &nbsp; 如果需要安装标准版本，请选择所需要指定的软件，如果不需要安装软件，就需要一个空的目录和数据库，请选择第一个选项</p>
	</td>
	</tr>


	<tr><td>Rewrite规则</td>
	<td>
	<select name="host_rewrite" id="host_rewrite">
	<option value="">选择虚拟Rewrite规则</option>
	<?php
		foreach ($Rewrite as $key=>$val)
			echo '<option value="' . $val . '">' . $val . '</option>';
	?>
	</select>
	</td>
	<td><p> &nbsp; URL重写规则</p><p> &nbsp; Rewrite存放文件夹 /usr/local/nginx/conf/rewrite</p></td>
	</tr>
	<tr><td>备注</td>
	<td><input type="text" id="Comment" name="Comment" class="input_text" value="<?php echo $_POST['Comment'];?>" <?php echo isset($edit_host) ? 'disabled=""' : '';?>/></td>
	<td><p> &nbsp; 有关服务器的备注</p>
	</td>
	</tr>
</table>

<?php if (isset($edit_host)) { ?>
	<input type="hidden" name="save_edit" value="<?php echo $_POST['host_domain'];?>" />
<?php } else { ?>
	<input type="hidden" name="save" value="y" />
<?php }?>

<button type="submit" class="primary button" name="submit"><span class="check icon"></span>保存</button> 
</form>

<!--
<div id="notice_message">
<h3>» SSH Host</h3>
1) 有步骤提示操作: <br />
ssh执行命令: amh host <br />
然后选择对应的1~7的选项进行操作。<br />

2) 或直接操作: <br />
<ul>
<li>启动虚拟主机: amh host start [主标识域名] 缺省主标识域名即为所有</li>
<li>停止虚拟主机: amh host stop [主标识域名] 缺省主标识域名即为所有</li>
<li>虚拟主机列表: amh host list </li>
<li>新增虚拟主机: amh host add [主标识域名 amysql.com] [绑定域名 amysql.com,www.amysql.com] [默认主页 index.php,index.html] [Rewrite规则 amh] [自定义错误页面 404,502] [访问日志 on/off] [错误日志 on/off] [二级域名绑定子目录 on/off] [设置PHP-FPM static/dynamic,1,2,3,4]</li>
<br />
<li>编辑虚拟主机: amh host edit [主标识域名] [其余参数与add命令相同]</li>
<li>删除虚拟主机: amh host del [主标识域名]</li>

</ul>

3) 温馨提示:<br />
增加或编辑虚拟主机忽略参某参数请填写0，如参数有多项请使用英文逗号分隔。 <br />
例如: amh host add amysql.com amysql.com,www.amysql.com index.html,index.php 0 404,502 on off on static,1,2,3,4<br />
以上命令为增加一虚拟主机，主标识域名为amysql.com，绑定域名amysql.com与ww.amysql.com，默认主页为index.html与index.php，开启自定义404与502页面、开启错误日志、与开启子目录绑定。并设置主机php-fpm为静态模式，子进程数为4。<br />
3) 面板自身PHP操作: amh php [start/stop/restart/reload] [amh-web] [y/n] <br />
面板自身PHP主标识参数为 amh-web，并需额外增加确认参数 [y/n]
</div>
-->
</div>


<?php include('footer.php'); ?>
