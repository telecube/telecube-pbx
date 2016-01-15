<?php

namespace Telecube;


class Exten{

	function get_routing($ext){
		global $Db, $dbPDO, $Trunk;
		$q = "select routing from sip_devices where name = ?;";
		$res = $Db->pdo_query($q, array($ext), $dbPDO);
		$routing = json_decode($res[0]['routing'], true);
		
		$actStat = array("yes"=>"active","no"=>"inactive");

		// check active statuses
		$j = count($routing);
		for($i=0;$i<$j;$i++) { 
			$routing[$i]['status'] = $actStat[$Trunk->get_active_status($routing[$i]['id'])];
		}
		return $routing;
	}

}
?>