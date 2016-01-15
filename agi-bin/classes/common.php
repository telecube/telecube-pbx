<?php

namespace Telecube;


class Common{

	function is_voip_ext($n){
		if(strlen($n) == 4 && $n >= 1000 && $n <= 1999){
			return true;
		}
		return false;
	}

	// testing call to number type
	function callto_type($n){
		// call to a system extension
		if(strlen($n) == 4 && $n >= 1000 && $n <= 1999){
			return "voip";
		}
		// call to a pstn/did/fixed line number
		$ac = array("02","03","07","08","09");
		if(strlen($n) == 10 && in_array(substr($n, 0, 2), $ac)){
			return "fixed";
		}
		// call to a mobile number
		$ac = array("04");
		if(strlen($n) == 10 && in_array(substr($n, 0, 2), $ac)){
			return "mobile";
		}
		// call to a 13/1300 number
		if((strlen($n) == 10 || strlen($n) == 6) && substr($n, 0, 2) == "13"){
			return "1300";
		}
		// call to a 1800 number
		if(strlen($n) == 10 && substr($n, 0, 2) == "18"){
			return "1800";
		}

		return "unknown";
	}
}
?>