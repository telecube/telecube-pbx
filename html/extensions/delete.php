<?php
require("../init.php");

$q = "delete from sip_devices where name = ?;";
$data = array($_GET['ext']);
$res = $Db->pdo_query($q,$data,$dbPDO);

$q = "optimize table sip_devices;";
$res = $Db->pdo_query($q,array(),$dbPDO);

header("Location: /extensions/");
?>