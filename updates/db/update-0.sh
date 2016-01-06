#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "Creating database table telecube.logging_updates"

# create update log db
$QUERY "CREATE TABLE IF NOT EXISTS telecube.logging_updates (
	id int(10) unsigned NOT NULL auto_increment,
	datetime datetime NOT NULL,
	update_type varchar(150) NOT NULL,
	log_text text NOT NULL,
	PRIMARY KEY  (id)
);"

echo "Done."
