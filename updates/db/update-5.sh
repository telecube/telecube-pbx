#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "seeding the updates next check time in preferences table"

$QUERY "insert into telecube.preferences (name, value) values ('update_wait_count','1');"

echo "Done."
