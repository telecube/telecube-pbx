#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding uuid to preferences table"

uuid=$(uuidgen)

$QUERY "insert into telecube.preferences (name, value) values ('pbx_uuid','$uuid');"

echo "Done."
