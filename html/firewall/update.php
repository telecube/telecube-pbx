<?php
require("../init.php");

//print_r($_POST);
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


include("load-firewall.php");
//exec("/usr/bin/php ".__DIR__."/load-firewall.php > /dev/null &");

header("Location: /firewall/?update=".$update_type);

?>