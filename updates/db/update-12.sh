#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding routing field to extensions table"

$QUERY "ALTER TABLE telecube.sip_devices ADD register_status varchar(16) NOT NULL;"
$QUERY "ALTER TABLE telecube.trunks ADD register_status varchar(16) NOT NULL;"

echo "Done."

