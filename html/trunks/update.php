<?php
require("../init.php");

$id = $_POST["pk"];
$field = str_replace("-".$_POST["pk"], "", $_POST["id"]);
$value = $_POST["value"];


?>