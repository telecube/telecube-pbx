<?php

namespace Telecube;


class Common{

	function get_set_version_pref($prefname, $defval){
		global $Db, $dbPDO;

		$q = "select * from preferences where name = ?;";
		$res = $Db->pdo_query($q,array($prefname),$dbPDO);
		if(isset($res[0]['name']) && $res[0]['name'] == $prefname){
			return $res[0]['value'];
		}else{
			$q = "insert into preferences (name, value) values (?,?);";
			$Db->pdo_query($q,array($prefname, $defval),$dbPDO);
			return $defval;
		}
	}

	function system_update(){

	}
	
	function db_update($q, $v){
		global $Db, $dbPDO;

		$Db->pdo_query($q,array(),$dbPDO);

		$qu = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($qu,array($v, 'current_version_db'),$dbPDO);

		return true;
	}

	function random_password( $length = 8, $incspecialchars = false ) {
	    $chars = "bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ123456789";
	    if($incspecialchar){
		    $chars .= "!@#$%^&*()_-=+;:,.?";
	    }
	    $password = substr( str_shuffle( $chars ), 0, $length );
	    return $password;
	}
}
?>