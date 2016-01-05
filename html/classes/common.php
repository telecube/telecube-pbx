<?php

namespace Telecube;


class Common{


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