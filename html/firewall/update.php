<?php
require("../init.php");

$name 	= $_POST["pk"];
$value 	= $_POST["value"];

if($name == "fw_https_ports" && $value == "off"){
	// get the white;ist ips
	$wl = json_decode($Common->get_pref("fw_whitelist_ips"));
	if(!in_array($_SERVER["REMOTE_ADDR"], $wl)){
		header("HTTP/1.0 400 Bad Request");
		echo "IP ".$_SERVER["REMOTE_ADDR"]." not found in whitelist!";
		exit;
	}
}

if($_POST["pk"] == "fw_whitelist_ips"){
	$list 	= trim($_POST["value"]);
	$value 	= json_encode(explode("\n", $list));
}

$Common->set_pref($name, $value);

include("load-firewall.php");

echo "OK";
?>