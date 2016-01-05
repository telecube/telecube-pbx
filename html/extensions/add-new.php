<?php
require("../init.php");

$pass = $Common->random_password(12);

$q = "insert into sip_devices (name, context, secret) values (?, ?, ?);";
$data = array($_POST['ext'], "voip-extensions", $pass);
$res = $Db->pdo_query($q,$data,$dbPDO);

header("Location: /extensions/")

?>