<?php
require("init.php");

$commit_id = $Common->get_pref("current_version_git");
if($commit_id == ""){
	exit("No Commit Id!");
}

$pbx_uuid = $Common->get_pref("pbx_uuid");

$data = array("pbx_uuid"=>$pbx_uuid, "commit_id"=>$commit_id, "check_type"=>"count");

$url = $Config->get("api_url")."/check-updates-waiting.php";

$res = $Curl->http($url, $data, '', '', 60, true);

$Common->set_pref("update_wait_count", $res->count);

?>