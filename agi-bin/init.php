<?php
require("classes/_autoloader.php");
use Telecube\CubeAgi;
use Telecube\Common;
use Telecube\Db;

$Agi 	= new CubeAgi;
$Common = new Common;
$Db 	= new Db;

$calleridnum    = $Agi->request['agi_callerid'];
$callerid       = $Agi->request['agi_callerid'];
$calleridname   = $Agi->request['agi_calleridname'];
$channel        = $Agi->request['agi_channel'];
$uniqueid       = $Agi->request['agi_uniqueid'];
$peername 		= $Agi->get_variable("CHANNEL(peername)",true);
$useragent 		= $Agi->get_variable("CHANNEL(useragent)",true);
$peerip 		= $Agi->get_variable("CHANNEL(peerip)",true);
$eventid 		= str_replace(array(".","-"), "", $uniqueid);
$dnid        	= $Agi->request['agi_dnid'];


?>