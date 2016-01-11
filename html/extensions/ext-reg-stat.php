<?php
require("../init.php");

$ext_stat = array("status"=>"OK");

$q = "select name, port, regseconds from sip_devices;";
$res = $Db->pdo_query($q, array(), $dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
	$ext_stat["exts"][] = $res[$i]['name'];
	//if($res[$i]['port'] > 0 && $res[$i]['regseconds'] > date("U")){
	if($Asterisk->ext_status($res[$i]['name']) > 0){
		//$ext_stat[$res[$i]['name']] = "yes";
		$ext_stat["data"][$res[$i]['name']] = "yes";
	}else{
		//$ext_stat[$res[$i]['name']] = "no";
		$ext_stat["data"][$res[$i]['name']] = "no";
	}
}

echo json_encode($ext_stat);

?>