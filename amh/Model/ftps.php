<?php

class ftps extends AmysqlModel
{
	
	// ftp列表
	function ftp_list()
	{
		$sql = "SELECT * FROM amh_ftp ORDER BY ftp_id ASC";
		Return $this -> _all($sql);
	}

	// 取得ftp
	function get_ftp($ftp_name)
	{
		$sql = "SELECT * FROM amh_ftp WHERE ftp_name = '$ftp_name'";
		Return $this -> _row($sql);
	}

	// ftp新增
	function ftp_insert($data)
	{
		$data['ftp_password'] = md5(md5($data['ftp_password']));
		$data_name = array('ftp_name', 'ftp_password', 'ftp_root', 'ftp_upload_bandwidth', 'ftp_download_bandwidth', 'ftp_upload_ratio', 'ftp_download_ratio', 'ftp_max_files', 'ftp_max_mbytes', 'ftp_max_concurrent', 'ftp_allow_time');
		foreach ($data_name as $val)
			$insert_data[$val] = $data[$val];
		$insert_data['ftp_type'] = isset($data['ftp_type']) ? $data['ftp_type'] : 'web';
		Return $this -> _insert('amh_ftp', $insert_data);
	}

	// ftp新增 ssh
	function ftp_insert_ssh()
	{
		if($_POST['ftp_root'] == 'index' || strpos($_POST['ftp_root'], '..') !== false || strpos($_POST['ftp_root'], '/') !== false ) 
			Return ' 禁止使用的根目录。';

		$data_name = array('ftp_name', 'ftp_password', 'ftp_root', 'ftp_upload_bandwidth', 'ftp_download_bandwidth', 'ftp_upload_ratio', 'ftp_download_ratio', 'ftp_max_files', 'ftp_max_mbytes', 'ftp_max_concurrent', 'ftp_allow_time');
		$_POST['ftp_root'] = '/home/wwwroot/' . $_POST['ftp_root'] . '/web';

		if (!is_dir($_POST['ftp_root']))
			Return ' 根目录不存在。';

		$get_ftp = $this -> get_ftp($_POST['ftp_name']);
		if (isset($get_ftp['ftp_name']))
			Return ' 已存在账号。';
			
		$cmd = 'amh ftp add';
		foreach ($data_name as $key=>$val)
			$cmd .= (isset($_POST[$val]) && !empty($_POST[$val])) ? ' ' . $_POST[$val] : ' 0 ';

		$cmd = Functions::trim_cmd($cmd);
		$result = shell_exec($cmd);
		Return Functions::trim_result($result);
	}

	// ftp更新列表
	function ftp_update($ftp_list_ssh)
	{
		$data_name = array('ftp_name', 'ftp_password', 'ftp_root', 'ftp_upload_bandwidth', 'ftp_download_bandwidth', 'ftp_upload_ratio', 'ftp_download_ratio', 'ftp_max_files', 'ftp_max_mbytes', 'ftp_max_concurrent', 'ftp_allow_time');
		$all_ftp_name = array();

		foreach ($ftp_list_ssh as $key=>$val)
		{
			list($ftp_name,$ftp_password,$uid,$gid,$gecos,$ftp_root,$ftp_upload_bandwidth,$ftp_download_bandwidth,$ftp_upload_ratio,$ftp_download_ratio,$ftp_max_concurrent,$ftp_max_files,$ftp_max_mbytes,$authorized_local_IPs,$refused_local_IPs,$authorized_client_IPs,$refused_client_IPs,$ftp_allow_time) = explode(':', $val);

			if (!empty($ftp_name))
			{
				$all_ftp_name[] = $ftp_name;
				$ftp_root = rtrim($ftp_root , './');
				foreach ($data_name as $key=>$val)
				{
					$data[$val] = $$val;
					if(empty($data[$val])) $data[$val] = '';
				}
				
				$get_ftp = $this -> get_ftp($ftp_name);
				if (isset($get_ftp['ftp_name']))
				{
					unset($data['ftp_password']);
					$this -> _update('amh_ftp', $data, " WHERE ftp_name = '$ftp_name' ");
				}
				else
				{
					$data['ftp_type'] = 'ssh';
					$this -> ftp_insert($data);
				}
			}
		}

		if(count($all_ftp_name) > 0)
		{
			$sql = "DELETE FROM amh_ftp WHERE ftp_name NOT IN ('" . implode("','", $all_ftp_name) . "')";
			$this -> _query($sql);
		}
		else
		{
		    $sql = "TRUNCATE TABLE `amh_ftp`";
			$this -> _query($sql);
		}
	}

	// 编辑ftp
	function edit_ftp()
	{

		if($_POST['ftp_root'] == 'index' || strpos($_POST['ftp_root'], '..') !== false || strpos($_POST['ftp_root'], '/') !== false ) 
			Return ' 禁止使用的根目录。';

		$data_name = array('ftp_name', 'ftp_password', 'ftp_root', 'ftp_upload_bandwidth', 'ftp_download_bandwidth', 'ftp_upload_ratio', 'ftp_download_ratio', 'ftp_max_files', 'ftp_max_mbytes', 'ftp_max_concurrent', 'ftp_allow_time');
		$_POST['ftp_root'] = '/home/wwwroot/' . $_POST['ftp_root'] . '/web';

		if (!is_dir($_POST['ftp_root']))
			Return ' 根目录不存在。';


		$cmd = 'amh ftp edit';
		foreach ($data_name as $key=>$val)
			$cmd .= (isset($_POST[$val]) && !empty($_POST[$val])) ? ' ' . $_POST[$val] : (isset($_POST['_'.$val]) ? ' - ' : ' 0 ');

		$cmd = Functions::trim_cmd($cmd);
		$result_change = Functions::trim_result(shell_exec($cmd));

		if (!empty($_POST['ftp_password']))
		{
			$cmd = 'amh ftp pass ' . $_POST['ftp_name'] . ' ' . $_POST['ftp_password'];
			$cmd = Functions::trim_cmd($cmd);
			$result_pass = Functions::trim_result(shell_exec($cmd));
			if (strpos($result_pass, '[OK]') !== false)
			{
				$data['ftp_password'] = md5(md5($_POST['ftp_password']));
				$this -> _update('amh_ftp', $data, " WHERE ftp_name = '$_POST[ftp_name]' ");
			}
		}
		Return array($result_change, $result_pass);
	}


	// 删除ftp ssh
	function ftp_del_ssh($del_name)
	{
		$cmd = "amh ftp del $del_name";
		$cmd = Functions::trim_cmd($cmd);
		Return Functions::trim_result(shell_exec($cmd));
	}

}

?>