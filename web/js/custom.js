function show_modal(header,msg){
	$('#modal').modal('show').find('#header-info').html(header);
	$('#modal').modal('show').find('#modalContent').html(msg);
}