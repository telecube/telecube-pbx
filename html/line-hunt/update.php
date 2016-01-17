<?php
require("../init.php");

$lhid = $_POST["id"];
//echo $id."\n";
sleep(1);
//$Common->ecco($_POST['trdata']);
$data = json_decode($_POST['tdata']);
//$Common->ecco($data);
//exit;
// loop through the data and create the routing 
$lhList = array();
$j = count($data);
for($i=0;$i<$j;$i++) { 
	// expect the first iteration elements 0 & 1 to be type & id
	if($i==0){
		if($data[0][0] != "type" || $data[0][1] != "id"){
			exit("Ouch, it broke!");
		}else{
			continue;
		}
	}

	$type 			= $data[$i][0];
	$id 			= trim($data[$i][1]);
	$timeout		= trim(str_ireplace("seconds", "", $data[$i][4]));
	$description 	= $data[$i][3];

	$lhList[] = array("type"=>$type, "id"=>$id, "timeout"=>$timeout, "description"=>$description);

}

//$Common->ecco($lhList);

$Linehunt->update_data($lhid, $lhList);

echo json_encode(array("status"=>"OK"));
?>