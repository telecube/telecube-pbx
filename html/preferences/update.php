<?php
require("../init.php");

$name = $_POST["pk"];
$field = str_replace("-".$_POST["pk"], "", $_POST["name"]);
$value = $_POST["value"];

$Common->set_pref($name, $value);

//header("HTTP/1.0 400 Bad Request");

echo "OK";

?>