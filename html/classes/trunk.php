<?php

namespace Telecube;


class Trunk{

	function list_trunks(){
		global $Db, $dbPDO;
		$q = "select * from trunks;";
		$trunks = $Db->query($q,array(),$dbPDO);
		return $trunks;
	}

	function is_active($id){
		global $Db, $dbPDO;
		$q = "select active from trunks where id = ?;";
		$trunk = $Db->query($q,array($id),$dbPDO);
		return empty($trunk[0]['active']) ? "no" : $trunk[0]['active'];
	}


}
?>