#!/bin/sh

#
# This is the PBX installer script
# Only supports Ubuntu version(s) 14.04
# It will install the required packages and configure the server
# 

# Make sure only root can run this script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi
#

# test the os and version
. /etc/os-release
if [ "$NAME" != "Ubuntu" ] || [ "$VERSION_ID" != "14.04" ]; then
    echo "Sorry, this script only supports Ubuntu version 14.04"
    echo "You are on: $NAME $VERSION_ID"
    exit 1
fi


if !(whiptail --title "Telecube PBX Install" --yesno "This script will install the Telecube PBX, do you want to continue?" 10 60) then
    echo "Bye."
    exit 1
fi

# we have to make sure mysql, asterisk and nginx aren't already installed
ASTERISK_INSTALLED="$(dpkg-query -l | grep asterisk | wc -l)"
if [ "$ASTERISK_INSTALLED" != "0" ]; then
	echo "Asterisk is already installed!"
	echo "To continue you must remove it: apt-get remove -y asterisk && apt-get purge && apt-get autoremove -y"
	exit 1
fi
MYSQL_INSTALLED="$(dpkg-query -l | grep mysql-server | wc -l)"
if [ "$MYSQL_INSTALLED" != "0" ]; then
	echo "Mysql is already installed!"
	echo "To continue you must remove it: apt-get remove -y mysql-server && apt-get purge && apt-get autoremove -y"
	exit 1
fi
NGINX_INSTALLED="$(dpkg-query -l | grep nginx | wc -l)"
if [ "$NGINX_INSTALLED" != "0" ]; then
	echo "Nginx is already installed!"
	echo "To continue you must remove it: apt-get remove -y nginx && apt-get purge && apt-get autoremove -y"
	exit 1
fi

if [ ! -f /etc/apt/sources.list.d/nginx-stable.list ]; then
	echo "deb http://ppa.launchpad.net/nginx/stable/ubuntu $(lsb_release -sc) main" > /etc/apt/sources.list.d/nginx-stable.list 
	apt-key adv --keyserver keyserver.ubuntu.com --recv-keys C300EE8C 
fi

x=0
while true ; do
	if debconf-apt-progress -- aptitude -y update
		then 
			echo "done .."
			break
		else 
			echo "oops, trying again in a few seconds .."
			sleep 3
	fi
	
	x=$((x+1))
	if ["$x" = 30] ; then 
		echo "\n\n## ERROR! ##\nFailed to update!\n## ## ##\n"
		break 
	fi	
done


x=0
while true ; do

	if debconf-apt-progress -- aptitude -y install asterisk asterisk-dahdi asterisk-mysql asterisk-core-sounds-en-wav asterisk-moh-opsound-wav nginx php5 php5-fpm php5-mysql php5-curl git rsync
		then 
			echo "done .."
			break
		else 
			echo "oops, trying again in a few seconds .."
			sleep 3
	fi
	
	x=$((x+1))
	if ["$x" = 30] ; then 
		echo "\n\n## ERROR! ##\nFailed to install some critical packages!\n## ## ##\n"
		break 
	fi	
done

# generate a random 16 char str for mysql password
mysql_root_pass=$(openssl rand -base64 16)

x=0
while true ; do

	echo mysql-server mysql-server/root_password password $mysql_root_pass | sudo debconf-set-selections
	echo mysql-server mysql-server/root_password_again password $mysql_root_pass | sudo debconf-set-selections

	if debconf-apt-progress -- aptitude -y install mysql-server
		then 
			echo "done .."
			break
		else 
			echo "oops, trying again in a few seconds .."
			sleep 3
	fi
	
	x=$((x+1))
	if ["$x" = 30] ; then 
		echo "\n\n## ERROR! ##\nFailed to install some critical packages!\n## ## ##\n"
		break 
	fi	
done


# write the password to the config file in /opt so the control panel has access to the db
echo "<?php\n\$mysql_root_pass = \"$mysql_root_pass\";\n?>" > /opt/base_config.inc.php

# create the db and initial tables/values
mysql -u root -p"$mysql_root_pass" -e "create database telecube;"
mysql -u root -p"$mysql_root_pass" -e "CREATE TABLE telecube.preferences (name varchar(254), value varchar(254));"

