#!/usr/bin/php -q
<?php

include("init.php");


$Agi->verbose("Line: ".__line__);
$Agi->verbose("Callto: ".$dnid);

// make an array of ips to block
$ipBlockList = array();

$chanPeerIP = $Agi->get_variable("CHANNEL(peerip)",	true);
$Agi->verbose("Peer IP: ".$chanPeerIP);
if(in_array($chanPeerIP, $ipBlockList) === false){
	$ipBlockList[] = $chanPeerIP;
}

$chanRecvIP = $Agi->get_variable("CHANNEL(recvip)",	true);
$Agi->verbose("Receive IP: ".$chanRecvIP);
if(in_array($chanRecvIP, $ipBlockList) === false){
	$ipBlockList[] = $chanRecvIP;
}

$viaHeader = $Agi->get_variable("SIP_HEADER(Via)",	true);
$Agi->verbose("Via Header: ".$viaHeader);

//$fromHeader = $Agi->get_variable("SIP_HEADER(From)",	true);
//$Agi->verbose("From Header: ".$fromHeader);

//$contactHeader = $Agi->get_variable("SIP_HEADER(Contact)",	true);
//$Agi->verbose("Contact Header: ".$contactHeader);


preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $viaHeader, $matches);
$ip = $matches[0];
$Agi->verbose("IP: ".$ip);
if(in_array($ip, $ipBlockList) === false){
	$ipBlockList[] = $ip;
}



?>