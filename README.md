nginxpanel
==========
Lnmp虚拟主机控制面板简介<br>
01）此虚拟主机控制面板是由<a href="http://www.c3crm.com" target="_blank">易客CRM</a>开发团队基于amh针对lnmp0.7一键安装包开发的面板，目前在Redhat和Centos下测试正常。<br>
02) 简单: 简洁精致，支持ssh、web在线轻松管理维护虚拟主机、MySQL、FTP。<br>
03) 高效: 使用高性能Nginx服务器软件支持，面板基于AMH命令行运行，实现过程快速高效。<br>
04) 独立: 简易全局管理与及支持不同主机进行独立运行维护。<br>
05) 备份: 数据无忧保护，支持即时、定时、本地、远程FTP/SSH 备份web程序和数据库数据。<br>
06) 任务: 周全的在线任务计划设置管理，定时执行AMH各项命令。<br>
07) 免费: 开源、免费、自由、共享，遵循和amh一样的GPL协议。<br>
08）面板还可以和dnspod集成，增加虚拟主机时可以自动绑定域名。<br>
<br>
安装步骤：<br>
1）首先安装lnmp0.7,理论上高版本也可以正常运行。<br>
2）使用root用户登录linux，默认进入/root目录下。<br>
2）下载nginxpanel安装包(下载地址：<a href="http://vdisk.weibo.com/s/FVsZk" target="_blank">http://vdisk.weibo.com/s/FVsZk</a>)，例如nginxpanel.zip,解压缩在/root/nginxpanel目录下。<br>
3）cd /root/nginxpanel<br>
4）chmod 777 amh.sh<br>
5）./amh.sh 并按照提示输入mysql密码，amh密码和wwwroot所在目录。<br>
6）即可通过浏览器访问http://ip/amh/访问面板。<br>
7）面板相关的配置文件是/root/amh/config.conf 和 /home/wwwroot/amh/Amysql/Config.php 。<br>
8）管理虚拟主机面板的shell脚本在/root/amh目录下，相关的php代码在/home/wwwroot/amh目录下。<br>
