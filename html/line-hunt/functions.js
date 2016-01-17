/* included js functions */

function init_edit_table(){
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
}

function init_external_number_panel(){
    $( "#external-number-trunks > tbody" ).sortable({ 
		cursor: "crosshair",
		update: function( event, ui ){ $('#external-number-trunks > tbody tr').eq(ui.item.index()).effect("highlight", {color: "Moccasin"}, 1000); } 
    });
    $( "#external-number-trunks > tbody" ).disableSelection();

	$('#external-number-trunks > tbody  > tr > td').each(function(){
        $(this).css('width', $(this).width() +'px');
    });

	$('#external-number-trunks > thead  > tr > td').each(function(){
        $(this).css('width', $(this).width() +'px');
    });

	$("#external-number-trunks > tbody").on('click', '.btn-danger', function () {
		var tr = $(this).parents("tr");
		tr.effect("highlight", {color: "pink"}, 300);
		// fadeout is async so we need to wrap anything required to run after it in a function
		tr.fadeOut('fast', function(){ 
			tr.remove(); 
			if($("#external-number-trunks > tbody tr").length == 0){
				external_use_any_trunk();
			}
		});
	});
}

function add_item(type, id, desc){
	var type_label = "";
	var trunk_order = "";
	if(type == "external"){
		type_label = "External Number";
		id = $("#lh-add-external").val();

		var tblArray = jq_table_to_array("external-number-trunks");
		for (var i = 0; i < tblArray.length; i++) {
			if(i != 0){
				if(i != 1) desc += ", ";
				desc += tblArray[i][1];
				if(i != 1) trunk_order += "|";
				trunk_order += tblArray[i][0];
			}
		};
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
	html += '<td style="display:none;">'+trunk_order+'</td>';
	html += '<td>'+type_label+'</td>';
	html += '<td>'+id+' '+desc+'</td>';
	html += '<td><a class="timeout" href="#" id="timeout" data-type="select" data-url="" data-pk="" data-title="Select Timeout"></a></td>';
	html += '<td align="right"><button class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</button></td>';
	html += '</tr>';

	$('#linehuntEdit > tbody').append(html);

	// this is the new tr object
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

function external_use_any_trunk(){
	$("#external-number-trunks > tbody" ).html('<tr><td style="display:none;">any</td><td>Any</td><td>&nbsp;</td></tr>');
	$('#external-number-trunks > tbody  > tr').each(function(){
        $(this).effect("highlight", {}, 1000);
    });
}

function external_reset_changes(){
	var tbody = $('#external-number-temp-tbody-trunks-list > tbody').html();
	$( "#external-number-trunks > tbody" ).html(tbody);
	$('#external-number-trunks > tbody  > tr').each(function(){
        $(this).effect("highlight", {}, 1000);
    });
	$('#external-number-trunks > tbody  > tr > td').each(function(){
        $(this).css('width', $(this).width() +'px');
    });
}

