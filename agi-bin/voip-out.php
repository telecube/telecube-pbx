#!/usr/bin/php -q
<?php
include("init.php");

// get the routing profile for this extension
$routing = $Exten->get_routing($peername);
$Agi->verbose("Routing: ".json_encode($routing));
$Agi->verbose("Routing: ".$routing[0]['allowed'][0]);
//$Agi->verbose("Routing 0: ".$routing->0->name));
/* 
Routing:[
		{id:45,status:,name:Awesome_Trunk,allowed:[fixed]},
		{id:42,status:,name:Trunk_3,allowed:[all,mobile]},
		{id:40,status:,name:Trunk_No_1,allowed:[fixed]}
	]
*/


$callto_type = $Common->callto_type($dnid);

if($callto_type == "voip"){
	$Agi->dial($dnid, 120, "SIP");
}

//if($callto_type == "fixed"){
	

// try the trunks in order
$j = count($routing);
for($i=0;$i<$j;$i++) { 
	// check the trunk is active
	if($routing[$i]['status'] != "active") continue;

	// check the allowed call types
	if(in_array($callto_type, $routing[$i]['allowed'])){
		
		$Agi->dial($dnid."@".$routing[$i]['name'], 120, "SIP");
		$dialstat = $Agi->get_variable("DIALSTATUS",true);
		$Agi->verbose($dialstat);
		// break out of the loop if the call ended normally
		if($dialstat != "CHANUNAVAIL" && $dialstat != "CONGESTION"){
			break;
		}
	
	}
}	



//}



?>