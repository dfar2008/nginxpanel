<h2>AMH » Module </h2>
<div id="category">
<a href="index.php?c=module&a=module_list" id="module_list">管理模块</a>
<a href="index.php?c=module&a=module_down" id="module_down" >下载模块</a>
</div>
<script> 
var action = '<?php echo $_GET['a'];?>';
var action_dom = G(action) ? G(action) : G('module_list');
action_dom.className = 'activ';
</script>
