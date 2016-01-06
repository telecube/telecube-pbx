<?php
require("../init.php");

// system version
$system_version = $Common->get_set_version_pref("current_version_system", 0);
/*
if($system_version == 0){
	$Common->system_update('echo "www-data ALL=NOPASSWD: /bin/cat" >> /etc/sudoers.d/telecube-sudo',1);
}
// changing the value field to text
if($system_version <= 1){
	$Common->system_update("ALTER TABLE telecube.preferences MODIFY value TEXT NOT NULL;",2);
}

www-data ALL=NOPASSWD: /bin/cat
*/
?>