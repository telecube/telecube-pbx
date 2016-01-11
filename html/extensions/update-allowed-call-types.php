<?php
require("../init.php");

$pk_parts = explode("-", $_POST["pk"]);
$ext = $pk_parts[0];
$trunk = $pk_parts[1];
$value = $_POST["value"];

echo $ext."\n";
echo $trunk."\n";
echo json_encode($value)."\n";


// get the extension routing
//$q = "select routing from sip_devices where name = ?;";
//$res = $Db->pdo_query($q, array($ext), $dbPDO);
//$routing = json_decode($res[0]['routing'], true);

$routing = $Ext->get_routing($ext);
if($routing === false){
	$q = "select id, name from trunks;";
	$routing = $Db->query($q,array(),$dbPDO);
}

//$Common->ecco($routing);
//header("HTTP/1.0 400 Bad Request");
//exit;

// loop through until we find trunk x
$j = count($routing);
for($i=0;$i<$j;$i++) { 
	if($routing[$i]['id'] == $trunk){
		$routing[$i]['allowed'] = $value;
		break;
	}
}

$Ext->set_routing($ext, $routing);

//$q = "update sip_devices set routing = ? where name = ?;";
//$res = $Db->pdo_query($q, array(json_encode($routing), $ext), $dbPDO);

echo "OK";

// update the allowed



//$Common->ecco($value);




//$Common->ecco($_POST);

//header("HTTP/1.0 400 Bad Request");

?>