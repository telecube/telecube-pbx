<?php
require("../init.php");

$name = $_POST["pk"];
$field = str_replace("-".$_POST["pk"], "", $_POST["name"]);
$value = $_POST["value"];

$q = "update sip_devices set ".$field." = ? where name = ?;";
$data = array($value, $name);
$res = $Db->pdo_query($q,$data,$dbPDO);

//header("HTTP/1.0 400 Bad Request");

echo "ok";
?>