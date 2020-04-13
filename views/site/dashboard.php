<?php
use yii\helpers\Url;
$this->registerJs('var url = "' . Url::to(['/site/grafikpenjualan']) . '";');
$this->registerJs('var daysago = "' . $days_ago . '";');
$this->registerJs('var daysnow = "' . $days_now . '";');
$this->registerJsFile(Yii::$app->request->BaseUrl . '/js/numeral.min.js');
$this->registerJs(<<<JS
	$( document ).ready(function() {
		$.ajax({
			type: 'get',
			url: url,
			dataType: 'json',
			success: function(v){
				$(function () {
					var ctx = document.getElementById('barChart').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'bar',
					    data: {
					        labels: v.label,
					        datasets: [{
					            label: 'GRAFIK DATA PENJUALAN (Rupiah)',
					            data: v.data,
					            backgroundColor: [
					                'rgba(255, 99, 132, 0.2)',
					                'rgba(54, 162, 235, 0.2)',
					                'rgba(255, 206, 86, 0.2)',
					                'rgba(75, 192, 192, 0.2)',
					                'rgba(153, 102, 255, 0.2)',
					                'rgba(255, 159, 64, 0.2)',
					                'rgba(72, 176, 69, 0.2)',
					                'rgba(176, 69, 137, 0.2)',
					                'rgba(64, 132, 191, 0.2)',
					                'rgba(169, 129, 213, 0.2)',
					                'rgba(129, 213, 132, 0.2)'
					            ],
					            borderColor: [
					                'rgba(255, 99, 132, 1)',
					                'rgba(54, 162, 235, 1)',
					                'rgba(255, 206, 86, 1)',
					                'rgba(75, 192, 192, 1)',
					                'rgba(153, 102, 255, 1)',
					                'rgba(255, 159, 64, 1)',
					                'rgba(72, 176, 69, 1)',
					                'rgba(176, 69, 137, 1)',
					                'rgba(64, 132, 191, 1)',
					                'rgba(169, 129, 213, 1)',
					                'rgba(129, 213, 132, 1)'
					            ],
					            borderWidth: 1
					        }]
					    },
					    options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero: true,
					                    callback: function (value) {
				                            return numeral(value).format('0,0')
				                        }
					                }
					            }]
					        },
					        tooltips: {
					            callbacks: {
					                label: function(tooltipItem, data) {
					                     return numeral(tooltipItem.yLabel).format('0,0');
					                }
					            }
					        }
					    }
					});
				})
			}
		});
	});

	//Date range picker
    $('#reservation').daterangepicker({
    	"startDate": daysago,
    	"endDate": daysnow
    })

    $(document).on("click", "#search-grafik", function () {
    	var daterange = $('#reservation').val();

    });
JS
);
?>

	<div class="row">	
			
		 <!-- BAR CHART -->
	    <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                
              </div>
            	<div class="box-body">
	            		<div class="form-group col-xs-4">
			              	
			              	<div class="input-group">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="reservation" >

				            </div>

			             </div>
			             <div class="col-xs-1">
			             	 <button class="btn btn-block btn-success btn-sm" id="search-grafik">Cari</button>
			             </div>
            		<div class="chart">
		                <canvas id="barChart" style="height:230px"></canvas>
		            </div>
                </div>
            </div>
          </div>
	    </div>
	    <?php /*<div class="col-md-4">
	    	<div class="box box-danger">
	    		<div class="box-header with-border">
		    		<h3 class="box-title">10 TERLARIS BULAN INI</h3>
		    	</div>
	    		<div class="box-body">
	    			<ul class="todo-list ui-sortable">
	    				<?php if($popular != null){ 
	    						foreach($popular as $row){
	    				?>
	    			    			<li><span class="handle ui-sortable-handle"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span><span class="text"><?php echo $row['nama_barang'];?></span><span class="label label-success"><?php echo $row['total'];?> ITEM</span></li>
	    			    <?php }
	    				} ?>
	    			</ul>
	    		</div>
	    	</div>
	    </div>*/ ?>
	    
	</div>



