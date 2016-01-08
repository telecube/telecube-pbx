<?php
require("../init.php");

$id = $_POST["pk"];
$field = str_replace("-".$_POST["pk"], "", $_POST["name"]);
$value = $_POST["value"];

//echo $id.' '.$field.' '.$value."\n";

//$q = "update trunks set ".$field." = ".$value." where id = ".$id.";";
//echo $q."\n";

$q = "update trunks set ".$field." = ? where id = ?;";
$data = array($value, $id);
$Db->pdo_query($q,$data,$dbPDO);


//header("HTTP/1.0 400 Bad Request");

//$Common->ecco($_POST);
echo "OK";
?>