<?php
require("/var/www/html/classes/_autoloader.php");
use Telecube\Config;
use Telecube\Db;
$Config = new Config;
$Db     = new Db;

$x=0;
while ($x <= 10) {
	try{
		$dbPDO = new PDO('mysql:dbname='.$Config->get("db_name").';host='.$Config->get("db_host").';port='.$Config->get("db_port"), $Config->get("db_user"), $Config->get("db_pass"));
		break;
	} catch(PDOException $ex){
		//exit( 'Connection failed: ' . $ex->getMessage() );
		echo 'Connection failed: ' . $ex->getMessage();
		$x++;
		sleep(3);
	}
}
$dbPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

$q = "select * from preferences where name LIKE 'fw_%';";
$data = array();
$res = $Db->pdo_query($q,$data,$dbPDO);

#Flush all existing chains
$addrule = `sudo /sbin/iptables --flush`;

#Allow traffic on loopback
$addrule = `sudo /sbin/iptables -A INPUT -i lo -j ACCEPT`;
$addrule = `sudo /sbin/iptables -A OUTPUT -o lo -j ACCEPT`;
# allow established connections
$addrule = `sudo /sbin/iptables -A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT`;

#Allow traffic on localhost
$addrule = `sudo /sbin/iptables -A INPUT -s 127.0.0.1 -j ACCEPT`;

# Creating default policies
$addrule = `sudo /sbin/iptables -P INPUT DROP`;
$addrule = `sudo /sbin/iptables -P OUTPUT DROP`;
$addrule = `sudo /sbin/iptables -P FORWARD DROP`;

$j = count($res);
for($i=0;$i<$j;$i++) { 
	if($res[$i]['name'] == "fw_whitelist_ips"){
		$list = json_decode($res[$i]['value']);
		$jj = count($list);
		for($ii=0;$ii<$jj;$ii++) { 
			$addrule = `sudo /sbin/iptables -A INPUT -s $list[$ii] -j ACCEPT`;
		}
	}
	# ssh ports
	if($res[$i]['name'] == "fw_ssh_ports"){
		if($res[$i]['value'] == "both"){
			$addrule = `sudo /sbin/iptables -A INPUT -p tcp --dport 22 -j ACCEPT`;
			$addrule = `sudo /sbin/iptables -A INPUT -p tcp --dport 32122 -j ACCEPT`;
		}else{
			$sshport = $res[$i]['value'];
			$addrule = `sudo /sbin/iptables -A INPUT -p tcp --dport $sshport -j ACCEPT`;
		}
	}
	if($res[$i]['name'] == "fw_sip_ports"){
		if($res[$i]['value'] != "off"){
			$sipport = $res[$i]['value'];
			$addrule = `sudo /sbin/iptables -A INPUT -p udp --dport $sipport -j ACCEPT`;
		}
	}
	if($res[$i]['name'] == "fw_rtp_ports"){
		if($res[$i]['value'] != "off"){
			$rtpport = $res[$i]['value'];
			$addrule = `sudo /sbin/iptables -A INPUT -p udp --dport $rtpport -j ACCEPT`;
		}
	}
	if($res[$i]['name'] == "fw_https_ports"){
		if($res[$i]['value'] != "off"){
			$httpsport = $res[$i]['value'];
			$addrule = `sudo /sbin/iptables -A INPUT -p tcp --dport $httpsport -j ACCEPT`;
		}
	}
}

# allow established connections
$addrule = `sudo /sbin/iptables -A FORWARD -i eth0 -m state --state RELATED,ESTABLISHED -j ACCEPT`;
$addrule = `sudo /sbin/iptables -A OUTPUT -m state --state NEW,RELATED,ESTABLISHED -j ACCEPT`;

?>