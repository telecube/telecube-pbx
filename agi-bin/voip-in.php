#!/usr/bin/php -q
<?php
include("init.php");

$trunk_id = $Agi->request['agi_extension'];

$Agi->verbose("Trunk ID: ".$trunk_id);

$Agi->progress();
//$Agi->wait(3);
//$Agi->playback("hello-world","noanswer");
$Agi->dial("1000", 120, "SIP");

?>