<?php
require("../init.php");

$res = array();

$trunks = $Trunk->list_trunks();
$j = count($trunks);
for($i=0;$i<$j;$i++) { 
	$res[$i]['id'] = $trunks[$i]["id"];
	$res[$i]['status'] = $Trunk->is_registered($trunks[$i]["id"]) ? "Registered" : "Not Registered";
	$res[$i]["btn-class"] = $Trunk->is_registered($trunks[$i]["id"]) ? "btn-success" : "btn-warning";
}

echo json_encode(array("status"=>"OK", "data"=>$res));


?>