<?php
require("../init.php");

// get the current pid for the process if it's running
$eventmon_str = "ami-scripts/event-handler.php";
exec("ps aux | grep '$eventmon_str' | grep -v grep | awk '{ print $2 }' | head -1", $out);
$pid = $out[0];

if($out[0] > 0){
	exec("sudo /usr/bin/pkill -f ami-scripts/event-handler.php");
}else{
	// start the service
	exec("/usr/bin/php /var/lib/asterisk/agi-bin/ami-scripts/event-handler.php > /dev/null &");
	header("Location: /manage-services/?status=ok&service=eventmon&msg=The service was restarted successfully.");
	exit;
}

// check it was killed
exec("ps aux | grep '$eventmon_str' | grep -v grep | awk '{ print $2 }' | head -1", $out1);
if($out1[0] > 0){
	// kill it properly
	exec("sudo /usr/bin/kill -9 `ps -ef|grep ami-scripts/event-handler.php|grep -v grep|awk '{print $2}'`");
}else{
	// restart it
	exec("/usr/bin/php /var/lib/asterisk/agi-bin/ami-scripts/event-handler.php > /dev/null &");
	header("Location: /manage-services/?status=ok&service=eventmon&msg=The service was restarted successfully.");
	exit;
}

// check it was killed
exec("ps aux | grep '$eventmon_str' | grep -v grep | awk '{ print $2 }' | head -1", $out2);
if($out2[0] > 0){
	// redirect with an error
	header("Location: /manage-services/?status=err&service=eventmon&msg=The service could not be restarted.");
}else{
	// restart it
	exec("/usr/bin/php /var/lib/asterisk/agi-bin/ami-scripts/event-handler.php > /dev/null &");
	header("Location: /manage-services/?status=ok&service=eventmon&msg=The service was restarted successfully.");
	exit;
}


?>