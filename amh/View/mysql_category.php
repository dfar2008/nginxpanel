<h2>AMH » MySQL</h2>
<div id="category">
<a href="index.php?c=mysql&a=mysql_list" id="mysql_list" >数据库</a>
<script>
var action = '<?php echo $_GET['a'];?>';
var action_dom = G(action) ? G(action) : G('mysql_list');
action_dom.className = 'activ';
</script>
</div>