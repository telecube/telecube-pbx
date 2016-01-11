<?php
require("../init.php");

//echo "<pre>";
//print_r($_POST);
$tnknm = trim($_POST['trunk_name']);

// trunk name is used in the config as the identifier so it must be letters and numbers only and not contain any special characters
$trunk_name = trim($_POST['trunk_name']);
if(empty($trunk_name)){
	header("Location: /trunks/?err=Trunk name must not be empty!");
	exit;
}

$trunk_name = $Common->sanitise_trunk_name($_POST['trunk_name'], "_");

$q = "insert into trunks (datetime, name, auth_type, username, password, host_address, qualify, active) values (?,?,?,?,?,?,?,?);";
$data = array(date("Y-m-d H:i:s"), $trunk_name, $_POST['auth_type'], $_POST['trunk_auth_name'], $_POST['trunk_pass'], $_POST['trunk_url'], "yes", "no");
$res = $Db->query($q,$data,$dbPDO);

if(strpos(strtolower($res), "duplicate") !== false){
	header("Location: /trunks/?err=Trunk name exists!");
	exit;
}

$trunk_id = $dbPDO->lastinsertid();

// we need to add this to the end of the allowed list in the extensions
$q = "select name, routing from sip_devices;";
$res = $Db->query($q,array(),$dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
	$routing = json_decode($res[$i]['routing'], true);

	array_push($routing, array("id"=>$trunk_id, "name"=>$trunk_name, "allowed"=>"null"));

	$q2 = "update sip_devices set routing = ? where name = ?;";
	$res2 = $Db->pdo_query($q2, array(json_encode($routing), $res[$i]['name']), $dbPDO);
}

header("Location: /trunks/");
exit;






/*
// get all the trunks and reload the config files
$regStr = "; Register Strings\n";
$ipStr = "; IP Auth Peers\n";
$q = "select * from trunks;";
$res = $Db->query($q,array(),$dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
//	echo $res[$i]['name']."\n";
	if($res[$i]['auth_type'] == "password"){
		$regStr .= "register => ".$res[$i]['username'].":".$res[$i]['password']."@".$res[$i]['host_address']."\n";
	}
}

$regFp = "/etc/asterisk/sip-register.conf";
file_put_contents($regFp,$regStr);

$rel = `sudo /usr/sbin/asterisk -rx "sip reload"`;
//$reset = `sudo /usr/sbin/asterisk -rx "sip show peer $name load"`;

header("Location: /trunks/")
*/
?>