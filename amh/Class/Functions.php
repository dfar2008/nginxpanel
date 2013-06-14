<?php


class Functions
{
	
	// 过滤结果数据
	function trim_result($result)
	{
		$result = trim($result);
		$result = str_replace(
			array(
					'[LNMP/Nginx] Amysql Host - AMH 3.1',
					'http://Amysql.com',
					'============================================================='
			), '', $result);

		Return $result;
	}
	// 命令过滤
	function trim_cmd($cmd)
	{
		$cmd = str_replace(array(';', '&', '|', '`'), ' ', trim($cmd));
		$cmd = ereg_replace("[ ]{1,}", " ", $cmd);
		Return $cmd;
	}
	
	// 分页页码
	function page ($name, $total_num, $total_page, $page, $set_url = null)
	{
		$uri = explode('?', $_SERVER['REQUEST_URI']);
		$url = _Host . $uri[0] . '?';

		if (!empty($set_url))
			$url .= $set_url;
		else
			$url .= preg_replace("/[\&]{0,}page\=[0-9]+/i", '', $uri[1]);
		
		$data = NULL;
		$url_model = '<a id="$id" href="$url&page=$page">$txt</a>';
		$replace_name = array('$url', '$page', '$name', '$txt', '$id');

		if($page-3>0)
		{
			$start=$page-3;
			if($page+3<$total_page)	
				$end=$page+3;	
			else
			{
				if($total_page-6>0)
					$start=$total_page-6;
				else
					$start=1;
				$end=$total_page;
			}
		}
		else
		{
			$start=1;
			if($total_page<7)
				$end=$total_page;
			else
				$end=7;
		}		

		if($page>1)
			$data .= str_replace($replace_name, array($url, $page-1, $name, '<', ''), $url_model);

		if($start!=1)
			$data .= str_replace($replace_name, array($url, '1', $name, '1', ''), $url_model) . ' ...';

		for($i=$start;$i<=$end;$i++)
		{
			if ($i==$page)
				$data .= '&nbsp;' . str_replace($replace_name, array($url, $i, $name, $i, 'page_now'), $url_model) ;
			else
				$data .= '&nbsp;' . str_replace($replace_name, array($url, $i, $name, $i, ''), $url_model);
		}

		if($end!=$total_page)
			$data .= ' ... ' . str_replace($replace_name, array($url, $total_page, $name, $total_page, ''), $url_model) ;
		if($total_page > $page)
			$data .= '&nbsp;' . str_replace($replace_name, array($url, $page+1, $name, '>', ''), $url_model) ;

		Return str_replace('?&', '?', $data);
	}
	
	
	// Check Login
	function CheckLogin()
	{
		if (!isset($_SESSION['amh_user_name']) || empty($_SESSION['amh_user_name']))
		{
			header('location:./index.php?c=index&a=login');
			exit();
		}
	}
	
}

?>