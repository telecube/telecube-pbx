<?php
require("../init.php");

// git version
//$git_version = $Common->get_set_version_pref("current_version_git", '');

if(file_exists("/opt/telecube-pbx/html")){

	$res = exec("sudo /usr/bin/git -C /opt/telecube-pbx/ fetch origin master", $gfOut1, $return_var0);
	$res = exec("sudo /usr/bin/git -C /opt/telecube-pbx/ reset --hard FETCH_HEAD", $grOut1, $return_var00);

	// get the latest commit id after the pull
	$res = exec("sudo /usr/bin/git -C /opt/telecube-pbx/ log -1", $gl2Out, $return_var0a);
	$thiscmtid = $Common->git_commit_id_from_log($gl2Out);
	$Common->set_pref('current_version_git', $thiscmtid);

	// zero  the update wait count
	$Common->set_pref('update_wait_count', '0');

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/agi-bin /var/lib/asterisk/", $rOut1, $return_var1);
	
	exec("sudo /bin/chown asterisk:asterisk /var/lib/asterisk/agi-bin -R", $rOut1a, $return_var1a);

	exec("sudo /bin/chmod +x /var/lib/asterisk/agi-bin/unauthorised-call.php", $rOut1b, $return_var1b);
	exec("sudo /bin/chmod +x /var/lib/asterisk/agi-bin/voip-out.php", $rOut1c, $return_var1c);
	exec("sudo /bin/chmod +x /var/lib/asterisk/agi-bin/voip-in.php", $rOut1d, $return_var1d);

	exec("sudo /usr/bin/rsync -av --delete /opt/telecube-pbx/html/ /var/www/html/", $rOut2, $return_var2);

	$msg = "OK, update successful.";
}else{
	$msg = "Folder /opt/telecube-pbx does not exist!";
}

header("Location: db-update.php");
?>