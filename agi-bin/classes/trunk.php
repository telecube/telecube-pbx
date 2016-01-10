<?php

namespace Telecube;


class Trunk{

	function get_active(){
		global $Db, $dbPDO;
		$q = "select name from trunks where active = ? order by rand() limit 1;";
		$trunk = $Db->query($q, array("yes"), $dbPDO);
		return $trunk[0]['name'];
	}


}
?>