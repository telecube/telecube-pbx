<?php
require("../init.php");

echo "hello";

if(file_exists("/opt/telecube-pbx")){
//	chdir('/opt/telecube-pbx');

	echo "<pre>";

	exec("sudo /usr/bin/git -C /opt/telecube-pbx/ status", $output, $return_var);
	print_r($output);

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/agi-bin /var/lib/asterisk/", $output, $return_var);
	print_r($output);

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/html/ /var/www/html/", $output, $return_var);
	print_r($output);

	echo "<pre>";

}else{
	echo "Folder /opt/telecube-pbx does not exist!";
}


?>