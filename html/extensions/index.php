<?php
require("../init.php");

// get a list of current extensions
$sip_devices 	= $Ext->list_extensions();
$extensions 	= $Ext->get_names($sip_devices);

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
				$sip_devices[$i]['bar_int'] == "y" ? $bar_int = "1" : $bar_int = "0";
				echo '$(function(){$(\'#bar_int-'.$sip_devices[$i]['name'].'\').editable({value: '.$bar_int.',source: [{value: 0, text: \'No\'},{value: 1, text: \'Yes\'}]});});';
				$sip_devices[$i]['bar_mobile'] == "y" ? $bar_mobile = "1" : $bar_mobile = "0";
				echo '$(function(){$(\'#bar_mobile-'.$sip_devices[$i]['name'].'\').editable({value: '.$bar_mobile.',source: [{value: 0, text: \'No\'},{value: 1, text: \'Yes\'}]});});';
				$sip_devices[$i]['bar_fixed'] == "y" ? $bar_fixed = "1" : $bar_fixed = "0";
				echo '$(function(){$(\'#bar_fixed-'.$sip_devices[$i]['name'].'\').editable({value: '.$bar_fixed.',source: [{value: 0, text: \'No\'},{value: 1, text: \'Yes\'}]});});';
				$sip_devices[$i]['bar_13'] == "y" ? $bar_13 = "1" : $bar_13 = "0";
				echo '$(function(){$(\'#bar_13-'.$sip_devices[$i]['name'].'\').editable({value: '.$bar_13.',source: [{value: 0, text: \'No\'},{value: 1, text: \'Yes\'}]});});';
			}
			?>
			$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });
		});

		function showAddNew(){
			$("#panel-extension-addnew").toggle(100);
		}

		function run(){
		//	setExtensionRegisterStatus();
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
						if(results["data"][results["exts"][i]] == "yes"){
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

								<button type="submit" class="btn btn-primary">Add New Extension</button>
							</div> 
						</div>
					</form>

				</div>

			</div>


			<?php
			$x=0;
			$j = count($sip_devices);
			for($i=0;$i<$j;$i++) { 
				$registered = $Asterisk->ext_status($sip_devices[$i]['name'], "expire") > 0 ? true : false;
				//$registered = $Ext->is_registered($sip_devices[$i]['name']);

				echo $x==0 ? '<div class="row">'."\n" : "";
				echo '<div class="col-lg-4">'."\n";

				echo '<form method="post" action="add-new.php">'."\n";

				echo '<div class="panel panel-default">'."\n";
				echo '<div class="panel-heading">'."\n";
				$regstatbtncolor = $registered ? "success" : "warning";
				$regstatbtntext = $registered ? "Registered" : "Not Registered";
				echo '<h3 class="panel-title">Ext: '.$sip_devices[$i]['name'].' <button type="button" id="btn-ext-register-status-'.$sip_devices[$i]['name'].'" class="btn btn-'.$regstatbtncolor.' btn-xs pull-right"><span id="span-ext-register-status-'.$sip_devices[$i]['name'].'">'.$regstatbtntext.'</span></button></h3>'."\n";
				echo '</div>'."\n";
				echo '<div class="panel-body">'."\n";

				echo '<p>Label: <a href="#" id="label-'.$sip_devices[$i]['name'].'" data-type="text" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Label">'.$sip_devices[$i]['label'].'</a></p>'."\n";
				echo '<p>Password: <a href="#" id="secret-'.$sip_devices[$i]['name'].'" data-type="text" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Password">'.$sip_devices[$i]['secret'].'</a></p>'."\n";
				echo '<p>PBX IP Address: '.$Common->get_pref("pbx_host_ip").'</p>'."\n";

	//			echo "<hr>";

	//			echo '<p>Outgoing Trunk: <a href="#" id="bar_int-'.$sip_devices[$i]['name'].'" data-type="select" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Bar International Calls"></a></p>'."\n";

				echo '<span id="">';
					echo '<hr>';
					//  echo "\t\t\t\t".'<p>Bar International Calls: <a href="#" id="bar_int-'.$sip_devices[$i]['name'].'" data-type="select" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Bar International Calls">'.$sip_devices[$i]['bar_int'].'</a></p>'."\n";
					echo '<p>Bar International Calls: <a href="#" id="bar_int-'.$sip_devices[$i]['name'].'" data-type="select" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Bar International Calls"></a></p>'."\n";
					echo '<p>Bar Mobile Calls: <a href="#" id="bar_mobile-'.$sip_devices[$i]['name'].'" data-type="select" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Bar Mobile Calls"></a></p>'."\n";
					echo '<p>Bar Fixed Calls: <a href="#" id="bar_fixed-'.$sip_devices[$i]['name'].'" data-type="select" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Bar Fixed Calls"></a></p>'."\n";
					echo '<p>Bar 13/1300 Calls: <a href="#" id="bar_13-'.$sip_devices[$i]['name'].'" data-type="select" data-pk="'.$sip_devices[$i]['name'].'" data-url="update.php" data-title="Bar 13/1300 Calls"></a></p>'."\n";

					echo '<hr>';
				echo '</span>';

				echo '<a class="btn btn-sm btn-danger" data-toggle="confirmation" data-title="Really, delete this extension?" data-href="delete.php?ext='.$sip_devices[$i]['name'].'" data-original-title="" title="">Delete</a>'."\n";

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