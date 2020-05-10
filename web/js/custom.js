function show_modal(header,msg){
	$('#modal').modal('show').find('#header-info').html(header);
	$('#modal').modal('show').find('#modalContent').html(msg);
}

//remove data chart
function removeData(chart) {
    chart.data.labels.pop();
    chart.data.datasets.forEach((dataset) => {
        dataset.data.pop();
    });
    chart.update();
}

//add data chart
function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data);
    });
    chart.update();
}