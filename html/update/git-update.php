<?php
require("../init.php");



if(file_exists("/opt/telecube-pbx")){
	chdir('/opt/telecube-pbx');

	exec("/usr/bin/git pull", $output, $return_var);
	print_r($output);

	exec("/usr/bin/rsync -av --delete /opt/telecube-pbx/agi-bin /var/lib/asterisk/", $output, $return_var);
	print_r($output);

	exec("/usr/bin/rsync -av --delete /opt/telecube-pbx/html/ /var/www/html/", $output, $return_var);
	print_r($output);
}else{
	echo "Folder /opt/telecube-pbx does not exist!";
}


?>