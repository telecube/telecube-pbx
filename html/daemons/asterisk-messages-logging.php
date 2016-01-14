<?php
// this script is run via phpcli so no server vars are available to it
if (PHP_SAPI !== 'cli') exit("Not allowed here..");

require("init.php");

$sleep_seconds = 5;
$runasdaemon = true;

while (true) {

	// get the latest hash
	$q = "select log_hash from asterisk_messages_logs order by log_datetime desc limit 1;";
	$res = $Db->pdo_query($q, array(), $dbPDO);
	$lasthash = isset($res[0]['log_hash']) ? $res[0]['log_hash'] : "nohash";

	$nLines = 100;

	$file = escapeshellarg("/var/log/asterisk/messages"); // for the security concious (should be everyone!)
	$log = `tail -n $nLines $file`;
	$log = explode("\n", trim($log));

	$next_line_process = 0;

	if($lasthash != "nohash"){
		$j = count($log);
		for($i=0;$i<$j;$i++) { 
			$line 		= $log[$i];
			$linehash 	= md5($line);

			if($linehash == $lasthash){
				$next_line_process = $i;
			}
		}
	}

	$lasthashprocessed = null;
	$module_codes = array("chan_sip.c:","res_rtp_asterisk.c:","acl.c:","netsock2.c:");

	// loop through the logs list and process the lines
	$j = count($log);
	for($i=0;$i<$j;$i++) { 
		if($i <= $next_line_process) continue; // skip lines already processed

		$line 		= $log[$i];
		$linehash 	= md5($line);

		// if we already processed a duplicate line
		if($linehash == $lasthashprocessed) continue;

		// handle notices
		if(strpos($line, "NOTICE[")){
			$log_line_type = "NOTICE";
			$parts = explode("NOTICE[", $line);
		}

		// handle warnings
		if(strpos($line, "WARNING[")){
			$log_line_type = "WARNING";
			$parts = explode("WARNING[", $line);
		}

		// handle errors
		if(strpos($line, "ERROR[")){
			$log_line_type = "ERROR";
			$parts = explode("ERROR[", $line);
		}

		// let's clean the parts
		$thisdt = date("Y-m-d H:i:s",strtotime(trim(str_replace(array("[","]"), "", $parts[0])))) ;
		$thistxt = trim(str_replace($module_codes, "", substr($parts[1], strpos($parts[1], "]")+1)));

		$q = "insert into asterisk_messages_logs (datetime, log_datetime, log_type, log_hash, log_text, log_raw_text) values (?,?,?,?,?,?);";
		$data = array(date("Y-m-d H:i:s"), $thisdt, $log_line_type, $linehash, $thistxt, $line);
		$res = $Db->pdo_query($q, $data, $dbPDO);

		$lasthashprocessed = $linehash;
	}

	if(!isset($runasdaemon) || !$runasdaemon) break;

	if(isset($sleep_seconds) && $sleep_seconds > 0){
		sleep($sleep_seconds);
	}else{
		break;
	}
}

?>