<?php

namespace Telecube;


class Asterisk{
	
	function ext_status($ext, $item = "expire"){
		$stat = exec('sudo /usr/sbin/asterisk -rx "sip show peer '.$ext.'"', $o, $r);
		// loop through the list and find the item
		$j = count($o);
		for($i=0;$i<$j;$i++) { 
			$line = strtolower($o[$i]);
			if(strpos($line, $item) !== false){
				// expand the result at the :
				$parts = explode(":", $line);
				return trim($parts[1]);			
			}
		}
		return "Not Found";
	}

}

?>