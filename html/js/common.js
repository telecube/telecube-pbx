/* common js functions */

function jq_table_to_array(name){
	var myTableArray = [];
	$("table#"+name+" tr").each(function() {
	    var arrayOfThisRow = [];
	    var tableData = $(this).find('td');
	    if (tableData.length > 0) {
	        tableData.each(function() { arrayOfThisRow.push($(this).text()); });
	        myTableArray.push(arrayOfThisRow);
	    }
	});
	return myTableArray;
}







