<?php
require("../init.php");

$eventmon_running = $Server->ami_eventmon_running();
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
		</script>
	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Manage Services</h1>
			
			<div class="well">
				<p>Manage system services here.</p>
			</div>


			<div class="row">
				<div class="col-lg-4">

					<div class="panel panel-default"> 
						<div class="panel-heading"> 
							<h3 class="panel-title">Asterisk Event Monitor <button class="btn btn-xs btn-<?php echo $eventmon_running ? "success" : "warning";?> pull-right"><?php echo $eventmon_running ? "Running" : "Stopped";?></button></h3> 
						</div> 
						<div class="panel-body" id="panel-manage-event-monitor">
							<p>If the extension and trunk registration statuses aren't changing you may need to restart the event monitor.</p>
							<a class="btn btn-sm btn-block btn-danger" data-toggle="confirmation" data-title="Really, restart this service?" data-href="restart-event-monitor.php" data-original-title="" title="">Restart Now</a>
						</div> 
					</div>

				</div>
			</div>

		</div>


	</body>
</html>