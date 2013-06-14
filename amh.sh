#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH

clear;
echo '================================================================';
echo ' [LNMP/Nginx] Amysql Host - AMH 3.1 ';
echo ' http://Amysql.com';
echo '================================================================';


# VAR ***************************************************************************************
SysName='';
SysBit='';
Cpunum='';
RamTotal='';
RamSwap='';
MysqlPass='';
AMHPass='';
RootDir='';
Domain=`ifconfig  | grep 'inet addr:'| grep -v '127.0.0.*' | cut -d: -f2 | awk '{ print $1}'`;

# Function List	*****************************************************************************
function CheckSystem()
{
	if [ $(id -u) != '0' ]; then
		echo '[Error] Please use root to install AMH.';
		exit;
	fi;
	egrep -i "red" /etc/issue && SysName='centos';
	egrep -i "centos" /etc/issue && SysName='centos';
	egrep -i "debian" /etc/issue && SysName='debian';
	egrep -i "ubuntu" /etc/issue && SysName='ubuntu';
	if [ "$SysName" == ''  ]; then
		echo '[Error] Your system is not supported install AMH.';
		exit;
	fi;

	SysBit='32';
	if [ `getconf WORD_BIT` == '32' ] && [ `getconf LONG_BIT` == '64' ]; then
		SysBit='64';
	fi;

	Cpunum=`cat /proc/cpuinfo |grep 'processor'|wc -l`;
	RamTotal=`free -m | grep 'Mem' | awk '{print $2}'`;
	RamSwap=`free -m | grep 'Swap' | awk '{print $2}'`;
	echo "Server ${Domain}";
	echo "${SysBit}Bit, ${Cpunum}*CPU, ${RamTotal}MB*RAM, ${RamSwap}MB*Swap";
	echo '================================================================';
	
	RamSum=$[$RamTotal+$RamSwap];
	if [ "$SysBit" == '32' ] && [ "$RamSum" -lt '250' ]; then
		echo -e "[Error] Not enough memory install AMH. \n(32bit system need memory: ${RamTotal}MB*RAM + ${RamSwap}MB*Swap > 250MB)";
		exit;
	elif [ "$SysBit" == '64' ];  then
		if [ "$RamSum" -lt '600' ]; then
			echo -e "[Error] Not enough memory install AMH. \n(64bit system need memory: ${RamTotal}MB*RAM + ${RamSwap}MB*Swap > 600MB)";
			if [ "$RamSum" -gt '250' ]; then
				echo "[Notice] Please use 32bit system.";
			fi;
			exit;
		fi;
	fi;
	
	if [ "$RamSum" -lt '380' ]; then
		PHPDisable='--disable-fileinfo';
	fi;
}

function InputDomain()
{
	if [ "$Domain" == '' ]; then
		echo '[Error] empty server ip.';
		read -p '[Notice] Please input server ip:' Domain;

		if [ "$Domain" == '' ]; then
			InputDomain;
		fi;
	fi;

	if [ "$Domain" != '' ]; then
		echo '[OK] Your server ip is:';
		echo $Domain;
	fi;
}

function InputMysqlPass()
{
	read -p '[Notice] Please input MySQL password:' MysqlPass;
	if [ "$MysqlPass" == '' ]; then
		echo '[Error] MySQL password is empty.';
		InputMysqlPass;
	else
		echo '[OK] Your MySQL password is:';
		echo $MysqlPass;
	fi;
}


function InputAMHPass()
{
	read -p '[Notice] Please input AMH password:' AMHPass;
	if [ "$AMHPass" == '' ]; then
		echo '[Error] AMH password empty.';
		InputAMHPass;
	else
		echo '[OK] Your AMH password is:';
		echo $AMHPass;
	fi;
}
function InputRootDir()
{
	read -p '[Notice] Please input wwwroot Dir:' RootDir;
	if [ "$RootDir" == '' ]; then
		echo '[Error] RootDir empty.';
		InputRootDir;
	else
		echo '[OK] Your RootDir is:';
		echo $RootDir;
	fi;
}


