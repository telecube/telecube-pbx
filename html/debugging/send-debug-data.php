<?php
require("../init.php");

//$Common->ecco($_POST);
//exit;
/* 
debug-input-ip-tables
debug-input-system-info
debug-input-asterisk-modules
debug-input-trunks

debug-input-sip-conf
debug-input-sip-conf-conf
debug-input-sip-register-conf
debug-input-sip-trunks-conf
*/
// this pbx uuid
$pbx_uuid = $Common->get_pref("pbx_uuid");

// the post 
$url = $Config->get("api_url")."/receive-debug-info.php";
$data = array(
			"pbx_uuid"			=> $pbx_uuid, 
			"ip_tables"			=> $_POST["debug-input-ip-tables"], 
			"system_info"		=> $_POST["debug-input-system-info"], 
			"asterisk_modules" 	=> $_POST["debug-input-asterisk-modules"], 
			"trunks"			=> $_POST["debug-input-trunks"],
			"sip_conf"			=> $_POST["debug-input-sip-conf"],
			"sip_conf_conf"		=> $_POST["debug-input-sip-conf-conf"],
			"sip_register_conf"	=> $_POST["debug-input-sip-register-conf"],
			"sip_trunks_conf"	=> $_POST["debug-input-sip-trunks-conf"],
		);
$res = $Curl->http($url, $data, '', '', 60, true);

if($res->status == "OK"){
	header("Location: /debugging/?status=ok");
}else{
	$Common->ecco($res);

}



?>