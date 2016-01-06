<?php
require("../init.php");

echo "<pre>";
print_r($_POST);

/* 
Array
(
    [trunk_name] => test trunk
    [auth_type] => password
    [trunk_auth_name] => 12345
    [trunk_pass] => fgdfdfghdfgh
    [trunk_url] => sip.here.com
)

mysql> describe trunks;
+--------------+--------------+------+-----+---------+----------------+
| Field        | Type         | Null | Key | Default | Extra          |
+--------------+--------------+------+-----+---------+----------------+
| id           | int(10)      | NO   | PRI | NULL    | auto_increment |
| datetime     | datetime     | NO   |     | NULL    |                |
| name         | varchar(240) | YES  |     | NULL    |                |
| auth_type    | varchar(128) | YES  |     | NULL    |                |
| username     | varchar(128) | YES  |     | NULL    |                |
| password     | varchar(128) | YES  |     | NULL    |                |
| host_address | varchar(128) | YES  |     | NULL    |                |
| qualify      | varchar(5)   | YES  |     | NULL    |                |
+--------------+--------------+------+-----+---------+----------------+
*/


$q = "insert into trunks (datetime, name, auth_type, username, password, host_address, qualify) values (?,?,?,?,?,?,?);";
$data = array(date("Y-m-d H:i:s"), $_POST['trunk_name'], $_POST['auth_type'], $_POST['trunk_auth_name'], $_POST['trunk_pass'], $_POST['trunk_url'], "yes");
$res = $Db->query($q,$data,$dbPDO);

// get all the trunks and reload the config files
$regStr = "; Register Strings\n";
$ipStr = "; IP Auth Peers\n";
$q = "select * from trunks;";
$res = $Db->query($q,array(),$dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
//	echo $res[$i]['name']."\n";
	if($res[$i]['auth_type'] == "password"){
		$regStr .= "register => ".$res[$i]['username'].":".$res[$i]['password']."@".$res[$i]['host_address']."\n";
	}
}

$regFp = "/etc/asterisk/sip-register.conf";
file_put_contents($regFp,$regStr);

$rel = `sudo /usr/sbin/asterisk -rx "sip reload"`;
//$reset = `sudo /usr/sbin/asterisk -rx "sip show peer $name load"`;

header("Location: /trunks/")
?>