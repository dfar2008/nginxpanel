<?php

// 系统基本配置 **********************************************

$Config['HttpPath'] = false;				// 是否开启 index.php/Controller/Action/name/value 模式
$Config['Filter'] = true;					// 是否过滤 $_GET、$_POST、$_COOKIE、$_FILES
$Config['XSS'] = true;						// 是否开启 XSS防范
$Config['SessionStart'] = true;				// 是否开启 SESSION
$Config['DebugPhp'] = false;				// 是否开启PHP运行报错信息
$Config['DebugSql'] = false;				// 是否开启源码调试Sql语句
$Config['CharSet'] = 'utf-8';				// 设置网页编码
$Config['UrlControllerName'] = 'c';			// 自定义控制器名称 例如: index.php?c=index
$Config['UrlActionName'] = 'a';				// 自定义方法名称 例如: index.php?c=index&a=IndexAction						


// 默认使用数据库配置 *****************************************

$Config['ConnectTag'] = 'default';				// Mysql连接标识 可同时进行多连接
$Config['Host'] = '127.0.0.1';					// Mysql主机地址
$Config['User'] = 'root';						// Mysql用户
$Config['Password'] = 'MysqlPassword';			// Mysql密码
$Config['DBname'] = 'amh';						// 数据库名称

$Config['DNSPOD_EMAIL'] = 'dfar2008@gmail.com';	// dnspod的用户名
$Config['DNSPOD_PSWORD'] = 'AMHPass';			// dnspod的密码
$Config['DNSPOD_DOMAINID'] = '1667849';			// dnspod主域名的域名id
$Config['ROOT_DIR'] = 'alidata';				// www所在的目录,默认是放在/alidata目录