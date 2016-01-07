<?php
require("../init.php");

//echo "hello";
// git version
$git_version = $Common->get_set_version_pref("current_version_git", '');

if(file_exists("/opt/telecube-pbx/html")){
//	chdir('/opt/telecube-pbx');

//	echo "<pre>";

	$res = exec("sudo /usr/bin/git -C /opt/telecube-pbx/ pull", $gOut, $return_var);
//	print_r($gOut);

	$q = "insert into test (datetime, data1) values (?,?);";
	$data = array(date("Y-m-d H:i:s"), json_encode($gOut));
	$Db->query($q,$data,$dbPDO);

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/agi-bin /var/lib/asterisk/", $rOut1, $return_var);
//	print_r($rOut1);

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/html/ /var/www/html/", $rOut2, $return_var);
//	print_r($rOut2);

//	echo "<pre>";

	$msg = "OK, update successful.";
}else{
//	echo "Folder /opt/telecube-pbx does not exist!";
	$msg = "Folder /opt/telecube-pbx does not exist!";
}

header("Location: db-update.php");
?>