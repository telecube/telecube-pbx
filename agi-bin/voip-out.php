#!/usr/bin/php -q
<?php
include("init.php");

// get the routing profile for this extension
$routing = $Exten->get_routing($peername);

$callto_type = $Common->callto_type($dnid);

if($callto_type == "voip"){
	$Agi->dial($dnid, 120, "SIP");
}else{
	// try the trunks in order
	$j = count($routing);
	for($i=0;$i<$j;$i++) { 
		// check the trunk is active
		if($routing[$i]['status'] != "active") continue;

		// check the allowed call types
		if(in_array($callto_type, $routing[$i]['allowed']) || in_array("all", $routing[$i]['allowed'])){
			
			$Agi->dial($dnid."@".$routing[$i]['name'], 120, "SIP");
			$dialstat = $Agi->get_variable("DIALSTATUS",true);
			$Agi->verbose($dialstat);
			// break out of the loop if the call ended normally
			if($dialstat != "CHANUNAVAIL" && $dialstat != "CONGESTION"){
				break;
			}
		}
	}	
}






?>