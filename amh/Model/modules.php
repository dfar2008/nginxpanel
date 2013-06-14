<?php

class modules extends AmysqlModel
{
	
	// 取得模块列表数据
	function get_module_list_data($page = 1, $page_sum = 5)
	{
		$cmd = 'amh ls_modules';
		$result = trim(shell_exec($cmd), "\n");
		
		if (empty($result))
			Return array('data' => array(), 'sum' => 0);

		$run_list = explode("\n", $result);
		$sum = count($run_list);
		$run_list = array_slice($run_list, ($page-1)*$page_sum,  $page_sum);

		$param_arr = array(
			'AMH-ModuleName',
			'AMH-ModuleDescription',
			'AMH-ModuleButton',
			'AMH-ModuleDate',
			'AMH-ModuleAdmin',
			'AMH-ModuleWebSite',
			'AMH-MoudleScriptBy',
		);

		if (is_array($run_list))
		{
			foreach ($run_list as $key=>$val)
			{
				// Module Info
				$cmd = "amh module $val info";
				$cmd = Functions::trim_cmd($cmd);
				$result = trim(shell_exec($cmd), "\n");
				$result = Functions::trim_result($result);
				foreach ($param_arr as $k=>$v)
				{
					preg_match("/{$v}:(.*)/", $result, $param_value);
					$arr[$v] = trim($param_value[1]);
				}
				
				// Module Status
				$cmd = "amh module $val status";
				$cmd = Functions::trim_cmd($cmd);
				exec($cmd, $tmp, $status);
				$arr['AMH-ModuleStatus'] = ($status) ? 'false' : 'true';
				
				$arr['AMH-ModuleName'] = addslashes($arr['AMH-ModuleName']);
				$arr['AMH-ModuleButton'] = explode('/', $arr['AMH-ModuleButton']);
				if ($arr['AMH-ModuleStatus'] == 'true')
				{
					$arr['AMH-ModuleButton'] = $arr['AMH-ModuleButton'][1];
					$arr['AMH-ModuleAction'] = 'uninstall';
				}
				else
				{
					$arr['AMH-ModuleButton'] = $arr['AMH-ModuleButton'][0];
					$arr['AMH-ModuleAction'] = 'install';
				}

				$data[] = $arr;
			}
		}
		
		Return array('data' => $data, 'sum' => $sum);
	}

	// 下载模块
	function module_download($name)
	{
		$cmd = "amh module download $name";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}

}

?>