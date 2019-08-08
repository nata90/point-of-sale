<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'POINT OF SALE';
$this->registerJs(<<<JS
	$('#filebarang-nama_barang').focus();

    $('#process-transaction').on('click', function() { 

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
									echo AutoComplete::widget([
									    'model' => $model,
									    'attribute' => 'nama_barang',
									    'options' => ['class' => 'form-control input-sm'],
									    'clientOptions' => [
									        'source' => $data,
									        'minLength'=>'2', 
											'autoFill'=>true,
											/*'select' => new JsExpression("function( event, ui ) {
										        $('#memberssearch-family_name_id').val(ui.item.id);//#memberssearch-family_name_id is the id of hiddenInput.
										     }")*/
									    ],
									]); ?>
		                	</div>
		                 	
		                </div>
		                <div class="col-xs-2">
		                	<input type="text" class="form-control input-sm" placeholder="QTY">
		                </div>
		                <div class="col-xs-2">
		                	<button id="process-transaction" type="button" class="btn btn-block btn-primary btn-sm">ADD</button>
		                </div>	
						
						
					</div>
					
				</div>
			</div>
		</div>
		
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="box box-danger">
				
				<div class="box-body">
					<table class="table">
						<tbody>
							<tr>
								<th>No</th>
								<th>Nama Barang</th>
								<th>Harga</th>
								<th>Qty</th>
								<th>Subtotal</th>
								<th></th>
							</tr>
							<tr>
								<td>1.</td>
								<td>PARACETAMOL 500 MG</td>
								<td>Rp. 10.000,00</td>
								<td>5</td>
								<td>Rp. 50.000,00</td>
								<td style="text-align:center;">
						            <a href="#" title="Delete"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
							<tr>
								<td>2.</td>
								<td>AMBROXOL 500 MG</td>
								<td>Rp. 25.000,00</td>
								<td>5</td>
								<td>Rp. 125.000,00</td>
								<td style="text-align:center;">
						            <a href="#" title="Delete"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
							<tr>
								<td>3.</td>
								<td>FLUCADEC</td>
								<td>Rp. 15.000,00</td>
								<td>5</td>
								<td>Rp. 75.000,00</td>
								<td style="text-align:center;">
						            <a href="#" title="Delete"><i class="fa fa-trash"></i></a>
								</td>
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



