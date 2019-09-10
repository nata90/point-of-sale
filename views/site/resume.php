<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\components\Utility;


$this->title = 'RESUME TRANSAKSI';
$this->registerJs(<<<JS
	function newTransaction() {
	  var url = $(this).attr('url');
	  
	  location.replace(url);
	}
JS
);

?>

	<div class="row">
		<div class="col-md-12">
			<div class="box box-danger box-solid">
				<div class="box-header with-border">
					RESUME TRANSAKSI
				</div>
				<div class="box-body" id="data-transaksi">
					<table class="table">
						<tbody>
							<tr class="table-title">
								<th>No</th>
								<th>Kode Barang</th>
								<th>Nama Barang</th>
								<th>Harga</th>
								<th>Qty</th>
								<th>Subtotal</th>								
							</tr>
							<?php 
							if($model != null){ 
								$no = 1;
								foreach($model->details as $val){
							?>
									<tr>
										<td><?php echo $no;?>.</td>
					                    <td><?php echo $val->kd_barang;?></td>
					                    <td><?php echo $val->barang->nama_barang;?></td>
					                    <td><?php echo Utility::rupiah($val->harga_satuan);?></td>
					                    <td><?php echo $val->qty;?></td>
					                    <td><?php echo Utility::rupiah($val->total_harga);?></td>			                    
									</tr>
							<?php 
									$no++;
								}
							}
							?>
							
							
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td></td>
			                    <td></td>
			                    <td></td>
			                    <td><strong>TOTAL</strong></td>
			                    <td><strong><?php echo Utility::rupiah($model->total);?></strong></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
			                    <td></td>
			                    <td></td>
			                    <td><strong>TOTAL BAYAR</strong></td>
			                    <td><strong><?php echo Utility::rupiah($model->jumlah_bayar);?></strong></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
			                    <td></td>
			                    <td></td>
			                    <td><strong>KEMBALI</strong></td>
			                    <td><strong><?php echo Utility::rupiah(($model->jumlah_bayar-$model->total));?></strong></td>
							</tr>
							<tr>
								<td></td>
			                    <td></td>
			                    <td></td>
			                    <td></td>
			                    <td><button type="button" class="btn btn-block btn-success" onclick="newTransaction()" url="<?php echo Url::to(['site/index']);?>">BARU</button></td>
			                    <td><button type="button" class="btn btn-block btn-danger">BATAL</button></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>	
				
	</div>



