<?php

namespace Telecube;

class CubeAgi extends Agi{
	
	function wait($n){
		$this->exec("WAIT ".$n);
	}
	
	function answer(){
		$this->exec("ANSWER");
	}

	function playback($file='goodbye',$options=''){
		$this->exec("PLAYBACK ".$file.",".$options." ");
	}

	function progress(){
		$this->exec("EXEC PROGRESS");
	}
	
	function ringing(){
		$this->exec("EXEC RINGING");
	}
	
	function busy(){
		$this->exec("EXEC BUSY");
	}
	
	function dial($callto,$timeout=180,$type='SIP'){
		return $this->exec_dial($type, $callto, $timeout);
	}
	
	function tc_exec($command) {
		global $debug, $stdlog;
	
		fwrite(STDOUT, "$command\n");
		fflush(STDOUT);
		$result = trim(fgets(STDIN));
		$ret = array('code'=> -1, 'result'=> -1, 'timeout'=> false, 'data'=> '');
		if (preg_match("/^([0-9]{1,3}) (.*)/", $result, $matches)) {
			$ret['code'] = $matches[1];
			$ret['result'] = 0;
			if (preg_match('/^result=([0-9a-zA-Z]*)\s?(?:\(?(.*?)\)?)?$/', $matches[2], $match))  {
				$ret['result'] = $match[1];
				$ret['timeout'] = ($match[2] === 'timeout') ? true : false;
				$ret['data'] = $match[2];
			}
		}
		if ($debug && !empty($stdlog)) {
			$fh = fopen($stdlog, 'a');
			if ($fh !== false) {
				$res = $ret['result'] . (empty($ret['data']) ? '' : " / $ret[data]");
				fwrite($fh, "-------\n>> $command\n<< $result\n<<     parsed $res\n");
				fclose($fh);
			}
		}
		return $ret;
	}

	function addheader($hdrStr){
		return $this->exec("SIPAddHeader ".$hdrStr);
	}


}


?>