<?php
require("../init.php");

// git version
//$git_version = $Common->get_set_version_pref("current_version_git", '');

if(file_exists("/opt/telecube-pbx/html")){

	$res = exec("sudo /usr/bin/git -C /opt/telecube-pbx/ pull", $gOut, $return_var);

	// get the latest commit id after the pull
	$res = exec("sudo /usr/bin/git -C /opt/telecube-pbx/ log -1", $gl2Out, $return_var);
	$thiscmtid = $Common->git_commit_id_from_log($gl2Out);
	$Common->set_pref('current_version_git', $thiscmtid);

	// zero  the update wait count
	$Common->set_pref('update_wait_count', '0');

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/agi-bin /var/lib/asterisk/", $rOut1, $return_var);

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/html/ /var/www/html/", $rOut2, $return_var);

	$msg = "OK, update successful.";
}else{
	$msg = "Folder /opt/telecube-pbx does not exist!";
}

header("Location: db-update.php");
?>