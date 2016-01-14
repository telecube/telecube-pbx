#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "Creating database table telecube.asterisk_messages_logs"

# create update log db
$QUERY "CREATE TABLE IF NOT EXISTS telecube.asterisk_messages_logs (
	id int(10) unsigned NOT NULL auto_increment,
	datetime datetime NOT NULL,
	log_datetime datetime NOT NULL,
	log_type varchar(8) NOT NULL,
	log_hash varchar(32) NOT NULL,
	log_text text NOT NULL,
	log_raw_text text NOT NULL,
	PRIMARY KEY  (id)
);"

echo "Done."
