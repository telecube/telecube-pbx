<?php
require("../init.php");

$lh = $Linehunt->get_list();
//$Common->ecco($lh);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>


		<script type="text/javascript">

			$(document).ready(function(){
				$('[data-toggle="confirmation"]').confirmation({popout: true, singleton: true, animation: true });
			});

		</script>
	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1>Line Hunt Group</h1>
			<div class="well">
				<p>A Line Hunt Group allows you to set calls to overflow through a series of extensions until answered.</p>
			</div>

			<div class="row">
				
				<div class="col-lg-4">


					<form class="form-horizontal" method="post" action="add.php">
						<div class="panel panel-default"> 
							<div class="panel-heading"> 
								<h3 class="panel-title"><a href="javascript:void(0);" onclick="showAddNew();">Add New Line Hunt Group <span class="glyphicon glyphicon-plus pull-right"></span></a></h3> 
							</div> 
							<div class="panel-body" id="panel-trunk-addnew" style="display:;">

								<div class="form-group">
									<label for="trunk_name" class="col-sm-3 control-label">Name</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="linehunt_name" name="linehunt_name" maxlength="20" placeholder="Line Hunt Name">
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-9">
										<button type="submit" class="btn btn-default">Add New Line Hunt</button>
									</div>
								</div>

							</div> 
						</div>
					</form>

				</div>

			

			</div>



				<?php 
				$numcols = 3;
				$j = count($lh);
				for($i=0;$i<$j;$i++) { 
				$Common->html_rows("start",$i,$j,$numcols);
				?>
				
					<form method="post" action="edit.php">
						<input type="hidden" name="id" value="<?php echo $lh[$i]['id'];?>">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><?php echo $lh[$i]['name'];?> <button class="btn btn-xs btn-info pull-right">Edit</button></h3>
							</div>
							<div class="panel-body">
								
								<table class="table table-striped">
									<thead>
										<tr>
											<td>Description</td>
											<td>Timeout</td>
										</tr>
									</thead>
									<tbody>
										<?php
										$lh_data = json_decode($lh[$i]['data'], true);
										$jj = count($lh_data);
										for($ii=0;$ii<$jj;$ii++) { 
											echo '<tr>';
											echo '<td>'.$lh_data[$ii]['description'].'</td>';
											echo '<td>'.$lh_data[$ii]['timeout'].'</td>';
											echo '</tr>';
										}
										if($jj == 0){
											echo '<tr><td colspan="2">No Records</td></tr>';
										}
										?>
									</tbody>
								</table>

								<a class="btn btn-sm btn-danger" data-toggle="confirmation" data-title="Really, delete this line hunt?" data-href="delete.php?id=<?php echo $lh[$i]['id'];?>" data-original-title="" title="">Delete</a>

							</div>
						
						</div>
					
					</form>

				<?php 
				$Common->html_rows("end",$i,$j,$numcols);
				} 
				?>



		</div>


	</body>
</html>