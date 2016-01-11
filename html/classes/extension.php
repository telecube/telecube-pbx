<?php

namespace Telecube;


class Ext{

	function list_extensions(){
		global $Db, $dbPDO;
		$q = "select name, secret, port, regseconds, label, bar_13, bar_int, bar_mobile, bar_fixed from sip_devices order by name;";
		$data = array();
		$res = $Db->pdo_query($q,$data,$dbPDO);
		return $res;
	}

	function get_names($sip_devices){
		$ext_names = array();
		$j = count($sip_devices);
		for($i=0;$i<$j;$i++) { 
			$ext_names[] = $sip_devices[$i]['name'];
		}
		return $ext_names;
	}

	function is_registered($ext){
		global $Db, $dbPDO;
		$q = "select port, regseconds from sip_devices where name = ?;";
		$res = $Db->pdo_query($q, array($ext), $dbPDO);
		return $res[0]['port'] > 0 && $res[0]['regseconds'] > date("U") ? true : false;
	}

	function get_routing($ext){
		global $Db, $dbPDO, $Common;
		$q = "select routing from sip_devices where name = ?;";
		$res = $Db->pdo_query($q, array($ext), $dbPDO);
		$str = trim(stripslashes($res[0]['routing']));
		return is_string($str) && is_array(json_decode($str, true)) ? json_decode($str, true) : false;
	}

	function set_routing($ext, $routing){
		global $Db, $dbPDO;
		$q = "update sip_devices set routing = ? where name = ?;";
		$res = $Db->pdo_query($q, array(json_encode($routing), $ext), $dbPDO);
		return true;
	}


}
?>