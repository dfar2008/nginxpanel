<h2>AMH » Host</h2>
<div id="category">
<a href="index.php?c=host&a=vhost" id="vhost" >虚拟主机</a>
<a href="index.php?c=host&a=vhost#add_host" id="vhost">新增</a>
<script>
var action = '<?php echo $_GET['a'];?>';
var action_dom = G(action) ? G(action) : G('vhost');
action_dom.className = 'activ';
</script>
</div>