#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding index to asterisk_messages_logs datetime"

$QUERY "ALTER TABLE telecube.asterisk_messages_logs ADD KEY datetime (datetime);"

echo "Done."
