<?php
#ini_set('display_errors', 1);
#ini_set('display_startup_errors', 1);
#error_reporting(E_ALL);

require("classes/_autoloader.php");
use Telecube\Asterisk;
use Telecube\Common;
use Telecube\Config;
use Telecube\Curl;
use Telecube\Db;
use Telecube\Ext;
use Telecube\Log;
use Telecube\Server;
use Telecube\Trunk;
$Config = new Config;


session_name($Config->get("session_name"));
session_start();

if(!isset($_SESSION["user"])){ 
	header("Location: /login.php?err=Session expired&reqpage=".$_SERVER['REQUEST_URI']);
	exit;
}

// if the user is required to change the password
if(isset($_SESSION["force_password_change"])){
	if($_SERVER['REQUEST_URI'] != "/password-change.php"){
		header("Location: /password-change.php");
		exit;
	}
}

try{
	$dbPDO = new PDO('mysql:dbname='.$Config->get("db_name").';host='.$Config->get("db_host").';port='.$Config->get("db_port"), $Config->get("db_user"), $Config->get("db_pass"));
} catch(PDOException $ex){
	exit( 'Connection failed: ' . $ex->getMessage() );
}
$dbPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$Asterisk 	= new Asterisk;
$Common 	= new Common;
$Curl 		= new Curl;
$Db 		= new Db;
$Ext 		= new Ext;
$Log 		= new Log;
$Server 	= new Server;
$Trunk 		= new Trunk;

date_default_timezone_set($Common->get_pref("pbx_default_timezone"));

// check if there are updates waiting
$update_wait_count = $Common->get_pref("update_wait_count");

?>