<?php

namespace Telecube;


class Log{

	function updates($msg){
		global $Db, $dbPDO;

		$q = "insert into logging_updates (datetime, update_type, log_text) values (now(),?,?);";
		$Db->pdo_query($q,array("db", $msg),$dbPDO);
	}

}
?>