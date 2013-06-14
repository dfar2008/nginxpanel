<?php include('header.php'); ?>

<div id="body">
<?php include('module_category.php'); ?>

<?php if (isset($_GET['action'])) {?>
<script>
// 面板php重启
Ajax.get('./index.php?c=host&a=host&run=amh-web&m=php&g=reload&confirm=y');
</script>
<?php } ?>


<p>模块扩展&程序管理列表:</p>
<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<div id="module_list">
	<?php
	if (is_array($module_list_data['data']) && count($module_list_data['data']) > 0)
	{
		foreach ($module_list_data['data'] as $key=>$val)
		{
	?>
		<div class="item"  onmouseover="this.className='item_hover'" onmouseout="this.className='item'">
			<h3><?php echo $val['AMH-ModuleName'];?><i><font><?php echo $val['AMH-ModuleDate'];?></font></i></h3>
			<p><?php echo $val['AMH-ModuleDescription'];?></p>
			<em><a href="<?php echo $val['AMH-ModuleWebSite'];?>" target="_blank"><?php echo $val['AMH-ModuleWebSite'];?></a></em>
			<i class="by">ModuleScript By: <?php echo $val['AMH-MoudleScriptBy'];?></i>
			<a class="button" href="./index.php?c=module&a=module_list&name=<?php echo $val['AMH-ModuleName'];?>&action=<?php echo $val['AMH-ModuleAction'];?>&page=<?php echo $page;?>" onclick="return confirm('确认<?php echo $val['AMH-ModuleButton'];?><?php echo $val['AMH-ModuleName'];?>吗?');" ><?php echo $val['AMH-ModuleButton'];?></a>
			<?php if($val['AMH-ModuleStatus'] == 'true') { ?>
				<?php if( $val['AMH-ModuleAdmin'] != '') { ?>
				<a class="button" href="<?php echo $val['AMH-ModuleAdmin'];?>" target="_blank" style="right: 120px;">管理模块</a>
				<?php }?>
			<?php } else { ?>
				<a class="button" href="./index.php?c=module&a=module_list&name=<?php echo $val['AMH-ModuleName'];?>&action=delete&page=<?php echo $page;?>" onclick="return confirm('确认删除<?php echo $val['AMH-ModuleName'];?>吗?');" style="right: 120px;">删除</a>
			<?php } ?>
			<div style="clear:both;"></div>
		</div>
	<?php
		}
	}
	else
	{
	?>
		<div class="item" style="padding:20px; 10px"><p>无模块扩展程序。</p></div>   
	<?php
	}
	?>
</div>

<div id="page_list">总<?php echo $total_page;?>页 - 共<?php echo $module_list_data['sum'];?>个模块扩展 » 页码 <?php echo $page_list;?> </div>


<div id="notice_message" style="width:500px;">
<h3>» WEB Module</h3>
1) 注意: 安装非官方提供的模块，必要验证确认模块安全性。 <br />
2) 模块需先卸载再删除。卸载模块即恢复至安装模块之前的状态。
<br />删除模块即是删除对应/root/amh/modules模块脚本文件。 <br />

<h3>» SSH Module</h3>
1) 有步骤提示操作: <br />
ssh执行命令: amh module <br />
然后选择对应的模块进行管理。<br />
2) 或直接操作: <br />
<ul>
<li>下载模块: amh module download [模块名字]</li>
<li>模块信息: amh module [模块名字] info</li>
<li>安装模块: amh module [模块名字] install</li>
<li>管理模块: amh module [模块名字] admin</li>
<li>卸载模块: amh module [模块名字] uninstall</li>
<li>安装状态: amh module [模块名字] status</li>
<li>删除模块: amh module [模块名字] delete</li>
</ul>
4) 支持用户创建编写新的功能模块，模块脚本目录 /root/amh/modules
<br />模块编程规范请查阅官方论坛文档。
</div>
</div>
</div>
<?php include('footer.php'); ?>