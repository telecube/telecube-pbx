#!/usr/bin/php -q
<?php
include("init.php");

$trunk_id = $Agi->request['agi_extension'];

$Agi->verbose("Trunk ID: ".$trunk_id);

// get the default inbound routing for the trunk
$def_route = $Trunk->get_routing($trunk_id);
if($def_route["type"] == "extension"){
	$callto = $def_route["id"];
}


$Agi->progress();
//$Agi->wait(3);
//$Agi->playback("hello-world","noanswer");
$Agi->dial($callto, 120, "SIP");

?>