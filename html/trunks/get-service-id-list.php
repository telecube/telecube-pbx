<?php
require("../init.php");


if(isset($_GET["type"]) && $_GET["type"] == "extension"){

	$exts = $Ext->list_extensions();
	$list = array(array("value"=>"","text"=>"None"));
	$j = count($exts);
	for($i=0;$i<$j;$i++) { 
		$list[] = array("value"=>$exts[$i]['name'],"text"=>$exts[$i]['name']);
	}
	echo json_encode($list);

}elseif(isset($_GET["type"]) && $_GET["type"] == "linehunt"){
	
	$lh = $Linehunt->get_list();
	$list = array(array("value"=>"","text"=>"None"));
	$j = count($lh);
	for($i=0;$i<$j;$i++) { 
		$list[] = array("value"=>$lh[$i]['id'],"text"=>$lh[$i]['name']);
	}
	echo json_encode($list);

}else{
	echo json_encode(array(
			array("value"=>"", "text"=>"No Type Set"),
		));
}


?>