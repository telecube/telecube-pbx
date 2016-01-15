<?php
require("../init.php");

//header("HTTP/1.0 400 Bad Request");
//$Common->ecco($_POST);
//exit;

$id 	= $_POST["pk"];
$value 	= $_POST["value"];


$q = "update trunks set def_inbound_type = ? where id = ?;";
$res = $Db->pdo_query($q, array($value, $id), $dbPDO);

//print_r( $res );

echo $value;

?>