<?php
require("../init.php");
$system_info = array();
$system_info["public_address"] 				= file_get_contents('https://www.telecube.com.au/api/apps/ip-check.php');
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

			$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });

		
		});
		
		function sendDebug(){
			var iptables = $("#debug-input-ip-tables").val();
			var loadedmodules = $("#debug-input-asterisk-loaded-modules").val();
			var trunks = $("#debug-input-trunks").val();
			$.post("/debugging/send-debug-data.php", { iptables: iptables, loadedmodules: loadedmodules, trunks: trunks },
				function(data){
				//	alert(data);
					var results = new Array();
					try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
					// do stuff here
					if(results['status'] == "OK"){

					}else{
						alert(data);
					}
			});
			//location.reload();
		}
		</script>
	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Debugging</h1>
			
			<div class="row">
				<div class="col-lg-7">
					<div class="well">
						<p>Use this section to get debug info about the server and send data back to Telecube API.</p>
					</div>
				</div>
				<div class="col-lg-5">
					<button style="display:none" type="submit" class="btn btn-success btn-block" data-toggle="confirmation" data-title="Send this debug data now?" data-href="javascript:sendDebug();" data-original-title="" title="">Securely Send Debug Info to Telecube HTTPS API</button>
				</div>
			</div>


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
					?>
				</code>
				<input type="hidden" id="debug-input-ip-tables" value="<?php echo json_encode($o);?>">
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
					?>
				</code>
				<input type="hidden" id="debug-input-asterisk-loaded-modules" value="<?php echo $o1;?>">
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
				?>
				<input type="hidden" id="debug-input-trunks" value="<?php echo $trunks;?>">
			  </div>
			</div>



		</div>


	</body>
</html>