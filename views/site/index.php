<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;

$this->title = 'POINT OF SALE';
$this->registerJs(<<<JS
	$('#filebarang-nama_barang').focus();

	$(document).on("click", "#process-transaction", function () {
		var url = $(this).attr('url');
		var kodebarang = $('#field-kode-barang').val();
		var qty = $('#qty-barang').val();

		$.ajax({
			type: 'get',
			url: url,
			dataType: 'json',
			data: {'kodebarang':kodebarang, 'qty':qty},
			success: function(v){
				$('#data-transaksi table tbody tr.table-title').after(v.data);
			}
		});
    });
JS
);
?>


	<div class="row">

		<div class="col-md-8">
			<div class="box box-danger">
				
				<div class="box-body">
					<div class="row">
		                <div class="col-xs-7">
		                	<div class="input-group">
		                		<span class="input-group-addon"><i class="fa fa-search"></i></span>

									<?php 
									echo Html::hiddenInput('kode_barang', '', ['id'=>'field-kode-barang']);

									echo AutoComplete::widget([
									    'model' => $model,
									    'attribute' => 'nama_barang',
									    'options' => ['class' => 'form-control input-sm'],
									    'clientOptions' => [
									        'source' => $data,
									        'minLength'=>'2', 
											'autoFill'=>true,
											'select' => new JsExpression("function( event, ui ) {
										        $('#field-kode-barang').val(ui.item.id);
										     }")
									    ],
									]); ?>
		                	</div>
		                 	
		                </div>
		                <div class="col-xs-2">
		                	<input type="text" class="form-control input-sm" placeholder="QTY" id="qty-barang">
		                </div>
		                <div class="col-xs-2">
		                	<button url="<?php echo Url::to(['site/prosestransaksi']);?>" id="process-transaction" type="button" class="btn btn-block btn-primary btn-sm">ADD</button>
		                </div>	
						
						
					</div>
					
				</div>
			</div>
		</div>
		
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="box box-danger">
				
				<div class="box-body" id="data-transaksi">
					<table class="table">
						<tbody>
							<tr class="table-title">
								<th>No</th>
								<th>Nama Barang</th>
								<th>Harga</th>
								<th>Qty</th>
								<th>Subtotal</th>
								<th></th>
							</tr>
							
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6">
									<div class="col-xs-4">
										<button type="button" class="btn btn-block btn-danger btn-sm">BATALKAN TRANSAKSI</button>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>	
		<div class="col-md-4">
			<div class="box box-danger">
				<div class="box-body">
					<ul class="todo-list ui-sortable">
					    <li><span class="text">SUBTOTAL</span><span class="pull-right"><strong>Rp. 250.000,00</strong></span></li>
					    <li><span class="text">DISKON</span><span class="pull-right"><strong>Rp. 0,00</strong></span></li>
					    <li><span class="text">TOTAL</span><span class="pull-right"><strong>Rp. 250.000,00</strong></span></li>
					    <li><span class="text">BAYAR</span><span class="pull-right"><input type="text" class="form-control input-sm" size="8"></span></li>
					    <li><span class="text">KEMBALI</span><span class="pull-right"><strong>Rp. 250.000,00</strong></span></li>
					    <li><button type="button" class="btn btn-block btn-success">PROSES</button></li>
					</ul>	
				    
				</div>
			</div>
		</div>		
	</div>



