<?php

namespace Telecube;


class Common{

	function sanitise_trunk_name($str,$repl=""){
		return preg_replace("/[^a-zA-Z0-9]+/", $repl, $str);
	}

	function set_pref($name, $value){
		global $Db, $dbPDO;
		$q = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($q,array($value, $name),$dbPDO);
	}

	function get_pref($name){
		global $Db, $dbPDO;
		$q = "select value from preferences where name = ?;";
		$res = $Db->pdo_query($q, array($name), $dbPDO);
		return $res[0]['value'];
	}

	function get_file_perm($fp){
		return substr(sprintf('%o', fileperms($fp)), -4);
	}

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

	function git_commit_id_from_log($arr){
		// get the commit id
		$commit_id = str_replace("commit ", "", $arr[0]);
		$commit_id = trim($commit_id);
		return $commit_id;
	}

	function system_update($com, $v){
		global $Db, $dbPDO;
		
		$com = exec($com);

		$qu = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($qu,array($v, 'current_version_system'),$dbPDO);

		return true;
	}
	
	function db_update($q, $v){
		global $Db, $dbPDO;

		$Db->pdo_query($q,array(),$dbPDO);

		$qu = "update preferences set value = ? where name = ?;";
		$Db->pdo_query($qu,array($v, 'current_version_db'),$dbPDO);

		return true;
	}

	function secure_password($length = 12){
		$pass = base64_encode(openssl_random_pseudo_bytes($length));
		return $pass;
	}

	function random_string( $length = 8, $incspecialchars = false ) {
	    $chars = "bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ123456789";
	    if($incspecialchar){
		    $chars .= "!@#$%^&*()_-=+;:,.?";
	    }
	    $str = substr( str_shuffle( str_shuffle( $chars ) ), 0, $length );
	    return $str;
	}

	function html_rows($section, $iteration, $count, $numcols){
		$iteration = $iteration + 1;
		
		if($section == "start"){
			if($iteration == 1){
				echo '<div class="row"> <!-- open row //-->'."\n";
			}
			echo '<div class="col-lg-'.(12/$numcols).'">'."\n";
		}

		if($section == "end"){
			echo '</div>'."\n";
			// if we are at the nth iteration
			if($iteration % $numcols === 0){
				// close and open the row
				echo '</div> <!-- close row //-->'."\n";
				if($iteration != $count){
					echo '<div class="row"> <!-- open row //-->'."\n";
				}
			}
			// if iteration = count close the row
			if($iteration == $count){
				echo '</div> <!-- close row //-->'."\n";
			}
		}
	}


	// debugging helper
	function ecco($s){
		echo "<pre>";
		print_r($s);
		echo "</pre>";
	}
}
?>