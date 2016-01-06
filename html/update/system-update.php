<?php
require("../init.php");

// system version
$sysv = $Common->get_set_version_pref("current_version_system", 0);

// run the updates
$j = 99; // max 99 files
for($i=0;$i<$j;$i++) { 
	// if there is a file to process
	$fp = $Config->get("git_clone_path")."/updates/sys/update-".$sysv.".sh";
	if(file_exists($fp)){
		$com 	= "sudo /bin/sh ".$fp;
		$res 	= exec($com, $o, $r);
		//print_r($o);
		$Log->updates(json_encode($o), "system");
		// set the next incr
		$sysv++;
		$Common->set_pref("current_version_system",$sysv);
	}else{
		break;
	}
}
//exit;
$msg = "OK, update successful.";
header("Location: /update/?msg=".$msg."&sysv=".$sysv);
?>