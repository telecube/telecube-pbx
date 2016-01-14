<?php
if (PHP_SAPI !== 'cli') exit("Not allowed here..");
require("init.php");

// is the pbx natted?
$natted = $Common->get_pref("pbx_nat_is_natted");
if($natted != "yes"){
	exit;
}

// get the external ip
$externip = file_get_contents($Config->get("ip_check_url"));

// get the current pref
$pbx_nat_external_ip = $Common->get_pref("pbx_nat_external_ip");

if($externip == $pbx_nat_external_ip){
	// nothing to do
	exit;
}

// update the preferences
$Common->set_pref("pbx_nat_external_ip", $externip);

// get the local network details
$hostip = $Common->get_pref("pbx_host_ip");
$localnet = $Common->get_pref("pbx_nat_localnet");

// reset the sip network config
exec('sudo /bin/echo "; new variable network stuff" > /etc/asterisk/sip-network.conf');
exec('sudo /bin/echo "udpbindaddr='.$hostip.'" >> /etc/asterisk/sip-network.conf');
exec('sudo /bin/echo "externip='.$externip.'" >> /etc/asterisk/sip-network.conf');
exec('sudo /bin/echo "localnet='.$localnet.'" >> /etc/asterisk/sip-network.conf');
exec('sudo /bin/echo "tcpenable=no" >> /etc/asterisk/sip-network.conf');
exec('sudo /bin/echo "tcpbindaddr=0.0.0.0" >> /etc/asterisk/sip-network.conf');
exec('sudo /bin/echo "transport=udp" >> /etc/asterisk/sip-network.conf');


exec('sudo /usr/sbin/asterisk -rx "sip reload"');

?>