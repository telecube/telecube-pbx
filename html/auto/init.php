<?php
// this script is run via phpcli so no server vars are available to it
if (PHP_SAPI !== 'cli') exit("Not allowed here..");

require(__DIR__."/../classes/_autoloader.php");
use Telecube\Common;
use Telecube\Config;
use Telecube\Curl;
use Telecube\Db;

$Config = new Config;
$Common = new Common;
$Curl 	= new Curl;
$Db 	= new Db;

try{
	$dbPDO = new PDO('mysql:dbname='.$Config->get("db_name").';host='.$Config->get("db_host").';port='.$Config->get("db_port"), $Config->get("db_user"), $Config->get("db_pass"));
} catch(PDOException $ex){
	exit( 'Connection failed: ' . $ex->getMessage() );
}
$dbPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );




?>