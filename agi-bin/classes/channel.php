<?php

namespace Telecube;


class Channel{


	function dialstatus(){
		global $Agi;
		return $Agi->get_variable("DIALSTATUS",true);
	}


}
?>