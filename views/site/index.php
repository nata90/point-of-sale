<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'POINT OF SALE';

?>


	<div class="row">

		<div class="col-md-8">
			<div class="box box-danger">
				
				<div class="box-body">
					<div class="form-group">
						<div class="col-md-8">
							<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-search"></i></span>
									<?php 
									echo AutoComplete::widget([
									    'model' => $model,
									    'attribute' => 'nama_barang',
									    'options' => ['class' => 'form-control'],
									    'clientOptions' => [
									        'source' => $data,
									        'minLength'=>'2', 
											'autoFill'=>true,
											/*'select' => new JsExpression("function( event, ui ) {
										        $('#memberssearch-family_name_id').val(ui.item.id);//#memberssearch-family_name_id is the id of hiddenInput.
										     }")*/
									    ],
									]);
									 ?>

							</div>
						</div>			
						<div class="col-md-4">
							<div class="input-group col-xs-3">
								<button type="button" class="btn btn-block btn-danger pull-right">BATALKAN</button>
							</div>
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
								<th>Diskon</th>
								<th>Subtotal</th>
								<th></th>
							</tr>
							<tr>
								<td>1.</td>
								<td>PARACETAMOL 500 MG</td>
								<td>Rp. 10.000,00</td>
								<td>5</td>
								<td></td>
								<td>Rp. 50.000,00</td>
								<td><button type="button" class="btn btn-block btn-danger btn-sm">HAPUS</button></td>
							</tr>
							<tr>
								<td>2.</td>
								<td>AMBROXOL 500 MG</td>
								<td>Rp. 25.000,00</td>
								<td>5</td>
								<td></td>
								<td>Rp. 125.000,00</td>
								<td><button type="button" class="btn btn-block btn-danger btn-sm">HAPUS</button></td>
							</tr>
							<tr>
								<td>3.</td>
								<td>FLUCADEC</td>
								<td>Rp. 15.000,00</td>
								<td>5</td>
								<td></td>
								<td>Rp. 75.000,00</td>
								<td><button type="button" class="btn btn-block btn-danger btn-sm">HAPUS</button></td>
							</tr>
						</tbody>
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
				    <?php /*<ul class="nav nav-stacked">
				        <li><a href="#">SUBTOTAL <span class="pull-right">Rp. 250.000,00</span></a></li>
				        <li><a href="#">DISKON <span class="pull-right">Rp. 0,00</span></a></li>
				     	<li><a href="#">TOTAL <span class="pull-right">Rp. 250.000,00</span></a></li>
				     	<li>
				     		<a href="#">BAYAR <span class="pull-right">
				     		<input type="text" class="form-control input-sm" size="8" style=""></span>
				     		</a>
				     	</li>
				     	<li><a href="#">KEMBALI <span class="pull-right">Rp. 0,00</span></a></li>

				     	<li><button type="button" class="btn btn-block btn-success">PROSES</button></li>
				    </ul>*/ ?>
				</div>
			</div>
		</div>		
	</div>



