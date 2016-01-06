<?php
require("../init.php");

// system version
$dbv = $Common->get_set_version_pref("current_version_db", 0);

// run the updates
$j = 99; // max 99 files
for($i=0;$i<$j;$i++) { 
	// if there is a file to process
	$fp = $Config->get("git_clone_path")."/updates/db/update-".$dbv.".sh";
	if(file_exists($fp)){
		$com 	= "sudo /bin/sh ".$fp;
		$res 	= exec($com, $o, $r);
		print_r($o);
		// set the next incr
		$dbv++;
		$Common->set_pref("current_version_system",$dbv);
	}else{
		break;
	}
}
exit;
$msg = "OK, update successful.";
header("Location: /update/?msg=".$msg."&dbv=".$dbv);


exit;
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