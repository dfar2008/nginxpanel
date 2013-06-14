<?php

class accounts extends AmysqlModel
{
	// 运行的主机
	function get_amh_domain_list()
	{
		$host_list = '';
		$cmd = 'amh ls_vhost';
		$result = trim(shell_exec($cmd), "\n");
		$run_list = explode("\n", $result);
		foreach ($run_list as $key=>$val)
		{
			if(!empty($val))
			{
				$cmd = 'amh cat_vhost ' . substr($val, 0, -5);
				$conf = trim(shell_exec($cmd), "\n");
				preg_match_all('/server_name(.*); #server_name end/', $conf, $host_server_name);
				$host_list .= str_replace(' ', ',', trim($host_server_name[1][0])) . ',';
			}
		}
		Return explode(',', trim($host_list, ','));
	}

	// 取得系统配置
	function get_amh_config()
	{
		$sql = "SELECT * FROM amh_config";
		$result = $this -> _query($sql);
		while ($rs = mysql_fetch_assoc($result))
			$data[$rs['config_name']] = $rs;

		$cmd = "amh cat_nginx";
		$result = trim(shell_exec($cmd), "\n");
		$result = Functions::trim_result($result);
		preg_match('/listen[\s]*([0-9]+)/', $result, $listen);
		$data['AMHListen']['config_value'] = $listen[1];

		preg_match('/\$host != \'(.*)\'/', $result, $domain);
		$data['AMHDomain']['config_value'] = isset($domain[1]) ? $domain[1] : 'Off';

		Return $data;
	}

	// 更新系统配置
	function up_amh_config()
	{
		$data_name = array('HelpDoc', 'LoginErrorLimit', 'VerifyCode', 'AMHListen', 'AMHDomain');
		$Affected = 0;
		foreach ($data_name as $val)
		{
			if (isset($_POST[$val]) && $_POST[$val] != $_POST[$val.'_old'])
			{
				$this -> _update('amh_config', array('config_value' => $_POST[$val]), " WHERE config_name = '$val' ");
				$Affected += $this -> Affected;

				if ($val == 'AMHListen')
				{
					$cmd = "amh SetParam amh amh_Listen $_POST[$val]";
					$cmd = Functions::trim_cmd($cmd);
					$result = trim(shell_exec($cmd), "\n");
				}

				if ($val == 'AMHDomain')
				{
					$cmd = "amh SetParam amh amh_domain $_POST[$val]";
					$cmd = Functions::trim_cmd($cmd);
					$result = trim(shell_exec($cmd), "\n");
				}
			}
		}
		Return $Affected;
	}

	// 更改密码
	function change_pass($user_password)
	{
		$user_name = $_SESSION['amh_user_name'];
		$user_password = md5(md5($user_password.'_amysql-amh'));
		$sql = "UPDATE amh_user SET user_password = '$user_password' WHERE user_name = '$user_name'";
		$this -> _query($sql);
		Return $this -> Affected;
	}
	

	// 日志列表
	function log_list()
	{
		$sql = "SELECT al.*, au.user_name FROM amh_log AS al LEFT JOIN amh_user AS au ON al.log_user_id = au.user_id ORDER BY al.log_id DESC LIMIT 10";
		Return $this -> _all($sql);
	}

	// 登录记录列表
	function login_list()
	{
		$sql = "SELECT * FROM amh_login ORDER BY login_id DESC LIMIT 10";
		Return $this -> _all($sql);
	}

}

?>