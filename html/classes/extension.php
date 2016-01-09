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
}
?>