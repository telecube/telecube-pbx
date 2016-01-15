#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding routing columns to trunks"

$QUERY "ALTER TABLE telecube.trunks ADD def_inbound_type varchar(16) NOT NULL;"
$QUERY "ALTER TABLE telecube.trunks ADD def_inbound_id varchar(16) NOT NULL;"

echo "Done."
