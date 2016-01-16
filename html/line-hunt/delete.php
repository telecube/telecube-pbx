<?php
require("../init.php");

$id = $_GET['id'];

$q = "delete from linehunt where id = ?;";
$data = array($id);
$res = $Db->pdo_query($q,$data,$dbPDO);

header("Location: /line-hunt/?msg=success");
?>