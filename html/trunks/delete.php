<?php
require("../init.php");

$id = $_GET['id'];

$q = "delete from trunks where id = ?;";
$data = array($id);
$res = $Db->pdo_query($q,$data,$dbPDO);

$q = "optimize table trunks;";
$res = $Db->pdo_query($q,array(),$dbPDO);


// get all the trunks and reload the config files
$regStr = "; Register Strings\n";
$ipStr = "; IP Auth Peers\n";
$q = "select * from trunks;";
$res = $Db->query($q,array(),$dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
//	echo $res[$i]['name']."\n";
	if($res[$i]['auth_type'] == "password"){
		$regStr .= "register => ".$res[$i]['username'].":".$res[$i]['password']."@".$res[$i]['host_address']."\n";
	}
}

$regFp = "/etc/asterisk/sip-register.conf";
file_put_contents($regFp,$regStr);

$rel = `sudo /usr/sbin/asterisk -rx "sip reload"`;


// clean the extensions
$q = "select name, routing from sip_devices;";
$res = $Db->query($q,array(),$dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
	$routing = json_decode($res[$i]['routing'], true);
	$jj = count($routing);
	for($ii=0;$ii<$jj;$ii++) { 
		if($routing[$ii]['id'] == $id){
			unset($routing[$ii]);
			break;
		}
	}
	$routing = array_values($routing);
	$q2 = "update sip_devices set routing = ? where name = ?;";
	$res2 = $Db->pdo_query($q2, array(json_encode($routing), $res[$i]['name']), $dbPDO);

}

header("Location: /trunks/");
?>