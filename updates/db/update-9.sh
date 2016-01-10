#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding active status to trunks table"

$QUERY "ALTER TABLE telecube.trunks ADD active varchar(3) NOT NULL;"

echo "Done."

