<?php
require("../init.php");

$ext_stat = array("status"=>"OK");

$q = "select name, register_status from sip_devices;";
$res = $Db->pdo_query($q, array(), $dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
	$ext_stat["exts"][] = $res[$i]['name'];
	$ext_stat["data"][$res[$i]['name']] = $res[$i]['register_status'];
}

echo json_encode($ext_stat);
?>