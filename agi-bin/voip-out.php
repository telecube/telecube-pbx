#!/usr/bin/php -q
<?php
include("init.php");


/* if this call is to a voip extension */
if($Common->is_voip_ext($dnid)){
	$Agi->dial($dnid, 120, "SIP");
}


// test 1
// test 2
// test 3
// test 4
?>