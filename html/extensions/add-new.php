<?php
require("../init.php");

$pass = $Common->random_password(12);

$q = "insert into sip_devices (name, context, secret, host, nat, type, `call-limit`, insecure, defaultuser) values (?,?,?,?,?,?,?,?,?);";
$data = array($_POST['ext'], "voip-extensions", $pass, "dynamic", "force_rport,comedia", "friend", 0, "port,invite", $_POST['ext']);
$res = $Db->pdo_query($q,$data,$dbPDO);

header("Location: /extensions/")
?>