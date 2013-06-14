<?php

class mysql extends AmysqlController
{
	public $indexs = null;
	public $mysqls = null;
	public $notice = null;
	public $top_notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> mysqls = $this ->  _model('mysqls');
	}


	function IndexAction()
	{
		$this -> mysql_list();
	}

	function mysql_list()
	{
		$this -> title = 'AMH - MySQL';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		if (isset($_GET['ams']))
		{
			// 打开数据库列表
			if ($_GET['ams'] == 'OpenDatabaseJs')
			{
				header('Content-type: application/x-javascript');
				$open_database = isset($_SESSION['open_database_name']) && !empty($_SESSION['open_database_name']) ? true : false;
				$AmysqlHomeStatus = $open_database ? 'Normal' : 'Activate';
				$_AmysqlTabJson = "var _AmysqlTabJson = [";
				$_AmysqlTabJson .= "{'type':'" . $AmysqlHomeStatus . "','id':'AmysqlHome','name':'AmysqlHome - localhost', 'url': '" . _Http . "ams/index.php?c=ams&a=AmysqlHome'}";
				if($open_database)
				{
					$ODN = $_SESSION['open_database_name'];
					$_AmysqlTabJson .= ", {'type':'Activate','id':'AmysqlDatabase_" . $ODN . "','name':'" . $ODN ."', 'url': '" . _Http . "ams/index.php?c=ams&a=AmysqlDatabase&DatabaseName=" . $ODN ."'}";
				}
				$_AmysqlTabJson .= "];";
				echo $_AmysqlTabJson;
				exit();
			}
			elseif ($_GET['ams'] == 'OpenCreate')
			{
				header('Content-type: application/x-javascript');
				if (isset($_SESSION['create_database']) && !empty($_SESSION['create_database']))
					echo " AddEvent({'load':function () { NavigationObject.ActiveSet(NavigationObject.Item['N_DatabaseAdd']);} },window); ";
				exit();
			}
			elseif ($_GET['ams'] == 'index')
			{
				$_SESSION['open_database_name'] = null;
				$_SESSION['create_database'] = null;
			}
			elseif ($_GET['ams'] == 'database')
			{
			    if (!empty($_GET['name']))
					$_SESSION['open_database_name'] = $_GET['name'];
				$_SESSION['create_database'] = null;
			}
			elseif ($_GET['ams'] == 'create')
			{
				$_SESSION['open_database_name'] = null;
				$_SESSION['create_database'] = 'yes';
			}
			header('location:./ams/');
			exit();
		}
		$this -> databases = $this -> mysqls -> databases();
		$this -> _view('mysql');
	} 


	function mysql_setparam()
	{
//		$this -> title = 'AMH - MySQL - SetParam';
//		$this -> AmysqlModelBase();
//		Functions::CheckLogin();
//
//		$param_list = array(
//			array('设置是否开启InnoDB引擎','InnoDB_Engine', 'On / Off'),
//			array('MyISAM索引缓冲区大小','key_buffer_size', '16M'),
//			array('客户端/服务器之间通信缓存区最大值','max_allowed_packet', '1M'),
//			array('设置打开表数目最大缓存值','table_open_cache', '64'),
//			array('设置每一个连接缓存一次性分配的内存','sort_buffer_size', '512K'),
//			array('TCP/IP和套接字通信缓冲区大小','net_buffer_length', '8K'),
//		);
//
//		if (isset($_POST['submit']))
//		{
//			foreach ($param_list as $key=>$val)
//			{
//				$post_keyname = str_replace('.', '_', $val[1]);
//				$cmd = "amh SetParam mysql $val[1] {$_POST[$post_keyname]}";
//				$cmd = Functions::trim_cmd($cmd . ' noreload');		// 只更改参数不重启
//				$result = Functions::trim_result(shell_exec($cmd));
//			}
//
//			if (strpos($result, '[OK]') !== false)
//			{
//				$this -> status = 'success';
//				$this -> notice = 'MySQL配置更改成功。';
//			}
//			else
//			{
//				$this -> status = 'error';
//				$this -> notice = 'MySQL配置更改失败。';
//			}
//		}
//		
//		
//		$param_list = $this -> mysqls -> get_mysql_param($param_list);
//
//		$this -> param_list = $param_list;
//		$this -> _view('mysql_setparam');

	}

}

?>