<?php

namespace Telecube;


class Server{

	function ami_eventmon_running(){
		$eventmon_str = "ami-scripts/event-handler.php";
		exec("ps aux | grep '$eventmon_str' | grep -v grep | awk '{ print $2 }' | head -1", $out);
		return $out[0] > 0 ? true : false;
	}










}
?>