function InstallReady()
{
        wget http://amysql.com/file/AMH/3.1/bin/${SysName}${SysBit};
	cp ${SysName}${SysBit} /bin/amh;
	chmod 4775 /bin/amh;
        mv ./amh /${RootDir}/wwwroot/amh        
	mkdir -p /root/amh/;
	chmod +rw /root/amh;
        mv ./amhshell/* /root/amh/ 
	cd /root/amh/;
	chmod +x /root/amh/backup /root/amh/BRssh /root/amh/BRftp /root/amh/info /root/amh/SetParam /root/amh/host /root/amh/mysql /root/amh/nginx /root/amh/php;

	#sed -i "s/localhost/127.0.0.1/g" /${RootDir}/wwwroot/amh/Amysql/Config.php;
	sed -i "s/MysqlPassword/"$MysqlPass"/g" /${RootDir}/wwwroot/amh/Amysql/Config.php;
	sed -i "s/alidata/"$RootDir"/g" /${RootDir}/wwwroot/amh/Amysql/Config.php;
	sed -i "s/AMHPass/"$AMHPass"/g" /${RootDir}/wwwroot/amh/Amysql/Config.php;

	#sed -i "s/alidata/"$RootDir"/g" /${RootDir}/wwwroot/amh/Model/hosts.php;
	#sed -i "s/AMHPass/"$AMHPass"/g" /${RootDir}/wwwroot/amh/Model/hosts.php;
	#sed -i "s/alidata/"$RootDir"/g" /${RootDir}/wwwroot/amh/View/index.php;
        sed -i "s/alidata/"$RootDir"/g" /root/amh/vhost.conf;

	sed -i "s/alidata/"$RootDir"/g" /root/amh/config.conf;
	sed -i "s/MysqlPassword/"$MysqlPass"/g" /root/amh/config.conf;

	sed -i "s/MysqlPassword/"$MysqlPass"/g" /root/amh/backup;
	sed -i "s/MysqlPassword/"$MysqlPass"/g" /root/amh/BRssh;
	sed -i "s/MysqlPassword/"$MysqlPass"/g" /root/amh/BRftp;
	sed -i "s/MysqlPassword/"$MysqlPass"/g" /root/amh/host;

	sed -i "s/alidata/"$RootDir"/g" /root/amh/backup;
	sed -i "s/alidata/"$RootDir"/g" /root/amh/BRssh;
	sed -i "s/alidata/"$RootDir"/g" /root/amh/BRftp;
	sed -i "s/alidata/"$RootDir"/g" /root/amh/host;

	sed -i "s/AMHPass_amysql-amh/"$AMHPass"_amysql-amh/g" /root/amh/amh.sql;
	/usr/local/mysql/bin/mysql -u root -p$MysqlPass < /root/amh/amh.sql;
	echo '[OK] AMH is installed.';

}




# AMH Installing ****************************************************************************
if [ -s /usr/local/nginx ] && [ -s /usr/local/php ] && [ -s /usr/local/mysql ]; then
CheckSystem;
InputMysqlPass;
InputAMHPass;
InputRootDir;
InstallReady;
echo '================================================================';
	echo '[AMH] Congratulations, AMH 3.1 install completed.';
	echo "AMH Management: http://${Domain}/amh";
	echo 'User:admin';
	echo "Password:${AMHPass}";
	echo "MySQL Password:${MysqlPass}";
	echo '';
	echo '******* SSH Management *******';
	echo 'Host: amh host';
	echo 'PHP: amh php';
	echo 'Nginx: amh nginx';
	echo 'MySQL: amh mysql';
	echo 'Backup: amh backup';
	echo 'Info: amh info';
echo '================================================================';
else
	echo 'Sorry, You are required to install LNMP first.';
	echo 'Please contact us: http://www.lnmp.org';
fi;
