<?php
require("../init.php");

$ext = $_GET['ext'];

$q = "delete from sip_devices where name = ?;";
$data = array($ext);
$res = $Db->pdo_query($q,$data,$dbPDO);

$q = "optimize table sip_devices;";
$res = $Db->pdo_query($q,array(),$dbPDO);

// let's reset the realtime cache
$reset = `sudo /usr/sbin/asterisk -rx "sip prune realtime $ext"`;
$reset = `sudo /usr/sbin/asterisk -rx "sip show peer $ext load"`;

header("Location: /extensions/");
?>