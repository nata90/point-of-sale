<?php

/* @var $this yii\web\View */

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
								<input type="text" class="form-control" name="" placeholder="Nama Barang">
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
							</tr>
							<tr>
								<td>1.</td>
								<td>PARACETAMOL 500 MG</td>
								<td>Rp. 10.000,00</td>
								<td>5</td>
								<td></td>
								<td>Rp. 50.000,00</td>
							</tr>
							<tr>
								<td>2.</td>
								<td>AMBROXOL 500 MG</td>
								<td>Rp. 25.000,00</td>
								<td>5</td>
								<td></td>
								<td>Rp. 125.000,00</td>
							</tr>
							<tr>
								<td>3.</td>
								<td>FLUCADEC</td>
								<td>Rp. 15.000,00</td>
								<td>5</td>
								<td></td>
								<td>Rp. 75.000,00</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
		<div class="col-md-4">
			<div class="box box-danger">
				<div class="box-body">
				    <ul class="nav nav-stacked">
				        <li><a href="#">SUBTOTAL <span class="pull-right">Rp. 250.000,00</span></a></li>
				        <li><a href="#">DISKON <span class="pull-right">Rp. 0,00</span></a></li>
				     	<li><a href="#">TOTAL <span class="pull-right">Rp. 250.000,00</span></a></li>
				     	<li>
				     		<a href="#">BAYAR <span class="pull-right">
				     		<input type="text" class="form-control input-sm" size="8" ></span>
				     		</a></li>
				     	<li><a href="#">KEMBALIAN <span class="pull-right">Rp. 250.000,00</span></a></li>
				     	<li><button type="button" class="btn btn-block btn-success">PROSES</button></li>
				    </ul>
				</div>
			</div>
		</div>		
	</div>



