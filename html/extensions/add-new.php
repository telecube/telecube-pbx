<?php
require("../init.php");

if($_POST["password-type"] == "crypto"){
	$pass = $Common->secure_password(12);
}else{
	$pass = $Common->random_string(12);
}

$q = "select id, name from trunks order by id;";
$trunks = $Db->pdo_query($q, array(), $dbPDO);
$j = count($trunks);
for($i=0;$i<$j;$i++) { 
	$trunks[$i]['allowed'] = "";
}
$q = "insert into sip_devices (name, context, secret, host, nat, type, `call-limit`, insecure, defaultuser, bar_int, bar_13, bar_fixed, bar_mobile, routing) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
$data = array($_POST['ext'], "voip-extensions", $pass, "dynamic", "force_rport,comedia", "friend", 0, "port", $_POST['ext'], "n", "n", "n", "n",json_encode($trunks));
$res = $Db->pdo_query($q,$data,$dbPDO);

header("Location: /extensions/")
?>