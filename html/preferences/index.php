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

			$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });
		});

		function showAddNew(){
			$("#panel-extension-addnew").toggle(100);
		}
	</script>

	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Preferences</h1>
			<div class="well">
				<p>Configure firewall options here.</p>
			</div>

			<div class="row">

				<div class="col-lg-4">

					<form method="post" action="add-new.php">
						<div class="panel panel-default"> 
							<div class="panel-heading"> 
								<h3 class="panel-title">Default Timezone</h3> 
							</div> 
							<div class="panel-body" id="panel-preferences-default-timezone">
								<p><a href="#" id="pbx_default_timezone" data-type="select" data-pk="pbx_default_timezone" data-url="update.php" data-title="Default Timezone"></a></p>
							</div> 
						</div>
					</form>

				</div>

			</div>


				



		</div>


	</body>
</html>