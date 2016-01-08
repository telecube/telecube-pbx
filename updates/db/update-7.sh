#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding default timezone"

$QUERY "insert into telecube.preferences (name, value) values ('pbx_default_timezone','Australia/Melbourne');"

echo "Done."
