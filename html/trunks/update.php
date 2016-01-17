<?php
require("../init.php");

$id = $_POST["pk"];
$field = str_replace("-".$_POST["pk"], "", $_POST["name"]);
$value = $_POST["value"];

//$Common->ecco($_POST);
//exit;
//echo $id.' '.$field.' '.$value."\n";

//$q = "update trunks set ".$field." = ".$value." where id = ".$id.";";
//echo $q."\n";

$q = "update trunks set ".$field." = ? where id = ?;";
$data = array($value, $id);
$Db->pdo_query($q,$data,$dbPDO);


//header("HTTP/1.0 400 Bad Request");

//$Common->ecco($_POST);
echo json_encode(array("status"=>"OK","value"=>$value));

// if the update is the active status we will run the config builder
if($field == "active"){
	
	// get all the trunks and reload the config files
	$regStr = "; Register Strings\n\n";
	$ipStr = "; IP Auth Peers\n\n";
	
	$q = "select * from trunks where active = ?;";
	$res = $Db->query($q,array("yes"),$dbPDO);
	$j = count($res);
	for($i=0;$i<$j;$i++) { 
	//	echo $res[$i]['name']."\n";
		if($res[$i]['auth_type'] == "password"){
			$thisTrId = str_pad($res[$i]['id'], 3, "0", STR_PAD_LEFT);
			$regStr .= "register => ".$res[$i]['username'].":".$res[$i]['password'].":".$res[$i]['username']."@".$res[$i]['name']."/".$thisTrId."\n";
		}

		// build the peers string
		$ipStr .= "[".$res[$i]['name']."]\n";
		$ipStr .= "type=peer\n";
		$ipStr .= "host=".$res[$i]['host_address']."\n";
		$ipStr .= "username=".$res[$i]['username']."\n";
		$ipStr .= "secret=".$res[$i]['password']."\n";
		$ipStr .= "fromuser=".$res[$i]['username']."\n";
		$ipStr .= "context=voip-trunks\n";
		$ipStr .= "insecure=port,invite\n";
		$ipStr .= "allow=all\n";
		$ipStr .= "nat=force_rport,comedia\n";
	//	$ipStr .= "nat=yes\n";
		$ipStr .= "directmedia=no\n";

		$ipStr .= "\n\n";

	}

	$regFp = "/etc/asterisk/sip-register.conf";
	file_put_contents($regFp,$regStr);

	$ipFp = "/etc/asterisk/sip-trunks.conf";
	file_put_contents($ipFp,$ipStr);

	$rel = `sudo /usr/sbin/asterisk -rx "sip reload"`;
	//$reset = `sudo /usr/sbin/asterisk -rx "sip show peer $name load"`;

	
	// we can set the trunk as unregistered if active = no
	if($value == "no"){
		$q = "update trunks set register_status = ? where id = ?;";
		$data = array("Unregistered", $id);
		$Db->pdo_query($q,$data,$dbPDO);
	}

}

/* 
; SIP IP Authenticated Trunks

[telecube-1]
type=friend
username=123123213
secret=werwert
host=abc.net

;Below is will be the context you will use to receive incoming calls in extension.conf
context=voip-in 

insecure=port,invite
*/
?>