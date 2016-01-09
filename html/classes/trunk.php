<?php

namespace Telecube;


class Trunk{

	function list_trunks(){
		global $Db, $dbPDO;
		$q = "select * from trunks;";
		$trunks = $Db->query($q,array(),$dbPDO);
		return $trunks;
	}




}
?>