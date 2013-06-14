<?php

class hosts extends AmysqlModel
{

	// host列表
	function host_list()
	{
		$sql = "SELECT * FROM users where deleted=0 ORDER BY id DESC";
		Return $this -> _all($sql);
	}

	// 取得host
	function get_host($host_domain)
	{
		$sql = "SELECT * FROM users WHERE deleted=0 and domain = '$host_domain'";
		Return $this -> _row($sql);
	}

	// host列表更新
	function host_update()
	{
		$host_list = array();
		$cmd = 'amh ls_vhost';
		$result = trim(shell_exec($cmd), "\n");
		$run_list = explode("\n", $result);
		foreach ($run_list as $key=>$val)
		{
			if(!empty($val))
			{
				$cmd = 'amh cat_vhost ' . substr($val, 0, -5);
				$host_list[$val]['conf'] = trim(shell_exec($cmd), "\n");
				$host_list[$val]['host_nginx'] = 1;

				$cmd = 'amh cat_php_fpm ' . substr($val, 0, -5);
				$host_list[$val]['php_fpm_conf'] = trim(shell_exec($cmd), "\n");
			}
		}

		$cmd = 'amh ls_vhost_stop';
		$result = trim(shell_exec($cmd), "\n");
		$stop_list = explode("\n", $result);
		foreach ($stop_list as $key=>$val)
		{
			if(!empty($val))
			{
				$cmd = 'amh cat_vhost_stop ' . substr($val, 0, -5);
				$host_list[$val]['conf'] = trim(shell_exec($cmd), "\n");
				$host_list[$val]['host_nginx'] = 0;

				$cmd = 'amh cat_php_fpm ' . substr($val, 0, -5);
				$host_list[$val]['php_fpm_conf'] = trim(shell_exec($cmd), "\n");
			}
		}

		foreach ($host_list as $key=>$val)
		{
			$conf = $val['conf'];
			$php_fpm_conf = $val['php_fpm_conf'];

			preg_match_all('/server_name(.*); #server_name end/', $conf, $host_server_name);
			$host_list[$key]['host_server_name'] = str_replace(' ', ',', trim($host_server_name[1][0]));

			preg_match_all('/root(.*)\$domain;/', $conf, $host_root);
			$host_list[$key]['host_root'] = trim($host_root[1][0]);

			preg_match_all('/index(.*); #index end/', $conf, $host_index_name);
			$host_list[$key]['host_index_name'] = str_replace(' ', ',', trim($host_index_name[1][0]));

			preg_match_all('/include rewrite\/(.*); #rewrite end/', $conf, $host_rewrite);
			$host_list[$key]['host_rewrite'] = trim($host_rewrite[1][0]);

			preg_match_all('/error_page ([0-9]{3}) =/', $conf, $host_error_page);
			$host_list[$key]['host_error_page'] = implode(',', $host_error_page[1]);

			preg_match_all('/access_log(.*); #access_log end/', $conf, $host_log);
			$host_list[$key]['host_log'] = strpos($host_log[1][0] , 'access.log') !== false ? 1 : 0;

			preg_match_all('/error_log(.*); #error_log end/', $conf, $host_error_log);
			$host_list[$key]['host_error_log'] = strpos($host_error_log[1][0], 'error.log') !== false ? 1 : 0;

			$host_list[$key]['host_subdirectory'] = (preg_match('/[#]+\s*set.*#host subdirectory/', $conf)) ? 0 : 1;

			$php_fpm_arr = array('/pm = (.*)/', '/pm\.min_spare_servers = (.*)/', '/pm\.start_servers = (.*)/', '/pm\.max_spare_servers = (.*)/', '/pm\.max_children = (.*)/');
			$php_fpm_val = array();
			foreach ($php_fpm_arr as $val)
			{
				preg_match($val, $php_fpm_conf, $host_php_fpm);
				$php_fpm_val[] = $host_php_fpm[1];
			}
			$host_list[$key]['host_php_fpm'] = implode(',', $php_fpm_val);

			$host_list[$key]['host_domain'] = str_replace('.conf', '', $key);
			$cmd = 'amh cat_php_pid php-fpm-' . $host_list[$key]['host_domain'];
			$host_list[$key]['host_php'] = strlen(trim(shell_exec($cmd), "\n")) > 1 ? 1 : 0;
			unset($host_list[$key]['conf']);
			unset($host_list[$key]['php_fpm_conf']);
		}

		$all_host_name = array();
		foreach ($host_list as $key=>$val)
		{
			$get_host = $this -> get_host($val['host_domain']);
			if (isset($get_host['host_domain']))
				$this -> _update('amh_host', $val, " WHERE host_domain = '$val[host_domain]' ");
			else
			{
			    $val['host_type'] = 'ssh';
				$this -> _insert('amh_host', $val);
			}
			$all_host_name[] = $val['host_domain'];
		}

		if(count($all_host_name) > 0)
		{
			$sql = "DELETE FROM amh_host WHERE host_domain NOT IN ('" . implode("','", $all_host_name) . "')";
			$this -> _query($sql);
		}
		else
		{
		    $sql = "TRUNCATE TABLE `amh_host`";
			$this -> _query($sql);
		}
	}

