<?php
require("../init.php");
$system_info = array();
$system_info["public_address"] 				= file_get_contents($Config->get("ip_check_url"));
$system_info["pbx_host_ip"] 				= $Common->get_pref("pbx_host_ip");
$system_info["pbx_nat_is_natted"] 			= $Common->get_pref("pbx_nat_is_natted");
$system_info["pbx_nat_external_ip"] 		= $Common->get_pref("pbx_nat_external_ip");
$system_info["pbx_nat_localnet"] 			= $Common->get_pref("pbx_nat_localnet");
$system_info["pbx_nat_public_ip_static"] 	= $Common->get_pref("pbx_nat_public_ip_static");
$system_info["current_version_git"] 		= $Common->get_pref("current_version_git");
$system_info["current_version_db"] 			= $Common->get_pref("current_version_db");
$system_info["current_version_system"] 		= $Common->get_pref("current_version_system");

if($system_info["public_address"] != $system_info["pbx_nat_external_ip"]){
	$ip_alert = "The actual public ip address differs from the system record.";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>

		<script type="text/javascript">

		$(document).ready(function() {

			$('[data-toggle="confirmation"]').confirmation({onConfirm: function(){$("#form-debug-send-data").submit();}, popout: true, singleton: true, animation: true });

		});
		
		</script>
	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1><span class="glyphicon glyphicon-console"></span> Debugging</h1>
			
			<div class="row">
				<div class="col-lg-7">
					<div class="well">
						<p>Use this section to get debug info about the server and send data back to Telecube API.</p>
					</div>
				</div>
				<div class="col-lg-5">
					<button type="submit" class="btn btn-success btn-block" data-toggle="confirmation" data-title="Send this debug data now?">Securely Send Debug Info to Telecube HTTPS API</button>
					<p></p>
					<div class="alert alert-success" role="alert" style="display:<?php echo isset($_GET['status']) && strtolower($_GET['status']) == "ok" ? "" : "none";?>">
						<strong>Success:</strong> Data sent successfully
					</div>
				</div>
			</div>

			<form id="form-debug-send-data" method="post" action="send-debug-data.php">
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">System Information</h3>
				  </div>
				  <div class="panel-body">
				  	<p>Public Address: <?php echo $system_info["public_address"];?></p>
				  	<p>PBX External IP: <?php echo $system_info["pbx_nat_external_ip"];?></p>
					<div class="alert alert-warning" role="alert" style="display:<?php echo isset($ip_alert) ? "" : "none";?>">
						<strong>Warning:</strong> <?php echo isset($ip_alert) ? $ip_alert : "";?>
					</div>
				  	<p>PBX Host IP: <?php echo $system_info["pbx_host_ip"];?></p>
				  	<p>PBX Is Natted: <?php echo $system_info["pbx_nat_is_natted"];?></p>
				  	<p>PBX Localnet: <?php echo $system_info["pbx_nat_localnet"];?></p>
				  	<p>PBX Static IP: <?php echo $system_info["pbx_nat_public_ip_static"];?></p>
				  	<p>Current GIT Version: <?php echo $system_info["current_version_git"];?></p>
				  	<p>Current DB Version: <?php echo $system_info["current_version_db"];?></p>
				  	<p>Current System Version: <?php echo $system_info["current_version_system"];?></p>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Iptables Output</h3>
				  </div>
				  <div class="panel-body">
					<code id="iptables-output">
						<?php 
							$last = exec('sudo /sbin/iptables -nL', $o, $r);  
							print nl2br(htmlentities(implode("\n", $o)));
							$j = count($o);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-ip-tables[]" id="debug-input-ip-tables" value="'.$o[$i].'">';
							}
						
							// load the system info here too
							$si_arr_keys = array_keys($system_info);
							$j = count($si_arr_keys);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-system-info['.$si_arr_keys[$i].']" id="debug-input-system-info" value="'.$system_info[$si_arr_keys[$i]].'">';
							}
						?>
					</code>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Asterisk Loaded Modules</h3>
				  </div>
				  <div class="panel-body">
					<code id="asterisk-loaded-modules">
						<?php 
							$last = exec('sudo /usr/sbin/asterisk -x "module show"', $o1, $r1);  
							print nl2br(htmlentities(implode("\n", $o1)));
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-asterisk-modules[]" id="debug-input-asterisk-modules" value="'.$o1[$i].'">';
							}
						?>
					</code>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Trunks</h3>
				  </div>
				  <div class="panel-body">
					<?php 
						$trunks = $Trunk->list_trunks();
						$Common->ecco( $trunks );
						$j = count($trunks);
						for($i=0;$i<$j;$i++) { 
							$tr_arr_keys = array_keys($trunks[$i]);
							$jj = count($tr_arr_keys);
							for($ii=0;$ii<$jj;$ii++) { 
								echo '<input type="hidden" name="debug-input-trunks['.$i.']['.$tr_arr_keys[$ii].']" value="'.$trunks[$i][$tr_arr_keys[$ii]].'">';
							}
						}
					?>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Sip Config</h3>
				  </div>
				  <div class="panel-body">
					<code id="asterisk-loaded-modules">
						<?php 
							$last = exec('sudo /bin/cat /etc/asterisk/sip.conf', $o2, $r);  
							print nl2br(htmlentities(implode("\n", $o2)));
							$j = count($o2);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-sip-conf[]" value="'.$o2[$i].'">';
							}
						?>
					</code>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Sip-Network Config</h3>
				  </div>
				  <div class="panel-body">
					<code id="asterisk-loaded-modules">
						<?php 
							$last = exec('sudo /bin/cat /etc/asterisk/sip-network.conf', $o3, $r);  
							print nl2br(htmlentities(implode("\n", $o3)));
							$j = count($o3);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-sip-network-conf[]" value="'.$o3[$i].'">';
							}
						?>
					</code>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Sip-Conf Config</h3>
				  </div>
				  <div class="panel-body">
					<code id="asterisk-loaded-modules">
						<?php 
							$last = exec('sudo /bin/cat /etc/asterisk/sip-conf.conf', $o3a, $r);  
							print nl2br(htmlentities(implode("\n", $o3a)));
							$j = count($o3a);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-sip-conf-conf[]" value="'.$o3a[$i].'">';
							}
						?>
					</code>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Sip-Register Config</h3>
				  </div>
				  <div class="panel-body">
					<code id="asterisk-loaded-modules">
						<?php 
							$last = exec('sudo /bin/cat /etc/asterisk/sip-register.conf', $o4, $r);  
							print nl2br(htmlentities(implode("\n", $o4)));
							$j = count($o4);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-sip-register-conf[]" value="'.$o4[$i].'">';
							}
						?>
					</code>
				  </div>
				</div>

				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Sip-Trunks Config</h3>
				  </div>
				  <div class="panel-body">
					<code id="asterisk-loaded-modules">
						<?php 
							$last = exec('sudo /bin/cat /etc/asterisk/sip-trunks.conf', $o5, $r);  
							print nl2br(htmlentities(implode("\n", $o5)));
							$j = count($o5);
							for($i=0;$i<$j;$i++) { 
								echo '<input type="hidden" name="debug-input-sip-trunks-conf[]" value="'.$o5[$i].'">';
							}
						?>
					</code>
				  </div>
				</div>



			</form>


		</div>


	</body>
</html>