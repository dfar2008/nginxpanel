window.onload = function ()
{
	var host_edit_dom = G('host_edit');
	var host_domain_dom = G('host_domain');
	var host_root_dom = G('host_root');
	var host_log_dom = G('host_log');
	var min_spare_servers_dom = G('min_spare_servers');
	var start_servers_dom = G('start_servers');
	var max_children_dom = G('max_children');
	var max_spare_servers_dom = G('max_spare_servers');
	var php_fpm_pm_dom = G('php_fpm_pm');
	var php_fpm_dynamic_dom_arr = [min_spare_servers_dom, start_servers_dom, max_spare_servers_dom];


	// 路径显示
	host_domain_dom.onkeyup = function ()
	{
		var v = (this.value == '') ? '主标识域名' : this.value;
		host_root_dom.innerHTML = v;
		host_log_dom.innerHTML = v;

	}
	host_domain_dom.onkeyup();

	// PHP-FPM
	var max_children_dom_onkeyup = function ()
	{
		if(max_children_dom.value == '' || max_spare_servers_dom.value == '' ) return;
		if (parseInt(max_children_dom.value) < parseInt(max_spare_servers_dom.value))
		{
			alert('必需设置动态模式最大进程数(' + max_spare_servers_dom.value + ')小于或等于子进程数(' + max_children_dom.value + ')');
			max_children_dom.value = max_children_dom._val ? max_children_dom._val : '';
			max_spare_servers_dom.value = max_spare_servers_dom._val ? max_spare_servers_dom._val : '';
			return false;
		}
		else
		{
		    max_children_dom._val = max_children_dom.value;
			max_spare_servers_dom._val = max_spare_servers_dom.value;
		}
	}
	max_children_dom_onkeyup();
	max_spare_servers_dom.onkeyup = max_children_dom.onkeyup = max_children_dom_onkeyup;

	php_fpm_pm_dom.onchange = function ()
	{
		var disabled = (this.value == 'static') ? true : false;
		var className = (this.value == 'static') ? 'input_text disabled' : 'input_text';
		for (var k in php_fpm_dynamic_dom_arr)
		{
			php_fpm_dynamic_dom_arr[k].disabled = disabled;
			php_fpm_dynamic_dom_arr[k].className = className;
		}
	}
	php_fpm_pm_dom.onchange();

	// 提交取消disabled
	host_edit_dom.onsubmit = function ()
	{
		for (var k in php_fpm_dynamic_dom_arr)
		{
			php_fpm_dynamic_dom_arr[k].disabled = false;
		}
		return true;
	}

}
function getpw(length) {
	var chars = '';
	chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	chars += 'abcdefghijklmnopqrstuvwxyz';
	chars += '0123456789';
	chars += '~!-_';

	var pw = '';
	chars = chars.split('');
	for(var i = 0; i < length; i++)
		pw += chars[Math.floor(Math.random() * chars.length)];
	return pw;
}
function generate() {
	var length = 10;

	var Password = '';
	Password += getpw(length) + '\r\n';

	document.getElementById('password').value = Password.substring(0, Password.length-2);
	document.getElementById('lbl_password').innerHTML = "生成的密码是:<font color='red'>"+Password.substring(0, Password.length-2)+"</font>";
}