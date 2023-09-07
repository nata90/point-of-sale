<p><strong>LAPORAN PENJUALAN PERIODE TANGGAL <?php echo date('d-m-Y', strtotime($searchModel->start_date))?> S/D <?php echo date('d-m-Y', strtotime($searchModel->end_date))?></strong></p>
<?php
	use app\components\Utility;
	if($model != null){ ?>
		<table border="1" style="font-size:12px;border-collapse:collapse;border: 1px solid black;">
			<tr>
				<td width="30" align="center">NO</td>
				<td width="100" align="center">No Transaksi</td>
				<td width="100" align="center">Kode Barang</td>
				<td width="120" align="center">Tanggal Transaksi</td>
				<td width="200" align="center">Nama Barang</td>
				<td width="100" align="center">Harga Satuan</td>
				<td width="40" align="center">QTY</td>
				<td width="100" align="center">Total</td>
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
		} ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td align="right"><strong><?php echo Utility::rupiah($all_total)?></strong></td>
			</tr>
		</table>
<?php }
?>