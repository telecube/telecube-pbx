<?php
require("../init.php");

// system version
$sysv = $Common->get_set_version_pref("current_version_system", 0);

// run the updates
$j = 99; // max 99 files
for($i=0;$i<$j;$i++) { 
	// if there is a file to process
	$logp 	= $Config->get("git_clone_path")."/updates/sys/log.txt";
	$fp 	= $Config->get("git_clone_path")."/updates/sys/update-".$sysv.".sh";
	if(file_exists($fp)){
		$dt 	= date("Y-m-d H:i:s");
		$com 	= "sudo /bin/sh ".$fp;
		$res 	= exec($com, $o, $r);
		exec('sudo /bin/echo "$dt - Ran CMD: $com" >> $logp');
		exec('sudo /bin/echo $o >> $logp');
		exec('sudo /bin/echo "" >> $logp');
		// set the next incr
		$sysv++;
		$Common->set_pref("current_version_system",$sysv);
	}else{
		break;
	}
}

exit;

// setting perms to sip.conf so the console can write to it
if($system_version == 0){
//	$Common->system_update('sudo /bin/chmod 0666 /etc/asterisk/sip.conf',1);
}
// 
if($system_version <= 1){
//	$Common->system_update("",2);
}
// 
if($system_version <= 2){
//	$Common->system_update("",3);
}
// 
if($system_version <= 3){
//	$Common->system_update("",4);
}
// 
if($system_version <= 4){
//	$Common->system_update("",5);
}
// 
if($system_version <= 5){
//	$Common->system_update("",6);
}
// 
if($system_version <= 6){
//	$Common->system_update("",7);
}

//www-data ALL=NOPASSWD: /bin/cat

$msg = "OK, update successful.";
header("Location: /update/?msg=".$msg."&sysv=".$system_version);
?>