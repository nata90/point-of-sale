<?php 
	use app\components\Utility;
	use yii\helpers\Url;
?>
<div class="box box-danger box-solid">
	<div class="box-header with-border">
		<h3 class="box-title"></h3>
	</div>
	<div class="box-body no-padding">
		<table class="table table-striped table-bordered">
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
			</tfoot>
		</table>
	</div>
</div>
