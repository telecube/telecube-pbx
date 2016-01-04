<?php
require("classes/_autoloader.php");
use Telecube\Config;
$Config = new Config;

session_name($Config->get("session_name"));
session_start();

session_unset();
session_destroy();

header("Location: /");

?>