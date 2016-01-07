#!/usr/bin/php -q
<?php
include("init.php");



/* if this call is to a voip extension */
if($Common->is_voip_ext($dnid)){
	$Agi->dial($dnid, 120, "SIP");
}


?>