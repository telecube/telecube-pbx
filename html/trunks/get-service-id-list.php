<?php
require("../init.php");


if(isset($_GET["type"]) && $_GET["type"] == "extension"){
//	$q = "update trunks set def_inbound_type = ? where id = ?;";
//	$res = $Db->pdo_query($q, array($value, $id), $dbPDO);

	$exts = $Ext->list_extensions();
	$list = array(array("value"=>"","text"=>"None"));
	$j = count($exts);
	for($i=0;$i<$j;$i++) { 
		$list[] = array("value"=>$exts[$i]['name'],"text"=>$exts[$i]['name']);
	}
	echo json_encode($list);
//	echo json_encode(array(
//			array("value"=>"", "text"=>"None"),
//			array("value"=>"ext1", "text"=>"Ext 1"),
//			array("value"=>"ext2", "text"=>"Ext 2"),
//			array("value"=>"ext3", "text"=>"Ext 3"),
//			array("value"=>"ext4", "text"=>"Ext 4"),
//		));
}elseif(isset($_GET["type"]) && $_GET["type"] == "timebased"){
	echo json_encode(array(
			array("value"=>"", "text"=>"None"),
			array("value"=>"time1", "text"=>"Time 1"),
			array("value"=>"time2", "text"=>"Time 2"),
		));
}else{
	echo json_encode(array(
			array("value"=>"", "text"=>"No Type Set"),
		));
}


?>