<?php

namespace Telecube;


class Asterisk{

//	function ext_status($ext, $item = "expire"){
//		$stat = exec('sudo /usr/sbin/asterisk -rx "sip show peer '.$ext.'"', $o, $r);
//		
//		// if the item is an array we will return multiple entries
//		if(is_array($item)){
//			$list = array();
//			$j = count($item);
//			for($i=0;$i<$j;$i++) { 
//				$list[$item[$i]] = $this->item_from_sip_show($o, $item[$i]);
//			}
//			return $list;
//		}else{
//			return $this->item_from_sip_show($o, $item);
//		}
//	}

//	private function item_from_sip_show($o, $item){
//		// loop through the list and find the item
//		$item = strtolower($item);
//		$j = count($o);
//		for($i=0;$i<$j;$i++) { 
//			$line = $o[$i];
//			if(strpos(strtolower($line), $item) !== false){
//				// expand the result at the :
//				$parts = explode(":", $line, 2);
//				return trim($parts[1]);			
//			}
//		}
//		return "Not Found";
//	}
}

?>