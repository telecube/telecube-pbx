#!/usr/bin/php -q
<?php
include("init.php");

$trunk_id = $Agi->request['agi_extension'];
//						$Agi->verbose("Trunk ID: ".$trunk_id);

// let the other end know we are progressing with the call
$Agi->progress();

// let's see if we can match a did




// get the default inbound routing for the trunk
$def_route = $Trunk->get_routing($trunk_id);
//						$Agi->verbose("Def Route: ".json_encode($def_route));

if($def_route["type"] == "extension"){
	$Agi->dial($def_route["id"], 120, "SIP");
}

/* Def Route: {type:linehunt,id:1} */
if($def_route["type"] == "linehunt"){
	// get the linehunt details
	$lhdata = $Linehunt->get_data($def_route["id"]);
//							$Agi->verbose("Def Route: ".json_encode($lhdata));
	$j = count($lhdata);
	for($i=0;$i<$j;$i++) { 
		$type 			= $lhdata[$i]['type'];
		$callto 		= trim($lhdata[$i]['id']);
		$timeout 		= $lhdata[$i]['timeout'];
		$trunk_order	= explode("|", $lhdata[$i]['trunk_order']);
//								$Agi->verbose("Type: ".$lhdata[$i]['type']);
//								$Agi->verbose("ID: ".$lhdata[$i]['id']);
//								$Agi->verbose("Timeout: ".$lhdata[$i]['timeout']);
		if($type == "extension"){
			$Agi->dial($callto, $timeout, "SIP");
			$dialstat = $Channel->dialstatus();
								$Agi->verbose("Dialstatus: ".$dialstat);
			if($dialstat == "ANSWER"){
				break;
			}
		}

		if($type == "external"){
			// get the trunks
			if($trunk_order[0] == "any"){
				$trunks = $Trunk->get_registered();
			}else{
				$trunks = array();
				$jj = count($trunk_order);
				for($ii=0;$ii<$jj;$ii++) { 
					$trunks[] = $Trunk->get_from_id($trunk_order[$ii]);
				}
			}
			$jj = count($trunks);
			for($ii=0;$ii<$jj;$ii++) { 
				$Agi->dial($callto."@".$trunks[$ii]['name'], $timeout, "SIP");
				$dialstat = $Channel->dialstatus();
									$Agi->verbose("Dialstatus: ".$dialstat);
				if($dialstat == "ANSWER"){
					break 2;
				}
			}

		}

	}

}


//$Agi->wait(3);
//$Agi->playback("hello-world","noanswer");
//$Agi->dial($callto, 120, "SIP");

?>