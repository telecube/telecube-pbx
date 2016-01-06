<?php
require("../init.php");

// check if the preferences exist;
// db version
$db_version = $Common->get_set_version_pref("current_version_db", 0);

// ok so the first db entries are
//	$Common->db_update("ALTER TABLE telecube.preferences ADD UNIQUE KEY name (name);",1);
// 
if($db_version == 0){

}
// 
if($db_version <= 1){

}
// 
if($db_version <= 2){

}
// 
if($db_version <= 3){

}
// 
if($db_version <= 4){

}
// 
if($db_version <= 5){

}


$msg = "OK, update successful.";
header("Location: /update/?msg=".$msg."&dbv=".$db_version);


?>