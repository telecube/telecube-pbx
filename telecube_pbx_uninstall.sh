#!/bin/sh

#
# This is the PBX un-installer script
# Only supports Ubuntu version(s) 14.04
# It will un-install the packages and delete the server files
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


if !(whiptail --title "Telecube PBX Un-Install" --yesno "This script will delete the Telecube PBX and remove the associated packages, do you want to continue?" 10 60) then
    echo "Bye."
    exit 1
fi


# remove asterisk
apt-get remove -y asterisk asterisk-dahdi asterisk-mysql asterisk-core-sounds-en-wav asterisk-moh-opsound-wav nginx php5 php5-fpm php5-mysql php5-curl git rsync
apt-get purge && apt-get autoremove -y

apt-get purge -y asterisk*
apt-get purge -y nginx*
apt-get purge -y mysql*
apt-get purge -y php*


# remove nginx config and source list
rm -f /etc/apt/sources.list.d/nginx-stable.list
rm -R -f /var/www/*
rm -R -f /etc/nginx

# delete the old mysql data
rm -R -f /var/lib/mysql

rm -R -f /opt/telecube-pbx
rm -f /opt/base_config.inc.php

rm -R -f /var/lib/asterisk

echo "Done .. !"
