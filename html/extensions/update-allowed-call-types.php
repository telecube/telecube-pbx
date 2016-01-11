<?php
require("../init.php");

$pk_parts = explode("-", $_POST["pk"]);
$ext = $pk_parts[0];
$trunk = $pk_parts[1];
$value = $_POST["value"];

$routing = $Ext->get_routing($ext);
if($routing === false){
	$q = "select id, name from trunks;";
	$routing = $Db->query($q,array(),$dbPDO);
}

// loop through until we find trunk x
$j = count($routing);
for($i=0;$i<$j;$i++) { 
	if($routing[$i]['id'] == $trunk){
		$routing[$i]['allowed'] = $value;
		break;
	}
}

$Ext->set_routing($ext, $routing);

echo "OK";

?>