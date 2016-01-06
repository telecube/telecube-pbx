#!/bin/sh

LOG="/opt/telecube-pbx/updates/sys/log.txt"

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "


$QUERY "select * from telecube.preferences where name = 'fw_whitelist_ips';"
$QUERY "select * from telecube.preferences where name = 'pbx_login_username';"
