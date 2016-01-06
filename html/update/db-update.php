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
		$Log->update(json_encode($o));
		// set the next incr
		$dbv++;
		$Common->set_pref("current_version_system",$dbv);
	}else{
		break;
	}
}
//exit;
$msg = "OK, update successful.";
header("Location: /update/?msg=".$msg."&dbv=".$dbv);
?>