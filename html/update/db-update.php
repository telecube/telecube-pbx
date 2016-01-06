<?php
require("../init.php");

// check if the preferences exist;
$q = "select * from preferences where name = 'current_version_git';";
$res = $Db->pdo_query($q,array(),$dbPDO);
if(isset($res[0]['name']) && $res[0]['name'] == "current_version_git"){
	$git_version = $res[0]['value'];
}else{
	$q = "insert into preferences (name, value) values ('current_version_git','');";
	$Db->pdo_query($q,array(),$dbPDO);
	$git_version = '';
}

$q = "select * from preferences where name = 'current_version_db';";
$res = $Db->pdo_query($q,array(),$dbPDO);
if(isset($res[0]['name']) && $res[0]['name'] == "current_version_db"){
	$db_version = $res[0]['value'];
}else{
	$q = "insert into preferences (name, value) values ('current_version_db','0');";
	$Db->pdo_query($q,array(),$dbPDO);
	$db_version = 0;
}

$q = "select * from preferences where name = 'current_version_system';";
$res = $Db->pdo_query($q,array(),$dbPDO);
if(isset($res[0]['name']) && $res[0]['name'] == "current_version_system"){
	$system_version = $res[0]['value'];
}else{
	$q = "insert into preferences (name, value) values ('current_version_system','0');";
	$Db->pdo_query($q,array(),$dbPDO);
	$system_version = 0;
}

// ok so the first db entries are
// adding name as unique key in preferences
if($db_version == 0){
	$q = "ALTER TABLE telecube.preferences ADD UNIQUE KEY name (name);";
	$Db->pdo_query($q,array(),$dbPDO);
	$newDbVer = 1;
	$q = "update preferences set value = ? where name = ?;";
	$Db->pdo_query($q,array($newDbVer, 'current_version_db'),$dbPDO);
}
// changing the value field to text
if($db_version <= 1){
	$q = "ALTER TABLE telecube.preferences MODIFY value TEXT NOT NULL;";
	$Db->pdo_query($q,array(),$dbPDO);
	$newDbVer = 2;
	$q = "update preferences set value = ? where name = ?;";
	$Db->pdo_query($q,array($newDbVer, 'current_version_db'),$dbPDO);
}


$msg = "OK, update successful.";
header("Location: /update/?msg=".$msg);


?>