	//新增host ssh
	function host_insert_ssh($data)
	{
	    $data['domain'] = str_ireplace("http://","",$data['domain']);
		$data['domain'] = str_ireplace("/","",$data['domain']);
		$len = strpos($data['domain'],".");
		$subdomain = "";
		if($len) {
			$subdomain = substr($data['domain'],0,$len);
		}
		if($subdomain != "" && $data['domainip'] != "") {
			global $Config;
			//the same password for dnspod and amh
			$api = 'https://dnsapi.cn/Record.Create'; //A record
			$param = array(
				'login_email'   =>$Config['DNSPOD_EMAIL'],
				'login_password'=>$Config['DNSPOD_PSWORD'],
				'domain_id'=>$Config['DNSPOD_DOMAINID'],
				'sub_domain'=>$subdomain,
				'record_type'=>'A',
				'record_line'=>'默认',
				'value'=>$data['domainip'],
				'format'        =>'json'
			); 
			$query = http_build_query($param);
			$ch = curl_init($api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			$result = curl_exec($ch);
			curl_close($ch);
			$arr = json_decode($result);
		}
		$cmd = 'amh host add '.$data['domain'].' '.$data['username'].' '.$data['password'].' '.$data['install_soft'];//amh host add amhhost4.crm123.cn crm amhhost4 c3crm
		$cmd = Functions::trim_cmd($cmd);
		$result = shell_exec($cmd);
		Return Functions::trim_result($result);
	}

	// 新增host
	function host_insert($data)
	{	
		global $Config;
        $data['domain'] = str_ireplace("http://","",$data['domain']);
		$data['domain'] = str_ireplace("/","",$data['domain']);
		$insert_data = array();
		$insert_data['domain'] = $data['domain'];
		$insert_data['domainip'] = $data['domainip'];
		$insert_data['User'] = $data['username'];
		$insert_data['Password'] = md5($data['password']);
		$insert_data['Password_src'] = $data['password'];		
		$insert_data['Dir'] = "/".$Config['ROOT_DIR']."/www/".$data['domain']."/".$data['domain'];
		$insert_data['Comment'] = $data['Comment'];
		$insert_data['createtime'] = date('YmdHis');
		$insert_data['host_nginx'] = 1;
		Return $this -> _insert('users', $insert_data);
	}

	// 编辑host
	function edit_host()
	{
		$data_name = array('host_domain', 'host_server_name',  'host_index_name', 'host_rewrite', 'host_error_page', 'host_log', 'host_error_log', 'host_subdirectory', 'host_php_fpm');
		$_POST['host_log'] = ($_POST['host_log']) ? 'on' : 'off';
		$_POST['host_error_log'] = ($_POST['host_error_log']) ? 'on' : 'off';
		$_POST['host_subdirectory'] = ($_POST['host_subdirectory']) ? 'on' : 'off';
		$_POST['host_php_fpm'] = "$_POST[php_fpm_pm],$_POST[min_spare_servers],$_POST[start_servers],$_POST[max_spare_servers],$_POST[max_children]";

		$cmd = 'amh host edit';
		foreach ($data_name as $key=>$val)
			$cmd .= (isset($_POST[$val]) && !empty($_POST[$val])) ? ' ' . $_POST[$val] : ' 0 ';
		$cmd = Functions::trim_cmd($cmd);
		Return Functions::trim_result(shell_exec($cmd));
	}

	// 删除host ssh
	function host_del_ssh($host_domain)
	{
		$sql = "update users set deleted=1 WHERE domain='".$host_domain."'";
		$this -> _query($sql);
		$row = $this ->get_host($host_domain);
		$username = $row["User"];
		$password = $row["Password"];
		$cmd = "amh host del $host_domain $username $password";
		$cmd = Functions::trim_cmd($cmd);
		Return Functions::trim_result(shell_exec($cmd));
	}

	// 备份host ssh
	function host_backup_ssh($host_domain)
	{
		$row = $this ->get_host($host_domain);
		$username = $row["User"];
		$password = $row["Password"];
		$cmd = "amh host backup $host_domain $username $password";
		$cmd = Functions::trim_cmd($cmd);
		Return Functions::trim_result(shell_exec($cmd));
	}

	


	// 取得php配置参数值
	function get_php_param($param_list)
	{
		$cmd = "amh cat_php_ini";
		$cmd = Functions::trim_cmd($cmd);
		$php_ini = Functions::trim_result(shell_exec($cmd));
		foreach ($param_list as $key=>$val)
		{
			preg_match("/$val[1] = (.*)/", $php_ini, $param_val);
			$param_list[$key][3] = $param_val[1];
		}
		Return $param_list;
	}
}

?>