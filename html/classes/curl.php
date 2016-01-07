<?php

namespace Telecube;

class Curl{


	// the basic curl api function
	function http($url,$postArr,$httpAuthUser='',$httpAuthPass='',$timeout=60,$json_decode=false){
	    if(!isset($timeout)) $timeout=60;
	    $curl = curl_init();
	    $post = http_build_query($postArr);
	    if(isset($referer)){
	        curl_setopt ($curl, CURLOPT_REFERER, $referer);
	    }
	    curl_setopt ($curl, CURLOPT_URL, $url);
	    curl_setopt ($curl, CURLOPT_TIMEOUT, $timeout);
	    curl_setopt ($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322)');
	    curl_setopt ($curl, CURLOPT_HEADER, false);
	    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, false);
		if($httpAuthUser != '' && $httpAuthPass != ''){
			curl_setopt ($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
			curl_setopt ($curl, CURLOPT_USERPWD, $httpAuthUser.':'.$httpAuthPass);
	    }
		curl_setopt ($curl, CURLOPT_POST, true);
	    curl_setopt ($curl, CURLOPT_POSTFIELDS, $post);
	    curl_setopt ($curl, CURLOPT_HTTPHEADER,
	        array("Content-type: application/x-www-form-urlencoded"));
	    $html = curl_exec ($curl);
	    curl_close ($curl);
	    if($json_decode){
		    return json_decode($html);
	    }else{
		    return $html;
	    }
	}



}
?>