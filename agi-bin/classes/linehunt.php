<?php

namespace Telecube;


class Linehunt{

	function get_data($id){
		global $Db, $dbPDO, $Trunk;
		$q = "select * from linehunt where id = ?;";
		$res = $Db->pdo_query($q, array($id), $dbPDO);
		return isset($res[0]['data']) ? json_decode($res[0]['data'], true) : array();
	}


}
?>