<?php
require("../init.php");

$res = array();

$trunks = $Trunk->list_trunks();
$j = count($trunks);
for($i=0;$i<$j;$i++) { 
	$res[$i]['id'] = $trunks[$i]["id"];
	$res[$i]['status'] = $Trunk->is_active($trunks[$i]["id"], true) ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
	$res[$i]["btn-class"] = $Trunk->is_active($trunks[$i]["id"], true) ? "btn-success" : "btn-warning";
}

echo json_encode(array("status"=>"OK", "data"=>$res));


?>