<?php
require("../init.php");

// get the ssh ports
$q = "select * from preferences where name LIKE 'fw_%';";
$data = array();
$res = $Db->pdo_query($q,$data,$dbPDO);
$j = count($res);
for($i=0;$i<$j;$i++) { 
	$$res[$i]['name'] = $res[$i]['value'];
}
$fw_whitelist_ips = json_decode($fw_whitelist_ips);
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
				//toggle `popup` / `inline` mode
				$.fn.editable.defaults.mode = 'popup';     
				
				$(function(){$("#fw_whitelist_ips").editable({
					success: function(){listIpTables();}
				})});

				$(function(){$('#fw_ssh_ports').editable({
						value: '<?php echo $Common->get_pref("fw_ssh_ports");?>',
						source: [
									<?php
										echo "{value: '22', text: 'Port 22 Only'},";
										echo "{value: '32122', text: 'Port 32122 Only'},";
										echo "{value: 'both', text: 'Both ports 22 and 32122'},";
									?>
								],
						success: function(){listIpTables();}
					});
				});

				$(function(){$('#fw_sip_ports').editable({
						value: '<?php echo $Common->get_pref("fw_sip_ports");?>',
						source: [
									<?php
										echo "{value: '5060', text: 'Open'},";
										echo "{value: 'off', text: 'Closed'},";
									?>
								],
						success: function(){listIpTables();}
					});
				});

				$(function(){$('#fw_rtp_ports').editable({
						value: '<?php echo $Common->get_pref("fw_rtp_ports");?>',
						source: [
									<?php
										echo "{value: '8000:55000', text: 'Open'},";
										echo "{value: 'off', text: 'Closed'},";
									?>
								],
						success: function(){listIpTables();}
					});
				});

				$(function(){$('#fw_https_ports').editable({
						value: '<?php echo $Common->get_pref("fw_https_ports");?>',
						source: [
									<?php
										echo "{value: '443', text: 'Open'},";
										echo "{value: 'off', text: 'Closed'},";
									?>
								],
						success: function(){listIpTables();}
					});
				});

				$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });
			});

			function showAddNew(){
				$("#panel-extension-addnew").toggle(100);
			}

			function listIpTables(){
				$.get( "list-iptables.php", function( data ) {
					$("#iptables-output").html(data);
				});				
			}
		</script>
	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1><span class="glyphicon glyphicon-fire"></span> Firewall</h1>
			<div class="well">
				<p>Configure firewall options here.</p>
			</div>


			<div class="row">
				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">SSH Ports</h3> 
						</div> 
						<div class="panel-body" id="panel-firewall-ssh-ports">
							<p>SSH access to the server is available on the standard port 22 or a non-standard port 32122</p>
							<p>Here you can select the port(s) you want to allow SSH access on</p>
							<p><a href="#" id="fw_ssh_ports" data-type="select" data-pk="fw_ssh_ports" data-url="update.php" data-title="SSH Ports"></a></p>
						</div> 
					</div>


				</div>
				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">SIP Ports</h3> 
						</div> 
						<div class="panel-body" id="panel-firewall-sip-ports">
							<p>SIP signalling is port 5060 which is open to the world by default.</p>
							<p>You can block port 5060 if you have added whitelisted IP address(es)</p>
							<p><a href="#" id="fw_sip_ports" data-type="select" data-pk="fw_sip_ports" data-url="update.php" data-title="SIP Ports"></a></p>
						</div> 
					</div>


				</div>
				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">RTP Ports</h3> 
						</div> 
						<div class="panel-body" id="panel-firewall-rtp-ports">
							<p>RTP media is port range 8000 - 55000 which is open to the world by default.</p>
							<p>You can block RTP ports if you have added whitelisted IP address(es)</p>
							<p><a href="#" id="fw_rtp_ports" data-type="select" data-pk="fw_rtp_ports" data-url="update.php" data-title="SIP Ports"></a></p>
						</div> 
					</div>


				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">Whitelist IP Addresses</h3> 
						</div> 
						<div class="panel-body" id="panel-firewall-whitelist-ips">
								<p>Add IP addresses to a whitelist, one per line.</p>
								<p>They can be single (10.0.1.2) or CIDR (10.0.1.0/24) format only.</p>
							<p><a href="#" id="fw_whitelist_ips" data-type="textarea" data-pk="fw_whitelist_ips" data-url="update.php" data-title="Whitelist IP Addresses"><?php echo implode("\n", $fw_whitelist_ips);?></a></p>
						</div> 
					</div>


				</div>
				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">HTTPS Access (This Webpage!)</h3> 
						</div> 
						<div class="panel-body" id="panel-firewall-https-ports">
							<p>HTTPS is port 443 which is open to the world by default.</p>
							<p>You can block HTTPS if you have added whitelisted IP address(es)</p>
							<p><a href="#" id="fw_https_ports" data-type="select" data-pk="fw_https_ports" data-url="update.php" data-title="HTTPS Ports"></a></p>
						</div> 
					</div>

				</div>
				<div class="col-lg-4">


				</div>
			</div>


			<div class="well">
				<h4>Iptables Output</h4>
				<code id="iptables-output"><?php include("list-iptables.php");?></code>
			</div>


		</div>


	</body>
</html>