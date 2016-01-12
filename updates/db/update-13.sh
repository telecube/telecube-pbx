#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "add a event logging table"

# create update log db
$QUERY "CREATE TABLE IF NOT EXISTS telecube.ami_event_logs (
	id int(10) unsigned NOT NULL auto_increment,
	datetime datetime NOT NULL,
	event_type varchar(64) NOT NULL,
	peername varchar(128) NOT NULL,
	event_data text NOT NULL,
	PRIMARY KEY  (id)
);"

echo "kill the asmanager script"

/usr/bin/pkill -f ast-manager-event-handler.php

echo "Done."