mysql -u root -p"$mysql_root_pass" -e "insert into telecube.preferences (name, value) values ('pbx_login_username', 'admin');"
mysql -u root -p"$mysql_root_pass" -e "insert into telecube.preferences (name, value) values ('pbx_login_password', 'admin');"

mysql -u root -p"$mysql_root_pass" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
mysql -u root -p"$mysql_root_pass" -e "DELETE FROM mysql.user WHERE User=''"
mysql -u root -p"$mysql_root_pass" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
mysql -u root -p"$mysql_root_pass" -e "FLUSH PRIVILEGES"


# create certs folder
if [ ! -d /var/www/certs ]; then
	mkdir -p /var/www/certs
fi

# create self signed ssl certificate
if [ ! -f /var/www/certs/nginx.crt ]; then
	openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout /var/www/certs/nginx.key -out /var/www/certs/nginx.crt -subj "/C=AU/ST=Victoria/L=Melbourne/O=Telecube Pty Ltd/OU=IT Department/CN=telecube.com.au"
fi

# Find the line, cgi.fix_pathinfo=1, and change the 1 to 0.
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' /etc/php5/fpm/php.ini

# Start php5-fpm
service php5-fpm restart

# create a new config file
mv /etc/nginx/sites-available/default /etc/nginx/sites-available/default_BAK_$(date "+%Y-%m-%d-%H:%M:%S")

echo "# Default server configuration" > /etc/nginx/sites-available/default
echo "#" >> /etc/nginx/sites-available/default
echo "server {" >> /etc/nginx/sites-available/default
echo "        # configure ssl" >> /etc/nginx/sites-available/default
echo "        listen 443 ssl default_server;" >> /etc/nginx/sites-available/default
echo "        listen [::]:443 ssl default_server;" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # turn off gzip" >> /etc/nginx/sites-available/default
echo "        gzip off;" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # path to the certs" >> /etc/nginx/sites-available/default
echo "        ssl_certificate /var/www/certs/nginx.crt;" >> /etc/nginx/sites-available/default
echo "        ssl_certificate_key /var/www/certs/nginx.key;" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # doc root" >> /etc/nginx/sites-available/default
echo "        root /var/www/html;" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # add .php" >> /etc/nginx/sites-available/default
echo "        index index.php;" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # server name" >> /etc/nginx/sites-available/default
echo "        server_name _;" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # main rule" >> /etc/nginx/sites-available/default
echo "        location / {" >> /etc/nginx/sites-available/default
echo "                try_files \$uri \$uri/ =404;" >> /etc/nginx/sites-available/default
echo "        }" >> /etc/nginx/sites-available/default
echo "" >> /etc/nginx/sites-available/default
echo "        # main rule" >> /etc/nginx/sites-available/default
echo "        location ~ \.php$ {" >> /etc/nginx/sites-available/default
echo "                include snippets/fastcgi-php.conf;" >> /etc/nginx/sites-available/default
echo "                fastcgi_pass unix:/var/run/php5-fpm.sock;" >> /etc/nginx/sites-available/default
echo "        }" >> /etc/nginx/sites-available/default
echo "}" >> /etc/nginx/sites-available/default

service nginx restart

# check if the repo has been checked out and clone it if it hasn't
if [ -d /opt/telecube-pbx ]; then
	cd /opt/telecube-pbx
	git pull
else
	cd /opt
	git clone https://github.com/telecube/telecube-pbx.git
fi

rsync -av --delete /opt/telecube-pbx/html/ /var/www/html/

rsync -av --delete /opt/telecube-pbx/agi-bin /var/lib/asterisk/

echo "\n\n##############################"
echo "Done!"
echo "You can log in to your server at the following address(es)"

HOST_IP=$(ifconfig | awk -F':' '/inet addr/&&!/127.0.0.1/{split($2,_," ");print _[1]}')
arr=$(echo $HOST_IP | tr " " "\n")
for x in $arr
do
    echo "https://$x/login.php"
done

echo "Default username/password is admin/admin"
echo "##############################"


