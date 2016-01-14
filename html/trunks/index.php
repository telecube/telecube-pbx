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
		?>
			$(function(){$("#active-<?php echo $trunks[$i]['id'];?>").editable({
					value: "<?php echo $Trunk->is_active($trunks[$i]['id']);?>",
					source: [{value: 'no', text: 'Inactive'},{value: 'yes', text: 'Active'}], 
					success: function(data){ disable_active(<?php echo $trunks[$i]['id'];?>, data); } 
				});});
			$(function(){$("#description-<?php echo $trunks[$i]['id'];?>").editable();});
			$(function(){$("#host_address-<?php echo $trunks[$i]['id']?>").editable({disabled: <?php echo $disable_editable;?>});});
			$(function(){$("#username-<?php echo $trunks[$i]['id'];?>").editable({disabled: <?php echo $disable_editable;?>});});
			$(function(){$("#password-<?php echo $trunks[$i]['id'];?>").editable({disabled: <?php echo $disable_editable;?>});});
			
			$("#toggle-active-<?php echo $trunks[$i]['id'];?>").change(function() {trunk_toggle_active($(this),<?php echo $trunks[$i]['id'];?>);});
		
		<?php } ?>


		$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });



	});


	function trunk_toggle_active(obj, trunk_id){
	//	alert(obj.prop('checked'));
		var value = obj.prop('checked') ? "yes" : "no";
		$.post("/trunks/update.php", { pk: trunk_id, name: "active-"+trunk_id, value: value },
			function(data){
			//	alert(data);
				var results = new Array();
				try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
				// do stuff here
				if(results['status'] == "OK"){
					
					disable_active(trunk_id, results['value']);

				}else{
					alert(data);
				}
		});
	}

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

	function trunk_register_status(){
		if(navigator.userAgent.indexOf("Safari") > 0){$.ajaxSetup({async: false});}
		$.post("/trunks/register-status.php", { },
			function(data){
		//		alert(data);
				var results = new Array();
				try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
				// do stuff here
				if(results['status'] == "OK"){
					for (var i = 0; i < results['data'].length; i++) {
						$("#button-reg-status-"+results['data'][i]['id']).html(results['data'][i]['status']);
						$("#button-reg-status-"+results['data'][i]['id']).removeClass( "btn-success btn-warning" ).addClass( results['data'][i]['btn-class'] );;
					};



					/* 
					{
					"status":"OK",
					"data":[
							{"id":"40","status":"Registered","btn-class":"btn-success"},
							{"id":"42","status":"Not Registered","btn-class":"btn-warning"},
							{"id":"45","status":"Not Registered","btn-class":"btn-warning"}
						]
					}
					*/
				}else{
					alert(data);
				}

				setTimeout(function(){ trunk_register_status(); }, 1000);
		});
	}

	function run(){
		trunk_register_status();
	}
	</script>

	</head>
	<body onload="run();">
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
				$toggleCheckedStatus = $trunks[$i]['active'] == "yes" ? " checked" : "";
			?>
				<div class="col-lg-4">

					<form method="post" action="add-new.php">

					<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Trunk: <?php echo $trunks[$i]['name'];?> <button id="button-reg-status-<?php echo $trunks[$i]['id'];?>" class="btn btn-xs btn-default pull-right">Checking..</button></h3>
					</div>
					<div class="panel-body">

						<p>Description: <a href="#" id="description-<?php echo $trunks[$i]['id'];?>" data-type="text" data-pk="<?php echo $trunks[$i]['id'];?>" data-url="update.php" data-title="Description"><?php echo $trunks[$i]['description'];?></a></p>
						<p>Auth Type: <?php echo ucwords($trunks[$i]['auth_type']);?></p>
						<p>Active Status: <input type="checkbox" id="toggle-active-<?php echo $trunks[$i]['id'];?>" data-toggle="toggle" data-onstyle="success" data-size="mini" data-on="Active" data-off="Inactive" <?php echo $toggleCheckedStatus;?>></p>
						
						<span id="">
						<hr>
							<p><em>Not editable when trunk is active.</em></p>
							<p>Host Address: <a href="#" id="host_address-<?php echo $trunks[$i]['id'];?>" data-type="text" data-pk="<?php echo $trunks[$i]['id'];?>" data-url="update.php" data-title="SIP Provider URL"><?php echo $trunks[$i]['host_address'];?></a></p>
							<p>Auth Name: <a href="#" id="username-<?php echo $trunks[$i]['id'];?>" data-type="text" data-pk="<?php echo $trunks[$i]['id'];?>" data-url="update.php" data-title="Auth Username"><?php echo $trunks[$i]['username'];?></a></p>
							<p>Password: <a href="#" id="password-<?php echo $trunks[$i]['id'];?>" data-type="text" data-pk="<?php echo $trunks[$i]['id'];?>" data-url="update.php" data-title="Password"><?php echo $trunks[$i]['password'];?></a></p>

						<hr>
						</span>

						<a class="btn btn-sm btn-danger" data-toggle="confirmation" data-title="Really, delete this trunk?" data-href="delete.php?id=<?php echo $trunks[$i]['id'];?>" data-original-title="" title="">Delete</a>

					</div>
					</div>



				</div>
			
			<?php
				echo $x==2 || $i == $j-1 ? "\t".'</div>'."\n" : "";
				$x++;
				$x=$x==3?0:$x;
			}
			?>

			</div>

	</body>
</html>