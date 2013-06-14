<?php

class backup extends AmysqlController
{
	public $indexs = null;
	public $backups = null;
	public $notice = null;
	public $top_notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> backups = $this ->  _model('backups');
	}


	function IndexAction()
	{
		$this -> backup_list();
	}
	
	function backup_list()
	{
		$this -> title = 'AMH - Backup';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$this -> status = 'error';
		$category = isset($_GET['category']) ? $_GET['category'] : 'backup_list';
		$category_array = array('backup_list', 'backup_remote',  'backup_now', 'backup_revert');
		if (!in_array($category, $category_array)) $category = 'backup_list';

		$input_item = array('remote_type', 'remote_status', 'remote_ip', 'remote_path', 'remote_user', 'remote_password');
	
		if ($category == 'backup_list')
		{
			$this -> title = 'AMH - Backup - 备份列表';
			
			if (isset($_GET['del']))
			{
				$del_id = (int)$_GET['del'];
				$del_info = $this -> backups -> get_backup($del_id);
				if (isset($del_info['backup_file']))
				{
					$file = str_replace('.amh', '', $del_info['backup_file']);
					$cmd = "amh rm_backup $file";
					$cmd = Functions::trim_cmd($cmd);
					$result = shell_exec($cmd);
					$this -> status = 'success';
					$this -> notice = "删除备份文件({$file}.amh)执行完成。";
				}
				
			}

			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$page_sum = 20;

			$this -> backups -> backup_list_update();
			$backup_list = $this -> backups -> get_backup_list($page, $page_sum);

			$total_page = ceil($backup_list['sum'] / $page_sum);						
			$page_list = Functions::page('BackupList', $backup_list['sum'], $total_page, $page, 'c=backup&a=backup_list&category=backup_list');		// 分页列表

			global $Config;
			$Config['XSS'] = false;
			$this -> page = $page;
			$this -> total_page = $total_page;
			$this -> backup_list = $backup_list;
			$this -> page_list = $page_list;
		}
		elseif ($category == 'backup_remote')
		{
			$this -> title = 'AMH - Backup - 远程设置';

			// 连接测试
			if (isset($_GET['check']))
			{
				$id = (int)$_GET['check'];
				$data = $this -> backups -> get_backup_remote($id);
				if($data['remote_type'] == 'FTP')
					$cmd = "amh BRftp check $id";
				else
					$cmd = "amh BRssh check $id";
				$cmd = Functions::trim_cmd($cmd);
				$result = shell_exec($cmd);
				$result = trim(Functions::trim_result($result), "\n ");
				echo $result;
				exit();
			}
			// 保存远程配置
			if (isset($_POST['save']))
			{
				$save = true;
				foreach ($input_item as $val)
				{
					if(empty($_POST[$val]))
					{
						$this -> notice = '新增远程备份配置失败，请填写完整数据，*号为必填项。';
						$save = false;
					}
				}
				if($save)
				{
					$id = $this -> backups -> backup_remote_insert();
					if ($id)
					{
						$this -> status = 'success';
						$this -> notice = 'ID:' . $id . ' 新增远程备份配置成功。';
						$_POST = array();
					}
					else
						$this -> notice = ' 新增远程备份配置失败。';
				}
			}

			// 删除远程配置
			if (isset($_GET['del']))
			{
				$id = (int)$_GET['del'];
				if(!empty($id))
				{
					$result = $this -> backups -> backup_remote_del($id);
					if ($result)
					{
						$this -> status = 'success';
						$this -> notice = 'ID:' . $id . ' 删除远程备份配置成功。';
					}
					else
						$this -> notice = 'ID:' . $id . ' 删除远程备份配置失败。';
				}
			}

			// 编辑远程配置
			if (isset($_GET['edit']))
			{
				$id = (int)$_GET['edit'];
				$_POST = $this -> backups -> get_backup_remote($id);
				if($_POST['remote_id'])
				{
					$this -> edit_remote = true;
				}
			}

			// 保存编辑远程配置
			if (isset($_POST['save_edit']))
			{
				$id = $_POST['remote_id'] = (int)$_POST['save_edit'];
				$save = true;
				foreach ($input_item as $val)
				{
					if(empty($_POST[$val]) && $val != 'remote_password')
					{
						$this -> notice = 'ID:' . $id . ' 编辑远程备份配置失败。*号为必填项。';
						$save = false;
						$this -> edit_remote = true;
					}
				}
				if ($save)
				{
					$result = $this -> backups -> backup_remote_update();
					if ($result)
					{
						$this -> status = 'success';
						$this -> notice = 'ID:' . $id . ' 编辑远程备份配置成功。';
						$_POST = array();
					}
					else
					{
						$this -> notice = 'ID:' . $id . ' 编辑远程备份配置失败。';
						$this -> edit_remote = true;
					}
				}
				
			}

			$this -> remote_list = $this -> backups -> backup_remote_list();
		}
		elseif ($category == 'backup_now')
		{
			$this -> title = 'AMH - Backup - 即时备份';

			if (isset($_POST['backup_now']))
			{
				$backup_retemo = ($_POST['backup_retemo'] == 'on') ? 'y' : 'n';
				$backup_password = (!empty($_POST['backup_password'])) ? $_POST['backup_password'] : 'n';
				$backup_comment = (!empty($_POST['backup_comment'])) ? $_POST['backup_comment'] : '';

				if ((!empty($_POST['backup_password2']) || !empty($_POST['backup_password'])) && $_POST['backup_password'] != $_POST['backup_password2'])
				{
					$this -> notice = ' 两次密码不一致，请确认。' ;
				}
				else
				{
				    $cmd = "amh backup $backup_retemo $backup_password $backup_comment";
					$cmd = Functions::trim_cmd($cmd);
					$result = shell_exec($cmd);
					$result = trim(Functions::trim_result($result), "\n ");
					if (strpos($result, '[OK]') !== false)
					{
						$this -> status = 'success';
						$this -> notice = $result . ' 已成功创建备份文件。';
						$_POST = array();
					}
					else
						$this -> notice = $result. ' 备份文件创建失败' ;
				}
			}
		}
		elseif ($category == 'backup_revert')
		{
			$this -> title = 'AMH - Backup - 一键还原';
			$this -> notice = ' 还原失败!' ;

//			$revert_id = isset($_GET['revert_id']) ? (int)$_GET['revert_id'] : '';
//			if (!empty($revert_id))
//				$revert = $this -> backups -> get_backup($revert_id);
//
//			if (isset($_POST['revert_submit']))
//			{
//				$backup_file = $revert['backup_file'];
//				$backup_password = empty($_POST['backup_password']) ? 'n' : $_POST['backup_password'];
//				$cmd = "amh revert $backup_file $backup_password noreload";
//				$cmd = Functions::trim_cmd($cmd);
//				$result = shell_exec($cmd);
//				$result = trim(Functions::trim_result($result), "\n ");
//				if (strpos($result, '[OK]') !== false)
//				{
//					$this -> status = 'success';
//					$this -> notice = $backup_file . ' 数据还原成功。';
//				}
//				else
//					$this -> notice = $result . ' ' . $backup_file . ' 还原失败。' ;
//
//			}
//			$this -> revert = $revert;
		}
		

		$this -> indexs -> log_insert($this -> notice);
		$this -> category = $category;
		$this -> _view('backup');
	}
		
}

?>