<?php

$this->registerJs(<<<JS
	$(function () {
		var ctx = document.getElementById('barChart').getContext('2d');
		var myChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
		        datasets: [{
		            label: '# of Votes',
		            data: [12, 19, 3, 5, 2, 3],
		            backgroundColor: [
		                'rgba(255, 99, 132, 0.2)',
		                'rgba(54, 162, 235, 0.2)',
		                'rgba(255, 206, 86, 0.2)',
		                'rgba(75, 192, 192, 0.2)',
		                'rgba(153, 102, 255, 0.2)',
		                'rgba(255, 159, 64, 0.2)'
		            ],
		            borderColor: [
		                'rgba(255, 99, 132, 1)',
		                'rgba(54, 162, 235, 1)',
		                'rgba(255, 206, 86, 1)',
		                'rgba(75, 192, 192, 1)',
		                'rgba(153, 102, 255, 1)',
		                'rgba(255, 159, 64, 1)'
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero: true
		                }
		            }]
		        }
		    }
		});
	})
JS
);
?>
	<div class="row">
		 <!-- BAR CHART -->
	    <div class="col-md-8">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Penjualan</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div>
          </div>
	    </div>
	    <div class="col-md-4">
	    	<div class="box box-danger">
	    		<div class="box-header with-border">
		    		<h3 class="box-title">10 TERLARIS BULAN INI</h3>
		    	</div>
	    		<div class="box-body">
	    			<ul class="todo-list ui-sortable">
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">TV</span><span class="label label-success">20 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">KULKAS 2 PINTU</span><span class="label label-warning">20 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">KOMPUTER</span><span class="label label-danger">15 ITEM</span></li>
	    			   <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">SEPEDA MOTOR</span><span class="label label-primary">5 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">SEPEDA MOTOR</span><span class="label label-primary">5 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">TV</span><span class="label label-success">20 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">KULKAS 2 PINTU</span><span class="label label-warning">20 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">KOMPUTER</span><span class="label label-danger">15 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">TV</span><span class="label label-success">20 ITEM</span></li>
	    			    <li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text">KULKAS 2 PINTU</span><span class="label label-warning">20 ITEM</span></li>
	    			</ul>
	    		</div>
	    	</div>
	    </div>
	    
	</div>



