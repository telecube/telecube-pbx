<?php

namespace Telecube;

#// ensure this definition exists before running the script.
#if(!defined('MAIN_INCLUDED'))
#  exit("Not allowed here!");

date_default_timezone_set('Australia/Melbourne');

if(!file_exists("/opt/base_config.inc.php")){
	exit("There was an installation error, the base config file is missing!");
}

class Config{
	private $db_pass;

	public function __construct(){
		include("/opt/base_config.inc.php");
    	$this->db_pass = $mysql_root_pass; 
    }       

	public function get($varname){

		$db_name 			= "telecube";
		$db_host 			= "localhost";
		$db_port 			= "3306";
		$db_user 			= "root";
		$db_pass 			= $this->db_pass;

		$session_name 		= "TelecubePBX";

		$git_clone_path 	= "/opt/telecube-pbx";

		$api_url 			= "https://www.telecube.com.au/api/apps/cloud-pbx";

		return isset($$varname) ? $$varname : false;
	}
}

?>