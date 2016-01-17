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
//$Common->ecco($lh);

$exts = $Ext->list_extensions();
//$Common->ecco($exts);

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/meta.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/title.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/css.php");?>
		<?php include($_SERVER["DOCUMENT_ROOT"]."/includes/js.php");?>
		<script type="text/javascript" src="/js/common.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {

			    $( "#linehuntEdit > tbody" ).sortable({ 
		    		cursor: "crosshair",
		    		update: function( event, ui ){ $('#linehuntEdit > tbody tr').eq(ui.item.index()).effect("highlight", {color: "Moccasin"}, 1000); } 
			    });
			    $( "#linehuntEdit > tbody" ).disableSelection();

				$('#linehuntEdit > tbody  > tr > td').each(function(){
			        $(this).css('width', $(this).width() +'px');
			    });

				$("#linehuntEdit").on('click', '.btn-danger', function () {
				   var tr = $(this).closest('tr');
				   tr.effect("highlight", {color: "pink"}, 300);
				   tr.fadeOut('fast', function(){ tr.closest('tr').remove(); });
				});


				$(function(){
				    $(".timeout").editable({
				        source: [
				              {value: "5", text: "5 Seconds"},
				              {value: "10", text: "10 Seconds"},
				              {value: "15", text: "15 Seconds"},
				              {value: "20", text: "20 Seconds"},
				              {value: "30", text: "30 Seconds"},
				              {value: "45", text: "45 Seconds"},
				              {value: "60", text: "60 Seconds"},
				              {value: "90", text: "90 Seconds"},
				              {value: "120", text: "120 Seconds"},
				              {value: "180", text: "180 Seconds"},
				           ]
				    });
				});




			});

			function add_item(type, id, desc){
				var type_label = "";
				if(type == "external"){
					type_label = "External Number";
					id = $("#lh-add-external").val();
				}
				if(type == "extension"){
					type_label = "Voip Extension"
				}

				if(id=="") return false;
				if(desc != ""){
					desc = "("+desc+")";
				}
				
				var html = '';
				html += '<tr>';
				html += '<td style="display:none;">'+type+'</td>';
				html += '<td style="display:none;">'+id+'</td>';
				html += '<td>'+type_label+'</td>';
				html += '<td>'+id+' '+desc+'</td>';
				html += '<td><a class="timeout" href="#" id="timeout" data-type="select" data-url="" data-pk="" data-title="Select Timeout"></a></td>';
				html += '<td align="right"><button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</button></td>';
				html += '</tr>';

				$('#linehuntEdit > tbody').append(html);

				// this is the new tr object
				//var new_tr = $('#linehuntEdit tr').eq($('#linehuntEdit > tbody tr:last').index()+1);
				var new_tr = $("#linehuntEdit tr").eq( -1 );

				// for effect .. hide, fade in and highlight the new row
				new_tr.hide().fadeIn("fast").effect("highlight", { }, 1000);

				// set fixed width on the td elements in the new row
				new_tr.find('td').each (function() {
					$(this).css('width', $(this).width() +'px');
				});

				new_tr.find('a').each (function() {
				    $(".timeout").editable({
				        value: '30',  
				        source: [
				              {value: "5", text: "5 Seconds"},
				              {value: "10", text: "10 Seconds"},
				              {value: "15", text: "15 Seconds"},
				              {value: "20", text: "20 Seconds"},
				              {value: "30", text: "30 Seconds"},
				              {value: "45", text: "45 Seconds"},
				              {value: "60", text: "60 Seconds"},
				              {value: "90", text: "90 Seconds"},
				              {value: "120", text: "120 Seconds"},
				              {value: "180", text: "180 Seconds"},
				           ]
				    });
				});
			}
		
			function save_changes(id){

				$("#btn-save-changes").attr("disabled",true);
				$("#btn-save-changes").html("<span class=\"glyphicon glyphicon-refresh glyphicon-spin\"></span> Please wait ..");

				var tblArray = jq_table_to_array("linehuntEdit");
				//alert(myTableArray);

				$.post("/line-hunt/update.php", { id: id, tdata: JSON.stringify(tblArray) },
					function(data){
			//			alert(data);
						var results = new Array();
						try{ results = JSON.parse(data); }catch(ex){ results['status'] = ex; }
						// do stuff here
						if(results['status'] == "OK"){
							
							$("#btn-save-changes").toggleClass("btn-info btn-success");
							$("#btn-save-changes").html("Saved Sucessfully");

							setTimeout(function(){ $("#btn-save-changes").toggleClass("btn-info btn-success"); $("#btn-save-changes").html("Save Changes"); }, 1000);
						}else{
							alert(data);
						}
						$("#btn-save-changes").attr("disabled",false);
				});
			}

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
				
				<div class="col-lg-3">
					
					

						<div class="panel panel-success">
						  <div class="panel-heading">
						    <h3 class="panel-title"><a href="/line-hunt/"> <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span> Back</a></h3>
						  </div>
						</div>

						<div class="panel panel-default">
						  <div class="panel-heading">
						    <h3 class="panel-title">Extensions</h3>
						  </div>
						  <div class="panel-body">
					  	<table class="table table-striped">
					  		<tbody id="exttest">
					  			<?php for($i=0;$i<count($exts);$i++) { ?>
						  			<tr id="add-extensions-<?php echo $exts[$i]['name'];?>" onclick="add_item('extension','<?php echo $exts[$i]['name'];?>','<?php echo $exts[$i]['label'];?>');">
						  				<td><?php echo $exts[$i]['name'];?></td>
						  				<td><?php echo $exts[$i]['label'];?></td>
						  				<td align="right"><a href="javascript:void(0);"> <span class="glyphicon glyphicon-export" aria-hidden="true"></span> </a></td>
						  			</tr>
						  		<?php } ?>
					  		</tbody>
					  	</table>
						  </div>
						</div>

						<div class="panel panel-default">
						  <div class="panel-heading">
						    <h3 class="panel-title">External Numbers</h3>
						  </div>
						  <div class="panel-body">
							<table class="table table-striped">
						  		<tbody id="exttest">
					  				<tr>
						  				<td width="50%"><input id="lh-add-external" type="number"></td>
						  				<td align="right" onclick="add_item('external','','')"><a href="javascript:void(0);"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> </a></td>
						  			</tr>
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
				<div class="col-lg-7">


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
				<div class="col-lg-2">

					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Trunk Priority</h3>
						</div>
						<div class="panel-body">
							Panel content
						</div>
					</div>

				</div>

			</div>









		</div>


	</body>
</html>