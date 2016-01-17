<?php
require("../init.php");
if(!isset($_POST["id"])){

}
$id = $_POST["id"];
$lh = $Linehunt->get_linehunt($id);
if(!$lh){
	header("Location: /line-hunt/");
	exit;
}

$exts = $Ext->list_extensions();

$trunks = $Trunk->list_trunks();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="functions.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {

				init_edit_table();

				init_external_number_panel();

				$('.btn-xs').tooltip();

			});
		</script>
	</head>
	<body>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/top-menu.php");?>

		<div class="container">

			<h1><span class="glyphicon glyphicon-retweet"></span> Line Hunt Group <small>Editing: <?php echo $lh['name'];?></small></h1>
			<div class="well">
				<p>Add services onto the Line Hunt and drag to sort them into the order of priority. Use the delete button to remove it from the list.</p>
			</div>





			<div class="row">
				
				<div class="col-lg-4">
					
					


					<ol class="breadcrumb">
						<li><a href="/line-hunt/">Line Hunt Group</a></li>
						<li class="active">Editing: <?php echo $lh['name'];?></li>
					</ol>


						<div class="panel panel-default">
						  <div class="panel-heading">
						    <h3 class="panel-title">Extensions</h3>
						  </div>
						  <div class="panel-body">
					  	<table class="table table-striped">
					  		<tbody id="exttest">
					  			<?php for($i=0;$i<count($exts);$i++) { ?>
						  			<tr id="add-extensions-<?php echo $exts[$i]['name'];?>">
						  				<td><?php echo $exts[$i]['name'];?></td>
						  				<td><?php echo $exts[$i]['label'];?></td>
						  				<td align="right"><button class="btn btn-xs btn-success" onclick="add_item('extension','<?php echo $exts[$i]['name'];?>','<?php echo $exts[$i]['label'];?>');"> Add <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button></td>
						  			</tr>
						  		<?php } ?>
					  		</tbody>
					  	</table>
						  </div>
						</div>

						<div class="panel panel-default">
						  <div class="panel-heading">
						    <h3 class="panel-title">External Number</h3>
						  </div>
						  <div class="panel-body">
							<table class="table table-striped">
						  		<tbody id="exttest">
					  				<tr>
						  				<td width="50%"><input id="lh-add-external" type="number"></td>
						  				<td align="right"><button class="btn btn-xs btn-success" onclick="add_item('external','','')">Add <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button></td>
						  			</tr>
					  			</tbody>
				  			</table>						  
					  		<button class="btn btn-xs btn-warning" onclick="external_reset_changes();" data-placement="top" data-title="Reset the trunks list below if you have made changes."><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Reset Changes</button>&nbsp;
					  		<button class="btn btn-xs btn-success" onclick="external_use_any_trunk();" data-placement="top" data-title="Clear the list below and set a flag to use any available trunk, including any trunks added in the future."><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Use Any Available Trunk</button>
							<table id="external-number-trunks" class="table table-striped">
						  		<thead>
						  			<tr>
						  				<td style="display:none;">id</td>
						  				<td colspan="2">Set outgoing trunk priority for this number</td>
						  			</tr>
						  		</thead>
						  		<tbody>
					  				<?php
				  					$j = count($trunks);
				  					for($i=0;$i<$j;$i++) { 
				  						echo '<tr>';
				  						echo '<td style="display:none;">'.$trunks[$i]['id'].'</td>';
				  						echo '<td>'.$trunks[$i]['name'].'</td>';
				  						echo '<td align="right"><button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>';
				  						echo '</tr>';
				  					}
					  				?>
					  			</tbody>
				  			</table>						  

					  		</div>
						</div>

						<div class="panel panel-default" style="display:none;">
						  <div class="panel-heading">
						    <h3 class="panel-title">Ring Groups (Queues)</h3>
						  </div>
						  <div class="panel-body">
						    Panel content
						  </div>
						</div>



				</div>
				<div class="col-lg-8">


					<div class="panel panel-default">
					  <div class="panel-heading">
					    <h3 class="panel-title">Line Hunt - <?php echo $lh['name'];?> <button id="btn-save-changes" class="btn btn-xs btn-info pull-right" onclick="save_changes(<?php echo $lh['id'];?>);">Save Changes</button></h3>
					  </div>
					  <div class="panel-body">
					    

					  	<table id="linehuntEdit" class="table table-striped">
					  		<thead>
					  			<tr>
					  				<td style="display:none;">type</td>
					  				<td style="display:none;">id</td>
					  				<td style="display:none;">trunk_order</td>
					  				<td width="25%">Service Type</td>
					  				<td width="45%">Description</td>
					  				<td width="20%">Timeout</td>
					  				<td width="10%">&nbsp;</td>
					  			</tr>
					  		</thead>
					  		<tbody id="lh-tbody-list">
					  		<?php
				  				$lh_type_labels = array("extension" => "Voip Extension", "external" => "External Number");
				  				$lh_data = json_decode($lh['data'], true);
				  				$j = count($lh_data);
				  				for($i=0;$i<$j;$i++) { 
				  					echo '<tr>';
				  					echo '<td style="display:none;">'.$lh_data[$i]['type'].'</td>';
				  					echo '<td style="display:none;">'.$lh_data[$i]['id'].'</td>';
				  					echo '<td style="display:none;">'.$lh_data[$i]['trunk_order'].'</td>';
				  					echo '<td>'.$lh_type_labels[$lh_data[$i]['type']].'</td>';
				  					echo '<td>'.$lh_data[$i]['description'].'</td>';
				  					echo '<td><a class="timeout" href="#" id="timeout" data-type="select" data-value="'.$lh_data[$i]['timeout'].'" data-url="" data-pk="" data-title="Select Timeout"></a></td>';
				  					echo '<td align="right"><button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</button></td>';
				  					echo '</tr>';
				  				}
					  		?>
					  		</tbody>
					  	</table>

					  </div>
					</div>

				</div>


			</div>









		</div>

		<!-- store some temp data here for use in the reset trunks option //-->
		<table id="external-number-temp-tbody-trunks-list" style="display:none;" class="table table-striped">
	  		<thead>
	  			<tr>
	  				<td style="display:none;">id</td>
	  				<td colspan="2">Set outgoing trunk priority for this number</td>
	  			</tr>
	  		</thead>
	  		<tbody>
  				<?php
					$j = count($trunks);
					for($i=0;$i<$j;$i++) { 
						echo '<tr>';
						echo '<td style="display:none;">'.$trunks[$i]['id'].'</td>';
						echo '<td>'.$trunks[$i]['name'].'</td>';
						echo '<td align="right"><button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>';
						echo '</tr>';
					}
  				?>
  			</tbody>
		</table>						  


	</body>
</html>