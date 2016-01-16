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
			for($i=0;$i<$j;$i++) { ?>
				$("#label-<?php echo $sip_devices[$i]['name'];?>").editable();
				$("#secret-<?php echo $sip_devices[$i]['name'];?>").editable();

			    $( "#ext_trunk_list_<?php echo $sip_devices[$i]['name'];?>" ).sortable({ 
			    		cursor: "crosshair", 
			    		update: function(){ getList(<?php echo $sip_devices[$i]['name'];?>); } 
			    });
			    $( "#ext_trunk_list_<?php echo $sip_devices[$i]['name'];?>" ).disableSelection();

				<?php
				$get_routing 	= $Ext->get_routing($sip_devices[$i]['name']);
				$trunks 		= $get_routing !== false ? $get_routing : $orig_trunks;

				$jj = count($trunks);
				for($ii=0;$ii<$jj;$ii++) { 
					if(!isset($trunks[$ii]['allowed'])){
						$trunks[$ii]['allowed'] = "[]";
					}
					$initValue = json_encode($trunks[$ii]['allowed']);
				?>
					$(function(){
					    $("#ext_allowed_calltypes_<?php echo $sip_devices[$i]['name'].'-'.$trunks[$ii]['id'];?>").editable({
					        value: '<?php echo $initValue;?>',  
					        emptytext: "Internal Only",  
					        source: [
					              {value: "all", text: "All Call Types"},
					              {value: "fixed", text: "Fixed Lines"},
					              {value: "mobile", text: "Mobiles"},
					              {value: "1300", text: "1300 Numbers"},
					              {value: "international", text: "International"}
					           ]
					    });
					});
			<?php
				}
			}
			?>

			$('.btn-xs').tooltip();

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
			trunk_register_status();
			trunk_active_status();
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

		function trunk_active_status(){
			$.post("/extensions/trunk-active-status.php", { },
				function(data){
			//		alert(data);
					var results = new Array();
					try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
					// do stuff here
					if(results['status'] == "OK"){
						for (var i = 0; i < results['data'].length; i++) {
							$(".btn-tas-"+results['data'][i]['id']).html(results['data'][i]['status']);
							$(".btn-tas-"+results['data'][i]['id']).removeClass( "btn-success btn-warning" ).addClass( results['data'][i]['btn-class'] );;
							$(".btn-tas-"+results['data'][i]['id']).attr('data-original-title',results['data'][i]['tooltip-title']);
						};
					}else{
						alert(data);
					}

					setTimeout(function(){ trunk_active_status(); }, 1000);
			});
		}

		function trunk_register_status(){
			$.post("/extensions/trunk-register-status.php", { },
				function(data){
			//		alert(data);
					var results = new Array();
					try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
					// do stuff here
					if(results['status'] == "OK"){
						for (var i = 0; i < results['data'].length; i++) {
							$(".btn-trs-"+results['data'][i]['id']).html(results['data'][i]['status']);
							$(".btn-trs-"+results['data'][i]['id']).removeClass( "btn-success btn-warning" ).addClass( results['data'][i]['btn-class'] );;
							$(".btn-trs-"+results['data'][i]['id']).attr('data-original-title',results['data'][i]['tooltip-title']);
						};
					}else{
						alert(data);
					}

					setTimeout(function(){ trunk_register_status(); }, 1000);
			});
		}


		function getList(ext){
			var optionTexts = [];
			$("#ext_trunk_list_"+ext+" td").each(function() { optionTexts.push($(this).text()) });
		//	alert(JSON.stringify(optionTexts));

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

				<div class="col-lg-4">
					<div class="panel panel-default">
						<div class="panel-heading"> 
							<h3 class="panel-title">External IP Address: <?php echo $Common->get_pref("pbx_nat_external_ip");?></h3> 
						</div> 
					</div>
				</div>

			</div>


			<?php
			$orig_trunks = $trunks;
			$x=0;
			$j = count($sip_devices);
			for($i=0;$i<$j;$i++) { 
				$registered = $Ext->is_registered($sip_devices[$i]['name']);
				$get_routing 	= $Ext->get_routing($sip_devices[$i]['name']);
				$trunks 		= $get_routing !== false ? $get_routing : $orig_trunks;
				$regstatbtncolor = $registered ? "success" : "warning";
				$regstatbtntext = $registered ? "Registered" : "Not Registered";

				echo $x==0 ? '<div class="row">'."\n" : "";
			?>
				<div class="col-lg-4">

				<div class="panel panel-default">
				<div class="panel-heading">
				<h3 class="panel-title">Ext: <?php echo $sip_devices[$i]['name'];?> <button type="button" id="btn-ext-register-status-<?php echo $sip_devices[$i]['name'];?>" class="btn btn-<?php echo $regstatbtncolor;?> btn-xs pull-right"><span id="span-ext-register-status-<?php echo $sip_devices[$i]['name'];?>"><?php echo $regstatbtntext;?></span></button></h3>
				</div>
				<div class="panel-body">

				<p>Label: <a href="#" id="label-<?php echo $sip_devices[$i]['name'];?>" data-type="text" data-pk="<?php echo $sip_devices[$i]['name'];?>" data-url="update.php" data-title="Label"><?php echo $sip_devices[$i]['label'];?></a></p>
				<p>Password: <a href="#" id="secret-<?php echo $sip_devices[$i]['name'];?>" data-type="text" data-pk="<?php echo $sip_devices[$i]['name'];?>" data-url="update.php" data-title="Password"><?php echo $sip_devices[$i]['secret'];?></a></p>

				<span id="">
					<hr>
					<p><small>Drag to sort the trunks and set the allowed call types.</small></p>
					<table class="table table-striped">
					<tbody id="ext_trunk_list_<?php echo $sip_devices[$i]['name'];?>">
					<?php
					$jj = count($trunks);
					for($ii=0;$ii<$jj;$ii++) { 
						if(!empty($trunks[$ii]['id'])){
					?>
							<tr class="ui-state-default">
							<td width="1%" style="display:none;"><?php echo $trunks[$ii]['id'];?></td>
							<td width="5%">
								<div class="btn-group-vertical btn-group-xs">
									<button class="btn btn-default btn-xs btn-tas-<?php echo $trunks[$ii]['id'];?> pull-left" data-placement="right">x</button>
									<button class="btn btn-xs btn-default pull-right btn-trs-<?php echo $trunks[$ii]['id'];?>" data-placement="right">x</button>
								</div>
							</td>
							<td><strong><?php echo $trunks[$ii]['name'];?></strong></td>
							<td width="40%"><a class="pull-left" href="#" id="ext_allowed_calltypes_<?php echo $sip_devices[$i]['name'].'-'.$trunks[$ii]['id'];?>" data-type="checklist" data-url="update-allowed-call-types.php" data-pk="<?php echo $sip_devices[$i]['name'].'-'.$trunks[$ii]['id'];?>" data-title="Select allowed call types"></a></td>
							</tr>
					<?php	
						}
					}
					?>
					</tbody>
					</table>


					<hr>

				</span>

				<a class="btn btn-sm btn-danger" data-toggle="confirmation" data-title="Really, delete this extension?" data-href="delete.php?ext=<?php echo $sip_devices[$i]['name'];?>" data-original-title="" title="">Delete</a>

				</div>
				</div>

				</div>
			<?php
				echo $x==2 || $i == $j-1 ? "\t".'</div>'."\n" : "";
				$x++;
				$x=$x==3?0:$x;
			}?>
		</div>
	</body>
</html>