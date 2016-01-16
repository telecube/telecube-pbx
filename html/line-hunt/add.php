<?php
require("../init.php");

//echo "<pre>";
//print_r($_POST);
$lhname = trim($_POST['linehunt_name']);

if(empty($lhname)){
	header("Location: /line-hunt/?err=noname");
	exit;
}

$q = "insert into linehunt (datetime, name, data) values (?,?,?);";
$data = array(date("Y-m-d H:i:s"), $lhname, "[]");
$res = $Db->query($q,$data,$dbPDO);

header("Location: /line-hunt/");

/* 
CREATE TABLE IF NOT EXISTS telecube.linehunt (
	id int(10) unsigned NOT NULL auto_increment,
	datetime datetime NOT NULL,
	name varchar(64) NOT NULL,
	data text NOT NULL,
	PRIMARY KEY  (id)
);
*/




?>