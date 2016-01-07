<?php
require("init.php");

// get the latest commit id
$commit_id = $Common->get_pref("current_version_git");
if($commit_id == ""){
	exit("No Commit Id!");
}

// this pbx uuid
$pbx_uuid = $Common->get_pref("pbx_uuid");

// the post 
$url = $Config->get("api_url")."/check-updates-waiting.php";
$data = array("pbx_uuid"=>$pbx_uuid, "commit_id"=>$commit_id, "check_type"=>"count");
$res = $Curl->http($url, $data, '', '', 60, true);

// update with the result
$Common->set_pref("update_wait_count", $res->count);


?>