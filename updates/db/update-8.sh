#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding unique index to trunk names"

$QUERY "ALTER TABLE telecube.trunks ADD UNIQUE KEY name (name);"

$QUERY "ALTER TABLE telecube.trunks ADD description text NOT NULL;"

echo "Done."
