<?php
require("../init.php");

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

				$(function(){$('#pbx_default_timezone').editable({
					value: '<?php echo $Common->get_pref("pbx_default_timezone");?>',
					source: [
								<?php
								$tzList = timezone_identifiers_list();
								$j = count($tzList);
								for($i=0;$i<$j;$i++) { 
									echo "{value: '".$tzList[$i]."', text: '".$tzList[$i]."'},";
								}
								?>
							]
					});
				});

				$(function(){$('#pbx_nat_localnet').editable({
					value: '<?php echo $Common->get_pref("pbx_nat_localnet");?>',
					source: [
								{value: '192.168.0.0/16', text: '192.168.0.0/16'},
								{value: '172.16.0.0/12', text: '172.16.0.0/12'},
								{value: '10.0.0.0/8', text: '10.0.0.0/8'},
							]
					});
				});

				$(function(){$('#pbx_nat_is_natted').editable({
					value: '<?php echo $Common->get_pref("pbx_nat_is_natted");?>',
					source: [
								{value: 'yes', text: 'Yes - Server Is Local'},
								{value: 'no', text: 'No - Server Is Hosted'},
							]
					});
				});

				$(function(){$('#pbx_nat_public_ip_static').editable({
					value: '<?php echo $Common->get_pref("pbx_nat_public_ip_static");?>',
					source: [
								{value: 'yes', text: 'Yes - Public IP Is Static'},
								{value: 'no', text: 'No - Public IP Is Dynamic'},
							]
					});
				});

				$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });
			});

			function run(){
				dtUpdate();
			}

			function dtUpdate(){
				$.get( "dt-inc.php", function( data ) {
					$("#def-tz-time").html(data);
				});				
				setTimeout("dtUpdate();",1000);
			}

			function showAddNew(){
				$("#panel-extension-addnew").toggle(100);
			}
		</script>

	</head>
	<body onLoad="run();">
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1><span class="glyphicon glyphicon-compressed"></span> Preferences</h1>
			<div class="well">
				<p>Configure preference options here.</p>
			</div>

			<div class="row">

				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">Default Timezone <span id="def-tz-time" class="pull-right"><?php include("dt-inc.php");?></span></h3> 
						</div> 
						<div class="panel-body" id="panel-preferences-default-timezone">
							<p><a href="#" id="pbx_default_timezone" data-type="select" data-pk="pbx_default_timezone" data-url="update.php" data-title="Default Timezone"></a></p>
						</div> 
					</div>

				</div>


			</div>

			<hr>

			<div class="row">

				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">PBX Is Local</h3> 
						</div> 
						<div class="panel-body" id="panel-preferences-pbx-is-natted">
							<p><a href="#" id="pbx_nat_is_natted" data-type="select" data-pk="pbx_nat_is_natted" data-url="update.php" data-title="PBX Is Local"></a></p>
						</div> 
					</div>

				</div>

				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">Local Network Range</h3> 
						</div> 
						<div class="panel-body" id="panel-preferences-pbx-localnetwork">
							<p><a href="#" id="pbx_nat_localnet" data-type="select" data-pk="pbx_nat_localnet" data-url="update.php" data-title="Local Network Range"></a></p>
						</div> 
					</div>

				</div>

				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">Public IP Is Static</h3> 
						</div> 
						<div class="panel-body" id="panel-preferences-pbx-ip-static">
							<p><a href="#" id="pbx_nat_public_ip_static" data-type="select" data-pk="pbx_nat_public_ip_static" data-url="update.php" data-title="Local Network Range"></a></p>
						</div> 
					</div>
				
				</div>

			</div>





		</div>


	</body>
</html>