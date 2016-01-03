<?php
require("classes/_autoloader.php");
use Telecube\Config;
$Config = new Config;

session_name($Config->session_name);
session_start();

session_unset();
session_destroy();

header("Location: /");

?>