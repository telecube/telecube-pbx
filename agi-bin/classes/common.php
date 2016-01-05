<?php

namespace Telecube;


class Common{

	function is_voip_ext($n){
		if(strlen($n) == 4 && $n >= 1000 && $n <= 1999){
			return true;
		}
		return false;
	}

}
?>