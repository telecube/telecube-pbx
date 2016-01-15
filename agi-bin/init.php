<?php
require("classes/_autoloader.php");
use Telecube\CubeAgi;
use Telecube\Common;
use Telecube\Config;
use Telecube\Db;
use Telecube\Exten;
use Telecube\Trunk;

$Agi 	= new CubeAgi;
$Common = new Common;
$Config = new Config;
$Db 	= new Db;
$Exten 	= new Exten;
$Trunk 	= new Trunk;

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

try{
	$dbPDO = new PDO('mysql:dbname='.$Config->get("db_name").';host='.$Config->get("db_host").';port='.$Config->get("db_port"), $Config->get("db_user"), $Config->get("db_pass"));
} catch(PDOException $ex){
	exit( 'Connection failed: ' . $ex->getMessage() );
}
$dbPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

?>