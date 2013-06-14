<?php

class mysqls extends AmysqlModel
{
	function databases()
	{
		$sql = "SHOW DATABASES";
		$result = mysql_query($sql);
		while ($rs = mysql_fetch_assoc($result))
		{
//			$DBname = $rs['Database'];
//			$sql = "SHOW TABLES FROM `$DBname` ";
//			$rs['sum'] = mysql_num_rows(mysql_query($sql));
//
//			$sql = "SHOW CREATE DATABASE `$DBname` ";
//			$collations = mysql_fetch_assoc(mysql_query($sql));
//			$collations = explode(' ', $collations['Create Database']);
//			$rs['collations'] = $collations[7];
			$data[] = $rs;
		}
		Return $data;
	}


	// 取得php配置参数值
	function get_mysql_param($param_list)
	{
		$cmd = "amh cat_my_cnf";
		$cmd = Functions::trim_cmd($cmd);
		$my_cnf = Functions::trim_result(shell_exec($cmd));
		foreach ($param_list as $key=>$val)
		{
			preg_match("/$val[1] = (.*)/", $my_cnf, $param_val);
			if ($val[1] == 'InnoDB_Engine')
			{
				$param_val[1] = preg_match("/innodb = OFF/", $my_cnf) ? 'Off' : 'On';
			}
			$param_list[$key][3] = $param_val[1];
		}
		Return $param_list;
	}

}

?>