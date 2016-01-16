<?php

namespace Telecube;


class Linehunt{

	function get_list(){
		global $Db, $dbPDO;
		$q = "select * from linehunt;";
		$lh = $Db->query($q,array(),$dbPDO);
		return $lh;
	}

	function get_linehunt($id){
		global $Db, $dbPDO;
		$q = "select * from linehunt where id = ?;";
		$lh = $Db->query($q,array($id),$dbPDO);
		return $lh[0];
	}

	function update_data($id, $data){
		global $Db, $dbPDO;
		$q = "update linehunt set data = ? where id = ?;";
		$lh = $Db->query($q, array(json_encode($data), $id) ,$dbPDO);
	}
}
?>