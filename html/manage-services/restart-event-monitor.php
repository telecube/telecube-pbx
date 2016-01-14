<?php
require("../init.php");

// check if it's running
if($Server->ami_eventmon_running()){
	exec("sudo /usr/bin/pkill -f ami-scripts/event-handler.php");
}else{
	// start the service
	exec("/usr/bin/php /var/lib/asterisk/agi-bin/ami-scripts/event-handler.php > /dev/null &");
	header("Location: /manage-services/?status=ok&service=eventmon&msg=The service was restarted successfully.");
	exit;
}

// check it was killed
if($Server->ami_eventmon_running()){
	// kill it properly
	exec("sudo /usr/bin/kill -9 `ps -ef|grep ami-scripts/event-handler.php|grep -v grep|awk '{print $2}'`");
}else{
	// restart it
	exec("/usr/bin/php /var/lib/asterisk/agi-bin/ami-scripts/event-handler.php > /dev/null &");
	header("Location: /manage-services/?status=ok&service=eventmon&msg=The service was restarted successfully.");
	exit;
}

// check it was killed
if($Server->ami_eventmon_running()){
	// redirect with an error
	header("Location: /manage-services/?status=err&service=eventmon&msg=The service could not be restarted.");
}else{
	// restart it
	exec("/usr/bin/php /var/lib/asterisk/agi-bin/ami-scripts/event-handler.php > /dev/null &");
	header("Location: /manage-services/?status=ok&service=eventmon&msg=The service was restarted successfully.");
	exit;
}

?>