<?php include('header.php'); ?>

<div id="body">
<?php include('module_category.php'); ?>


<p>下载新的模块程序:</p>
<?php
	if (!empty($notice)) echo '<div style="margin:18px 2px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<div id="module_down">
<form action="" method="POST" />
<table border="0" cellspacing="1"  id="STable" style="width:570px;">
	<tr>
	<th>下载模块</th>
	</tr>
	<tr>
	<td class="td_block" style="padding:22px;text-align:left;">
	输入模块名字: <input type="text" class="input_text" name="module_name" style="width:251px"/>
	<button type="submit" name="download_submit" class="primary button" style="_margin-bottom:15px;_margin-left:3px;"><span class="check icon"></span>搜索下载</button> 
	</td>
	</tr>
</table>
</form>
</div>



<div id="notice_message" style="width:460px;line-height:25px">
<h3>» Download Module</h3>
1) 下载AMH官方提供的模块，输入模块名字进行下载。 <br />
2) 模块脚本保存目录：/root/amh/modules <br />
3) 支持用户创建编写新的功能模块，您也可以把模块提交给我们，<br />
审核通过后将列入官方下载列表或会收录为默认安装模块提供给用户使用，<br />
模块编程规范请查阅官方论坛文档。<br />
4) 更多丰富功能模块请查阅官方论坛获得。<br />
</div>

</div>
<?php include('footer.php'); ?>