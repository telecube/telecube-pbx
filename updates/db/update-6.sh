#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "Adding the host ip to the preferences table"

HOST_IP=$(ifconfig | awk -F':' '/inet addr/&&!/127.0.0.1/{split($2,_," ");print _[1]}')
arr=$(echo $HOST_IP | tr " " "\n")
for x in $arr
do
   ASTERISK_IP=$x
   break
done

$QUERY "insert into telecube.preferences (name, value) values ('pbx_host_ip','$ASTERISK_IP');"

echo "Done."
