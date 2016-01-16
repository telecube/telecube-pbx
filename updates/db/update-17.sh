#!/bin/sh

MYSQLPASS=$(cat /opt/mysql_root_pass)
QUERY="mysql -u root -p$MYSQLPASS -e "

echo "Creating database table telecube.linehunt"

# create linehunt table
$QUERY "CREATE TABLE IF NOT EXISTS telecube.linehunt (
	id int(10) unsigned NOT NULL auto_increment,
	datetime datetime NOT NULL,
	name varchar(64) NOT NULL,
	data text NOT NULL,
	PRIMARY KEY  (id)
);"

echo "Done."
