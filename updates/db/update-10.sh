#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "adding nat preferences table"

$QUERY "insert into telecube.preferences (name, value) values ('pbx_nat_is_natted','yes');"

$QUERY "insert into telecube.preferences (name, value) values ('pbx_nat_public_ip_static','yes');"

EXTERNALIP=$(wget https://www.telecube.com.au/api/apps/ip-check.php -q -O -)

$QUERY "insert into telecube.preferences (name, value) values ('pbx_nat_external_ip','$EXTERNALIP');"

$QUERY "insert into telecube.preferences (name, value) values ('pbx_nat_localnet','192.168.0.0/16');"

echo "Done."
