<?php
require("../init.php");

// if the updates are showing 0 we'll go check
$pref_update_wait_count = $Common->get_pref("update_wait_count");
if($pref_update_wait_count == "0"){
	// check for updates
	$commit_id = $Common->get_pref("current_version_git");
	if($commit_id != ""){
		$pbx_uuid = $Common->get_pref("pbx_uuid");

		$data = array("pbx_uuid"=>$pbx_uuid, "commit_id"=>$commit_id, "check_type"=>"count");

		$url = $Config->get("api_url")."/check-updates-waiting.php";

		$res = $Curl->http($url, $data, '', '', 60, true);
		if(isset($res->count) && $res->count != $pref_update_wait_count){
			$Common->set_pref("update_wait_count", $res->count);
			header("Refresh:0");
			exit;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>

	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Telecube Cloud PBX!</h1>

			<p>&nbsp;</p>

			<?php
			if($pref_update_wait_count == 0){
				echo 'No updates';
			}else{
				echo '<p><a href="git-update.php">Run Update</a></p>';
			}
			?>


		</div>

		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>

	</body>
</html>