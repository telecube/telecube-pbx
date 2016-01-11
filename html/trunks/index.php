<?php
require("../init.php");

$trunks = $Trunk->list_trunks();

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
	$j = count($trunks);
	for($i=0;$i<$j;$i++) { 
		$disable_editable = $Trunk->is_active($trunks[$i]['id']) == "yes" ? "true" : "false";
		echo '$(function(){$(\'#active-'.$trunks[$i]['id'].'\').editable({value: \''.$Trunk->is_active($trunks[$i]['id']).'\',source: [{value: \'no\', text: \'Inactive\'},{value: \'yes\', text: \'Active\'}], success: function(data){ disable_active('.$trunks[$i]['id'].', data); } });});';
		echo '$(function(){$("#description-'.$trunks[$i]['id'].'").editable();});'."\n";
		echo '$(function(){$("#host_address-'.$trunks[$i]['id'].'").editable({disabled: '.$disable_editable.'});});'."\n";
		echo '$(function(){$("#username-'.$trunks[$i]['id'].'").editable({disabled: '.$disable_editable.'});});'."\n";
		echo '$(function(){$("#password-'.$trunks[$i]['id'].'").editable({disabled: '.$disable_editable.'});});'."\n";
		
	}
	?>


	$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });






	});



	function disable_active(id, val){
		if(val == "yes"){
			$('#host_address-'+id).editable('option', 'disabled', true);
			$('#username-'+id).editable('option', 'disabled', true);
			$('#password-'+id).editable('option', 'disabled', true);
		}else{
			$('#host_address-'+id).editable('option', 'disabled', false);
			$('#username-'+id).editable('option', 'disabled', false);
			$('#password-'+id).editable('option', 'disabled', false);
		}
	}


	function showAddNew(){
		$("#panel-trunk-addnew").toggle(100);
		$("#panel-trunk-addnew-info").toggle(100);
		$("#trunk-add-err-alert").fadeOut(100);
	}

	function checkAuthType(type){
		if(type == 'ip'){
			$("#panel-trunk-addnew-password").fadeOut();
		}else{
			$("#panel-trunk-addnew-password").fadeIn();
		}
	}
	</script>

	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Trunks</h1>
			<div class="well">
				<p>Trunks are the connections between this PBX server and voip providers or other servers.</p>
			</div>

			<div class="row">
				
				<div class="col-lg-7">


					<form class="form-horizontal" method="post" action="add.php">
						<div class="panel panel-default"> 
							<div class="panel-heading"> 
								<h3 class="panel-title"><a href="javascript:void(0);" onclick="showAddNew();">Add New Trunk <span class="glyphicon glyphicon-plus pull-right"></span></a></h3> 
							</div> 
							<div class="panel-body" id="panel-trunk-addnew" style="display:none;">

								<div class="form-group">
									<label for="trunk_name" class="col-sm-3 control-label">Trunk Name</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="trunk_name" name="trunk_name" placeholder="Trunk Name">
									</div>
								</div>

								<div class="form-group">
									<label for="auth_type" class="col-sm-3 control-label">Auth Type</label>
									<div class="col-sm-9">
										<select class="form-control" name="auth_type" onChange="checkAuthType(this.value);">
											<option value="password">Password Authenticated (Register)</option>
											<option value="ip" disabled>IP Authenticated</option>
										</select>            
									</div>
								</div>

								<div class="form-group">
									<label for="trunk_auth_name" class="col-sm-3 control-label">Auth Name</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="trunk_auth_name" name="trunk_auth_name" placeholder="Auth (User) Name">
									</div>
								</div>

								<div class="form-group" id="panel-trunk-addnew-password">
									<label for="trunk_pass" class="col-sm-3 control-label">Password</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="trunk_pass" name="trunk_pass" placeholder="Password">
									</div>
								</div>

								<div class="form-group">
									<label for="trunk_url" class="col-sm-3 control-label">Host Address</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="trunk_url" name="trunk_url" placeholder="Host Address">
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
										<button type="submit" class="btn btn-default">Add New Trunk</button>
									</div>
								</div>

							</div> 
						</div>
					</form>

				</div>
				<div class="col-lg-5">

					<div class="panel panel-default">
					  <div class="panel-heading">
					    <h3 class="panel-title">&nbsp;</h3>
					  </div>
					  <div class="panel-body" id="panel-trunk-addnew-info" style="display:none;">
					    <p>The trunk name is important in as much as it must be unique and can only consist of letters and numbers. It serves as an identifier within the PBX when making and receiving calls. It doesn't have to be named with any relation to the provider.</p>
					  	<p>Eg; Trunk 1 or Primary Trunk are perfectly acceptable names.</p>
					  </div>
					</div>

				</div>
			

			</div>

			<div class="row">
				<div class="col-lg-7">
					<div id="trunk-add-err-alert" class="alert alert-warning" role="alert" style="display:<?php echo isset($_GET['err']) ? "" : "none";?>">
						<strong>Error:</strong> <?php echo isset($_GET['err']) ? $_GET['err'] : "";?>
					</div>
				</div>
			</div>

			<?php
			$x=0;
			$j = count($trunks);
			for($i=0;$i<$j;$i++) { 
				echo $x==0 ? '<div class="row">'."\n" : "";
				echo '<div class="col-lg-4">'."\n";

				echo '<form method="post" action="add-new.php">'."\n";

				echo '<div class="panel panel-default">'."\n";
				echo '<div class="panel-heading">'."\n";
				echo '<h3 class="panel-title">Trunk: '.$trunks[$i]['name'].'</h3>'."\n";
				echo '</div>'."\n";
				echo '<div class="panel-body">'."\n";



				echo '<p>Description: <a href="#" id="description-'.$trunks[$i]['id'].'" data-type="text" data-pk="'.$trunks[$i]['id'].'" data-url="update.php" data-title="Description">'.$trunks[$i]['description'].'</a></p>'."\n";
				echo '<p>Auth Type: '.ucwords($trunks[$i]['auth_type']).'</p>';
				echo '<p>Active Status: <a href="#" id="active-'.$trunks[$i]['id'].'" data-type="select" data-pk="'.$trunks[$i]['id'].'" data-url="update.php" data-title="Active Status"></a></p>'."\n";
				
				echo '<span id="">';
				echo '<hr>';
					echo '<p><em>Not editable when trunk is active.</em></p>';
					echo '<p>Host Address: <a href="#" id="host_address-'.$trunks[$i]['id'].'" data-type="text" data-pk="'.$trunks[$i]['id'].'" data-url="update.php" data-title="SIP Provider URL">'.$trunks[$i]['host_address'].'</a></p>'."\n";
					echo '<p>Auth Name: <a href="#" id="username-'.$trunks[$i]['id'].'" data-type="text" data-pk="'.$trunks[$i]['id'].'" data-url="update.php" data-title="Auth Username">'.$trunks[$i]['username'].'</a></p>'."\n";
					echo '<p>Password: <a href="#" id="password-'.$trunks[$i]['id'].'" data-type="text" data-pk="'.$trunks[$i]['id'].'" data-url="update.php" data-title="Password">'.$trunks[$i]['password'].'</a></p>'."\n";

				//  echo "\t\t\t\t".'<p>Bar International Calls: <a href="#" id="bar_int-'.$trunks[$i]['name'].'" data-type="select" data-pk="'.$trunks[$i]['name'].'" data-url="update.php" data-title="Bar International Calls">'.$trunks[$i]['bar_int'].'</a></p>'."\n";
				//         echo '<p>Bar International Calls: <a href="#" id="bar_int-'.$trunks[$i]['name'].'" data-type="select" data-pk="'.$trunks[$i]['name'].'" data-url="update.php" data-title="Bar International Calls"></a></p>'."\n";
				//         echo '<p>Bar Mobile Calls: <a href="#" id="bar_mobile-'.$trunks[$i]['name'].'" data-type="select" data-pk="'.$trunks[$i]['name'].'" data-url="update.php" data-title="Bar Mobile Calls"></a></p>'."\n";
				//         echo '<p>Bar Fixed Calls: <a href="#" id="bar_fixed-'.$trunks[$i]['name'].'" data-type="select" data-pk="'.$trunks[$i]['name'].'" data-url="update.php" data-title="Bar Fixed Calls"></a></p>'."\n";
				//         echo '<p>Bar 13/1300 Calls: <a href="#" id="bar_13-'.$trunks[$i]['name'].'" data-type="select" data-pk="'.$trunks[$i]['name'].'" data-url="update.php" data-title="Bar 13/1300 Calls"></a></p>'."\n";

				echo '<hr>';
				echo '</span>';

				echo '<a class="btn btn-sm btn-danger" data-toggle="confirmation" data-title="Really, delete this trunk?" data-href="delete.php?id='.$trunks[$i]['id'].'" data-original-title="" title="">Delete</a>'."\n";

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