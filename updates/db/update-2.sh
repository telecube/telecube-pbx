#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "add a temp table for testing"

# create update log db
$QUERY "CREATE TABLE IF NOT EXISTS telecube.test (
	id int(10) unsigned NOT NULL auto_increment,
	datetime datetime NOT NULL,
	data1 text NOT NULL,
	data2 text NOT NULL,
	PRIMARY KEY  (id)
);"

echo "Done."

