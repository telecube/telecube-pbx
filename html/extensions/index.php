<?php
require("../init.php");

// get a list of current extensions
$sip_devices 	= $Ext->list_extensions();
$extensions 	= $Ext->get_names($sip_devices);

$orig_trunks = $Trunk->list_trunks();

// get the active status
$j = count($orig_trunks);
for($i=0;$i<$j;$i++) { 
	$trunk_active_status[$orig_trunks[$i]['id']] = $orig_trunks[$i]['active'];
	$trunk_register_status[$orig_trunks[$i]['id']] = $orig_trunks[$i]['register_status'];
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
			//toggle `popup` / `inline` mode
			$.fn.editable.defaults.mode = 'popup';     

			<?php
			$j = count($sip_devices);
			for($i=0;$i<$j;$i++) { 
				echo '$("#label-'.$sip_devices[$i]['name'].'").editable();'."\n";
				echo '$("#secret-'.$sip_devices[$i]['name'].'").editable();'."\n";

			    echo '$( "#ext_trunk_list_'.$sip_devices[$i]['name'].'" ).sortable({ cursor: "crosshair", update: function(){ getList('.$sip_devices[$i]['name'].'); } });';
			    echo '$( "#ext_trunk_list_'.$sip_devices[$i]['name'].'" ).disableSelection();';

//			    $ext_routing = 
			//	$trunks = json_decode($Ext->get_routing($sip_devices[$i]['name']),true);
			   // $trunks = $orig_trunks;
				$get_routing 	= $Ext->get_routing($sip_devices[$i]['name']);
				$trunks 		= $get_routing !== false ? $get_routing : $orig_trunks;

				$jj = count($trunks);
				for($ii=0;$ii<$jj;$ii++) { 
					if(!isset($trunks[$ii]['allowed'])){
						$trunks[$ii]['allowed'] = "[]";
					}
					//\''.str_replace(array("No Calls","","null",null), '["fixed"]', $trunks[$ii]['allowed']).'\'"
					echo '$(function(){'."\n";
					echo '    $("#ext_allowed_calltypes_'.$sip_devices[$i]['name'].'-'.$trunks[$ii]['id'].'").editable({'."\n";
					echo '/* '.json_encode($trunks[$ii]).' */'."\n";
					$initValue = json_encode($trunks[$ii]['allowed']);
					echo '        value: \''.$initValue.'\', '."\n";   
					echo '        emptytext: "No Calls", '."\n";   
					echo '        source: ['."\n";
					echo '              {value: "all", text: "All Call Types"},'."\n";
					echo '              {value: "fixed", text: "Fixed Lines"},'."\n";
					echo '              {value: "mobile", text: "Mobiles"},'."\n";
					echo '              {value: "1300", text: "1300 Numbers"},'."\n";
					echo '              {value: "international", text: "International"}'."\n";
					echo '           ]'."\n";
					echo '    });'."\n";
					echo '});'."\n";
				}

			}
			?>

			$('.btn').tooltip();

			$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });


		    $('td').each(function(){
		        $(this).css('width', $(this).width() +'px');
		    });

		});

		function showAddNew(){
			$("#panel-extension-addnew").toggle(100);
		}

		function run(){
			setExtensionRegisterStatus();
		}

		function setExtensionRegisterStatus(){

			$.get( "ext-reg-stat.php", function( data ) {
			//	alert(data);
				var results = new Array();
				try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
				// do stuff here
				if(results['status'] == "OK"){
					for (var i = 0; i < results["exts"].length; i++) {
					//	alert(results["exts"][i]+' '+results["data"][results["exts"][i]]);
						if(results["data"][results["exts"][i]] == "Registered"){
							$("#btn-ext-register-status-"+results["exts"][i]).removeClass( "btn-warning" ).addClass( "btn-success" );
							$("#span-ext-register-status-"+results["exts"][i]).html( "Registered" );
						}else{
							$("#btn-ext-register-status-"+results["exts"][i]).removeClass( "btn-success" ).addClass( "btn-warning" );
							$("#span-ext-register-status-"+results["exts"][i]).html( "Not Registered" );
						}
					};
					setTimeout("setExtensionRegisterStatus();", 1000);
				}else{
				//	alert(data);
				}
			});				

		}


		function getList(ext){
			var optionTexts = [];
			$("#ext_trunk_list_"+ext+" td").each(function() { optionTexts.push($(this).text()) });
		//	alert(JSON.stringify(optionTexts));

			if(navigator.userAgent.indexOf("Safari") > 0){$.ajaxSetup({async: false});}
			$.post("/extensions/update-trunk-sorting.php", { ext: ext, arr:  JSON.stringify(optionTexts)},
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
		}

	</script>


	
	<script>
	
	</script>

	</head>
	<body onLoad="run();">
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Extensions</h1>
			
			<div class="well">
				<p>Extensions are the points on this PBX server that your voip devices connect to.</p>
			</div>


			<div class="row">

				<div class="col-lg-4">

					<form method="post" action="add-new.php">
						<div class="panel panel-default"> 
							<div class="panel-heading"> 
								<h3 class="panel-title"><a href="javascript:void(0);" onclick="showAddNew();">Add New Extension <span class="glyphicon glyphicon-plus pull-right"></span></a></h3> 
							</div> 
							<div class="panel-body" id="panel-extension-addnew" style="display:none;">
								<p>Select a new extension number.</p>

								<input type="hidden" name="extension_post_form" value="add_new">

								<select class="form-control" name="ext">
									<?php
									$j = 1000;
									for($i=0;$i<$j;$i++) { 
										$ext = $i+1000;
										if(!in_array($ext, $extensions)){
											echo "<option>".$ext."</option>";
										}
									}
									?>
								</select>            

								<p></p>

								<select class="form-control" name="password-type">
									<option value="crypto">Cryptographically Secure Password</option>
									<option value"keyboard">Keyboard Friendly Password</option>
								</select>            

								<p></p>

								<button type="submit" class="btn btn-primary">Add New Extension</button>
							</div> 
						</div>
					</form>

				</div>

				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<h3 class="panel-title">This PBX IP Address: <?php echo $Common->get_pref("pbx_host_ip");?></h3> 
						</div> 
					</div>
				</div>

			</div>


			<?php
			$orig_trunks = $trunks;
			$x=0;
			$j = count($sip_devices);
			for($i=0;$i<$j;$i++) { 
				//$registered = $Asterisk->ext_status($sip_devices[$i]['name'], "expire") > 0 ? true : false;
				$registered = $Ext->is_registered($sip_devices[$i]['name']);
				
				$get_routing 	= $Ext->get_routing($sip_devices[$i]['name']);
				$trunks 		= $get_routing !== false ? $get_routing : $orig_trunks;

				echo $x==0 ? '<div class="row">'."\n" : "";
				echo '<div class="col-lg-4">'."\n";

				echo '<div class="panel panel-default">'."\n";
				echo '<div class="panel-heading">'."\n";
				$regstatbtncolor = $registered ? "success" : "warning";
				$regstatbtntext = $registered ? "Registered" : "Not Registered";
				echo '<h3 class="panel-title">Ext: '.$sip_devices[$i]['name'].' <button type="button" id="btn-ext-register-status-'.$sip_devices[$i]['name'].'" class="btn btn-'.$regstatbtncolor.' btn-xs pull-right"><span id="span-ext-register-status-'.$sip_devices[$i]['name'].'">'.$regstatbtntext.'</span></button></h3>'."\n";
				echo '</div>'."\n";
				echo '<div class="panel-body">'."\n";

				echo '<p>Label: <a href="#" id="label-'.$sip_devices[$i]['name'].'" data-type="text" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Label">'.$sip_devices[$i]['label'].'</a></p>'."\n";
				echo '<p>Password: <a href="#" id="secret-'.$sip_devices[$i]['name'].'" data-type="text" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Password">'.$sip_devices[$i]['secret'].'</a></p>'."\n";

				echo '<span id="">';
					echo '<hr>';
					echo "<p><small>Drag to sort the trunks and set the allowed call types.</small></p>";
					echo '<table class="table table-striped">';
					echo '<tbody id="ext_trunk_list_'.$sip_devices[$i]['name'].'">';
					$jj = count($trunks);
					for($ii=0;$ii<$jj;$ii++) { 
						if(!empty($trunks[$ii]['id'])){
							$trunkactivecolour = $trunk_active_status[$trunks[$ii]['id']] == "yes" ? "success" : "warning";
							$trunkactivetooltip = $trunk_active_status[$trunks[$ii]['id']] == "yes" ? "Active" : "Inactive";
							$trunkactivetext = $trunk_active_status[$trunks[$ii]['id']] == "yes" 
								? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' 
								: '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
							$trunkregisteredcolour = $trunk_register_status[$trunks[$ii]['id']] == "Registered" ? "success" : "warning";
							$trunkregisteredtooltip = $trunk_register_status[$trunks[$ii]['id']] == "" ? "Unregistered" : $trunk_register_status[$trunks[$ii]['id']];
							$trunkregisteredtext = $trunk_register_status[$trunks[$ii]['id']] == "" || $trunk_register_status[$trunks[$ii]['id']] == "Unregistered" 
								? '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' 
								: '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
							/* <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> */
							/* <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> */
							echo '<tr class="ui-state-default">';
							echo '<td width="1%" style="display:none;">'.$trunks[$ii]['id'].'</td>';
							echo '<td width="5%"><div class="btn-group-vertical btn-group-xs"><button type="button" title="'.$trunkactivetooltip.'" data-placement="right" id="btn-ext-register-status-'.$trunks[$ii]['id'].'" class="btn btn-'.$trunkactivecolour.' btn-xs pull-left">'.$trunkactivetext.'</button><button class="btn btn-xs btn-'.$trunkregisteredcolour.' pull-right"  title="'.$trunkregisteredtooltip.'" data-placement="right">'.$trunkregisteredtext.'</button></div></td>';
							echo '<td><strong>'.$trunks[$ii]['name'].'</strong></td>';
							echo '<td width="35%"><a class="pull-left" href="#" id="ext_allowed_calltypes_'.$sip_devices[$i]['name'].'-'.$trunks[$ii]['id'].'" data-type="checklist" data-url="update-allowed-call-types.php" data-pk="'.$sip_devices[$i]['name'].'-'.$trunks[$ii]['id'].'" data-title="Select allowed call types"></a></td>';
							echo '</tr>';
						}
					}
					echo '</tbody>';
					echo '</table>';


					echo '<hr>';

				echo '</span>';

				echo '<a class="btn btn-sm btn-block btn-danger" data-toggle="confirmation" data-title="Really, delete this extension?" data-href="delete.php?ext='.$sip_devices[$i]['name'].'" data-original-title="" title="">Delete</a>'."\n";

				echo '</div>'."\n";
				echo '</div>'."\n";

				echo "\t\t".'</div>'."\n";
				echo $x==2 || $i == $j-1 ? "\t".'</div>'."\n" : "";
				$x++;
				$x=$x==3?0:$x;
			}
			?>
		</div>
	</body>
</html>