<?php
require("../init.php");

$ext = $_POST["ext"];
$list = json_decode($_POST["arr"]);

$new_routing = array();

$x = 0;
$y = 0;
$j = count($list);
for($i=0;$i<$j;$i++) { 
	if($x == 3){
		$new_routing[$y]['allowed'] = $list[$i];
		$x=0;
		$y++;
		continue;
	}
	if($x == 2){
		$new_routing[$y]['name'] = $list[$i];
		$x++;
	}
	if($x == 1){
		$new_routing[$y]['status'] = $list[$i];
		$x++;
	}
	if($x == 0){
		$new_routing[$y]['id'] = $list[$i];
		$x++;
	}
}

// we need to get the current routing data
$current_routing = $Ext->get_routing($ext);
if(empty($current_routing)){
	// there's nothing there so set what we have and we're done
	$Ext->set_routing($ext, $new_routing);
	echo json_encode(array("status"=>"OK"));
	exit;
}

$j = count($new_routing);
for($i=0;$i<$j;$i++) { 
	$jj = count($current_routing);
	for($ii=0;$ii<$jj;$ii++) { 
		if($current_routing[$ii]['id'] == $new_routing[$i]['id']){
			$new_routing[$i]['allowed'] = $current_routing[$ii]['allowed'];
	//		break;
		}
	}
}

$Ext->set_routing($ext, $new_routing);

echo json_encode(array("status"=>"OK"));

?>