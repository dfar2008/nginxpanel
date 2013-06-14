<?php

class index extends AmysqlController
{
	public $indexs = null;
	public $accounts = null;
	public $action_name = array('start' => '启动' , 'stop' => '停止' , 'reload' => '重载', 'restart' => '重启');
	public $notice = null;
	public $top_notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> accounts = $this ->  _model('accounts');
	}


	// Login
	function login()
	{
		$this -> title = 'AMH - Login';
		$this -> AmysqlModelBase();
		$amh_config = $this -> accounts -> get_amh_config();

		if (isset($_POST['login']))
		{
			$login_allow = $this -> indexs -> login_allow($amh_config);

			// 允许登录
			if($login_allow['status'])
			{
				$user = $_POST['user'];
				$password = $_POST['password'];
				$VerifyCode = $_POST['VerifyCode'];
				if ($amh_config['VerifyCode']['config_value'] == 'on' && strtolower($VerifyCode) != $_SESSION['VerifyCode'])
				{
					$this -> LoginError = '验证码错误，请重新输入。';
				}
				else
				{
					if(empty($user) || empty($password))
						$this -> LoginError = '请输入用户名与密码。';
					else
					{
						$user_id = $this -> indexs -> logins($user, $password);
						if($user_id)
						{
							$this -> indexs -> login_insert(1, $user);
							$_SESSION['amh_user_name'] = $user;
							$_SESSION['amh_user_id'] = $user_id;
							$_SESSION['amh_config'] = $amh_config;
							header('location:./');
							exit();
						}
						$_POST['password'] = '';
						$this -> LoginError = '账号或密码错误，登录失败。(' . ($login_allow['login_error_sum']+1) . '次)';
						$this -> login_error_sum = $login_allow['login_error_sum'];
						$this -> indexs -> login_insert(0, $user);
					}
				}
			}
			else
			{
			    $this -> LoginError = '登录出错已有' . $login_allow['login_error_sum'] . '次。当前禁止登录，下次允许登录时间:' . date('Y-m-d H:i:s', $login_allow['allow_time']);
			}
		}

		$this -> amh_config = $amh_config;
		$this -> _view('login');
		exit();
	}

	// Home
	function IndexAction()
	{
		$this -> title = 'AMH - Home';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$m = isset($_GET['m']) ? $_GET['m'] : '';
		$g = isset($_GET['g']) ? $_GET['g'] : '';

		if (!empty($m) && !empty($g) && in_array($m, array('host', 'php', 'nginx', 'mysql')) && in_array($g, array('start', 'stop', 'reload', 'restart')) ) 
		{
			if($m == "php" && $g != "stop") {
				$cmd = "amh php $g";
			}elseif($m == "nginx" && $g != "stop") {
				$cmd = "amh nginx $g";
			}else{
				$cmd = "amh $g";
			}
			$result = shell_exec($cmd);
			$result = Functions::trim_result($result);
			if (strpos($result, 'OK') !== false)
			{
				$this -> status = 'success';
				$this -> notice = "$m " . $this -> action_name[$g] . '成功。'.$result;
			}
			else
			{
			    $this -> status = 'error';
				$this -> notice = "$m " . $this -> action_name[$g] . '失败。'.$result;
			}
		}
		
		$this -> indexs -> log_insert($this -> notice);
		$this -> _view('index');
	}


	// INFO
	function infos()
	{
		$this -> AmysqlModelBase();
		Functions::CheckLogin();
		$cmd = "amh info";
		$result = shell_exec($cmd);
		$result = trim(Functions::trim_result($result), "\n ");
		$this -> infos = $result;
		$this -> _view('infos');
	}

	// phpinfo
	function phpinfo()
	{
		$this -> title = 'AMH - PHPINFO';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();
		$this -> _view('phpinfos');
	}
			
			

	// logout
	function logout()
	{
		$this -> title = 'AMH - Logout';
		$_SESSION['amh_user_name'] = null;
		$_SESSION['amh_user_id'] = null;
		$_COOKIE['LoginKey'] = '';
		$this -> _view('logout');
	}


	// AMH AJAX 
	function ajax()
	{
		$this -> AmysqlModelBase();
		Functions::CheckLogin();
		$html = file_get_contents('http://amysql.com/index.php?c=index&a=AMH&tag=ajax&V=3.1');
		$html = htmlspecialchars($html);
		$html = str_replace('[br]', '<br />', $html);
		$html = preg_replace('/\[url\]([a-z\_]+)\[\/url\]/i', '<a href="http://amysql.com/AMH.htm?tag=$1" target="_blank"> http://amysql.com/AMH.htm?tag=$1</a>', $html);
		echo $html;
		exit();
	}

}