#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "updating insecure field"

$QUERY "update telecube.sip_devices set insecure = 'port';"


echo "Done."


