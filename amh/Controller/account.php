<?php

class account extends AmysqlController
{
	public $indexs = null;
	public $accounts = null;
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


	function IndexAction()
	{
		$this -> account_my();
	}

	function account_my()
	{
		$this -> title = 'AMH - Account';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$this -> status = 'error';
		$category = isset($_GET['category']) ? $_GET['category'] : 'account_info';
		$category_array = array('account_info', 'account_config');
		if (!in_array($category, $category_array)) $category = 'account_info';
	
		if ($category == 'account_info')
		{
			$this -> login_list = $this -> accounts -> login_list();
			$this -> log_list = $this -> accounts -> log_list();

			if (isset($_POST['submit']))
			{
				$user_password = $_POST['user_password'];
				$new_user_password = $_POST['new_user_password'];
				$new_user_password2 = $_POST['new_user_password2'];
				$error = '';
				$this -> status = 'error';

				$status = $this -> indexs -> logins($_SESSION['amh_user_name'], $user_password);
				if ($status)
				{
					if(empty($new_user_password) || empty($new_user_password2))
						$error = '新密码与确认新密码不能为空。';
					elseif($new_user_password != $new_user_password2)
						$error = '新密码与确认新密码不一致。';
				}
				else
					$error = '旧密码错误。';

				if (empty($error))
				{
					$status = $this -> accounts -> change_pass($new_user_password);
					if($status)
					{
						$this -> status = 'success';
						$this -> notice = '更改密码成功。';
					}
					else
						$this -> notice = '更改密码失败。';
				}
				else
					$this -> notice = $error;
			}
		}
		elseif  ($category == 'account_config')
		{
			if (isset($_POST['submit']))
			{
				$_POST['LoginErrorLimit'] = (int)$_POST['LoginErrorLimit'];
				$_POST['AMHListen'] = (int)$_POST['AMHListen'];
				if(empty($_POST['LoginErrorLimit'])) $_POST['LoginErrorLimit'] = 1;
				if(!isset($_POST['HelpDoc'])) $_POST['HelpDoc'] = 'no';
				if(!isset($_POST['VerifyCode'])) $_POST['VerifyCode'] = 'no';

				$up_status = $this -> accounts -> up_amh_config();
				if($up_status)
				{
					$status = 'success';
					$this -> notice = '系统配置更改成功。';
				}
				else
					$this -> notice = '系统配置更改失败。';
			}

			$AMHDomain_text = ($_POST['AMHDomain'] == 'Off') ? $_SERVER['SERVER_ADDR'] : $_POST['AMHDomain'];
			if ($_POST['AMHListen'] != $_POST['AMHListen_old'] || ($_POST['AMHDomain'] != 'Off' && $_POST['AMHDomain'] != $_POST['AMHDomain_old']))
				$this -> notice .= "面板允许域或端口已更改，请使用 {$AMHDomain_text}:{$_POST['AMHListen']} 访问。";
			
			$amh_config = $this -> accounts -> get_amh_config();
			if($status == 'success')
			{
				$_SESSION['amh_config'] = $amh_config;
				$this -> status = $status;
			}
			$this -> amh_config = $amh_config;
			$this -> amh_domain_list = $this -> accounts -> get_amh_domain_list();
		}
		
		$this -> indexs -> log_insert($this -> notice);
		$this -> category = $category;
		$this -> _view('account');
	} 

}

?>