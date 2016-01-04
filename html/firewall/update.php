<?php
require("../init.php");

//print_r($_POST); exit;

if($_POST["firewall_update"] == "ssh_update"){
	$q = "update preferences set value = ? where name = 'fw_ssh_ports';";
	$data = array($_POST["fw_ssh_port"]);
	$Db->pdo_query($q,$data,$dbPDO);
	$update_type = "ssh_ports";
}

if($_POST["firewall_update"] == "sip_update"){
	$q = "update preferences set value = ? where name = 'fw_sip_ports';";
	$data = array($_POST["fw_sip_port"]);
	$Db->pdo_query($q,$data,$dbPDO);
	$update_type = "sip_ports";
}

if($_POST["firewall_update"] == "rtp_update"){
	$q = "update preferences set value = ? where name = 'fw_rtp_ports';";
	$data = array($_POST["fw_rtp_port"]);
	$Db->pdo_query($q,$data,$dbPDO);
	$update_type = "rtp_ports";
}

if($_POST["firewall_update"] == "https_update"){
	// if the option is being turned off check the ip accessing this page is whitelisted
	if($_POST["fw_https_port"] == "off"){
		$q = "select * from preferences where name = 'fw_whitelist_ips';";
		$data = array();
		$res = $Db->pdo_query($q,$data,$dbPDO);
		$wl = json_decode($res[0]['value']);
		if(!in_array($_SERVER["REMOTE_ADDR"], $wl)){
			header("Location: /firewall/?update=https_ports&err=IP ".$_SERVER["REMOTE_ADDR"]." not found in whitelist!");
			exit;
		}
	}
	$q = "update preferences set value = ? where name = 'fw_https_ports';";
	$data = array($_POST["fw_https_port"]);
	$Db->pdo_query($q,$data,$dbPDO);
	$update_type = "https_ports";
}

if($_POST["firewall_update"] == "whitelist_ips"){
	$list = trim($_POST["fw_whitelist_ips"]);
	$q = "update preferences set value = ? where name = 'fw_whitelist_ips';";
	$data = array(json_encode(explode("\r\n", $list)));
	$Db->pdo_query($q,$data,$dbPDO);
	$update_type = "whitelist_ips";
}


include("load-firewall.php");
//exec("/usr/bin/php ".__DIR__."/load-firewall.php > /dev/null &");

header("Location: /firewall/?update=".$update_type);

?>