
<p><strong>LAPORAN PENJUALAN PERIODE TANGGAL <?php echo date('d-m-Y', strtotime($searchModel->start_date))?> S/D <?php echo date('d-m-Y', strtotime($searchModel->end_date))?></strong></p>
<?php
	use app\components\Utility;
	if($model != null){ ?>
		<table border="1" style="font-size:12px;border-collapse:collapse;border: 1px solid black;">
			<tr>
				<td width="30" align="center">NO</td>
				<td width="100" align="center">NO TRANSAKSI</td>
				<td width="100" align="center">KODE BARANG</td>
				<td width="120" align="center">TANGGAL TRANSAKSI</td>
				<td width="200" align="center">NAMA BARANG</td>
				<td width="100" align="center">HARGA SATUAN</td>
				<td width="40" align="center">QTY</td>
				<td width="100" align="center">TOTAL</td>
			</tr>
<?php	
		$no = 1;
		$all_total = 0;
		foreach($model as $val){ ?>
			<tr>
				<td><?php echo $no;?></td>
				<td><?php echo $val->no_transaksi;?></td>
				<td><?php echo $val->kd_barang;?></td>
				<td><?php echo date('d-m-Y', strtotime($val->header->tgl_bayar));?></td>
				<td><?php echo $val->barang->nama_barang;?></td>
				<td align="right"><?php echo Utility::rupiah($val->harga_satuan)?></td>
				<td align="center"><?php echo $val->qty;?></td>
				<td align="right"><?php echo Utility::rupiah($val->harga_satuan * $val->qty)?></td>
			</tr>
<?php	$no++;
		$all_total = $all_total + ($val->harga_satuan * $val->qty);
		} 
		$distinct_discounts = [];
		foreach($model as $val){
			if(!isset($distinct_discounts[$val->no_transaksi])){
				$distinct_discounts[$val->no_transaksi] = (!empty($val->header->diskon)) ? (int)$val->header->diskon : 0;
			}
		}
		$total_diskon = array_sum($distinct_discounts);
		?>
		<?php if ($total_diskon > 0): ?>
			<tr>
				<td colspan="6" align="right"><b>SUBTOTAL</b></td>
				<td colspan="2" align="right"><strong><?php echo Utility::rupiah($all_total)?></strong></td>
			</tr>
			<tr>
				<td colspan="6" align="right" style="color:red;"><b>TOTAL DISKON</b></td>
				<td colspan="2" align="right" style="color:red;"><strong>- <?php echo Utility::rupiah($total_diskon)?></strong></td>
			</tr>
			<tr>
				<td colspan="6" align="right"><b>TOTAL PENJUALAN BERSIH</b></td>
				<td colspan="2" align="right"><strong><?php echo Utility::rupiah($all_total - $total_diskon)?></strong></td>
			</tr>
		<?php else: ?>
			<tr>
				<td colspan="6" align="right"><b>TOTAL</b></td>
				<td colspan="2" align="right"><strong><?php echo Utility::rupiah($all_total)?></strong></td>
			</tr>
		<?php endif; ?>
		</table>
<?php }
?>