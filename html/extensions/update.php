<?php
require("../init.php");

$name = $_POST["pk"];
$field = str_replace("-".$_POST["pk"], "", $_POST["name"]);
$value = $_POST["value"];

$q = "update sip_devices set ".$field." = ? where name = ?;";
$data = array($value, $name);
$res = $Db->pdo_query($q,$data,$dbPDO);

//header("HTTP/1.0 400 Bad Request");

// let's reset the realtime cache
$reset = `sudo /usr/sbin/asterisk -rx "sip prune realtime $name"`;
$reset = `sudo /usr/sbin/asterisk -rx "sip show peer $name load"`;

echo "ok";
?>