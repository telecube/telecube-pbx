/* included js functions */

function list_types(){
	return [
              {value: "", text: 'None'},
              {value: "extension", text: 'Extension'},
              {value: "linehunt", text: 'Line Hunt Group', disabled: true},
              {value: "queue", text: 'Ring Group (Queue)', disabled: true},
              {value: "ivr", text: 'Auto Attendant (IVR)', disabled: true},
              {value: "timebased", text: 'Time Based Rules', disabled: true},
           ];
}

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
			}else{
				alert(data);
			}

			setTimeout(function(){ trunk_register_status(); }, 1000);
	});
}

function run(){
	trunk_register_status();
}

