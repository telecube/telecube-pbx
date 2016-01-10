#!/usr/bin/php -q
<?php
include("init.php");


$callto_type = $Common->callto_type($dnid);

if($callto_type == "voip"){
	$Agi->dial($dnid, 120, "SIP");
}

if($callto_type == "fixed"){
	$Agi->dial($dnid."@".$Trunk->get_active(), 120, "SIP");
}



?>