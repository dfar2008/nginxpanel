<?php

class module extends AmysqlController
{
	public $indexs = null;
	public $modules = null;
	public $notice = null;
	public $top_notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> modules = $this ->  _model('modules');
	}


	function IndexAction()
	{
		$this -> module_list();
	}

	// 模块管理
	function module_list()
	{
		$this -> title = 'AMH - Module';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		if (isset($_GET['action']) && isset($_GET['name']))
		{
			$name = $_GET['name'];
			$action = $_GET['action'];
			$action_list = array('install' => '安装' , 'uninstall' => '卸载', 'delete' => '删除');
			$result = '';
			if (isset($action_list[$action]))
			{
				$cmd = "amh module $name $action y";
				$cmd = Functions::trim_cmd($cmd);
				$result = trim(shell_exec($cmd), "\n");
				$result = Functions::trim_result($result);
			}

			if (strpos($result, '[OK]') !== false && strpos($result, '[Error]') == false)
			{
				$this -> status = 'success';
				$this -> notice = "$name {$action_list[$action]}成功。";
			}
			else
			{
				$this -> status = 'error';
				$this -> notice = "$name {$action_list[$action]}失败。";
			}
		}

		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$page_sum = 5;
		
		$get_module_list_data = $this -> modules -> get_module_list_data($page, $page_sum);
		$total_page = ceil($get_module_list_data['sum'] / $page_sum);						
		$page_list = Functions::page('ModuleList', $get_module_list_data['sum'], $total_page, $page, 'c=module&a=module_list');		// 分页列表
		
		global $Config;
		$Config['XSS'] = false;
		$this -> page = $page;
		$this -> total_page = $total_page;
		$this -> page_list = $page_list;
		$this -> module_list_data = $get_module_list_data;

		$this -> indexs -> log_insert($this -> notice);
		$this -> _view('module_list');
	}

	// 下载模块
	function module_down()
	{
		$this -> title = 'AMH - Module';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		if (isset($_POST['download_submit']))
		{
			$module_name = $_POST['module_name'];
			if (!empty($module_name))
			{
				if($this -> modules -> module_download($module_name))
				{
					$this -> status = 'success';
					$this -> notice = "模块下载成功：$module_name";
				}
				else
				{
				    $this -> status = 'error';
					$this -> notice = "模块下载失败：$module_name";
				}
			}
			else
			{
			    $this -> status = 'error';
				$this -> notice = "请输入模块名字。";
			}
		}

		$this -> indexs -> log_insert($this -> notice);
		$this -> _view('module_down');
	}


}

?>