<?php

namespace Telecube;


class Trunk{

	function get_active(){
		global $Db, $dbPDO;
		$q = "select name from trunks where active = ? order by rand() limit 1;";
		$trunk = $Db->query($q, array("yes"), $dbPDO);
		return $trunk[0]['name'];
	}

	function get_registered(){
		global $Db, $dbPDO;
		$q = "select name from trunks where register_status = ?;";
		$trunks = $Db->query($q, array("Registered"), $dbPDO);
		return $trunks;
	}

	function get_active_status($id){
		global $Db, $dbPDO;
		$q = "select active from trunks where id = ?;";
		$active = $Db->query($q, array($id), $dbPDO);
		return $active[0]['active'];
	}

	function get_routing($id){
		global $Db, $dbPDO;
		$q = "select def_inbound_type as type, def_inbound_id as id from trunks where id = ?;";
		$route = $Db->query($q, array($id), $dbPDO);
		return $route[0];
	}
}
?>