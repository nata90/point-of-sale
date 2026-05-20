<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;



$this->title = 'PENJUALAN - POS';
$this->registerJs('var ip_addr = "' . $setting->ip_address . '";');
$this->registerJs('var url_get_nama = "' .Url::to(['site/getnamabarang']). '";');
$this->registerJs('var url_proses_transaksi = "' .Url::to(['site/prosestransaksi']). '";');
$this->registerJs('var url_create_item = "' .Url::to(['filebarang/createnewbarang']). '";');
$this->registerJs('var url_generate_code = "' .Url::to(['filebarang/getkodebarang']). '";');
$this->registerJs('var url_button_bayar = "' .Url::to(['transaksi/hitungpembayaran']). '";');
$this->registerJs(<<<JS
	$('#field-kode-barang').focus();

	$(document).on("click", "#process-transaction", function () {
		var url = $(this).attr('url');
		var kodebarang = $('#field-kode-barang').val();
		var namabarang = $('#filebarang-nama_barang').val();
		var qty = $('#qty-barang').val();
		/*var idstok = $('#field-id-stokbarang').val();*/

		if(kodebarang == ''){
			Swal.fire({
				title: "Kode Barang Tidak Boleh Kosong!",
				icon: "error"
			});
		}else if(namabarang == ''){
			Swal.fire({
				title: "Nama Barang Tidak Boleh Kosong!",
				icon: "error"
			});
		}else if(qty == ''){
			Swal.fire({
				title: "Jumlah Tidak Boleh Kosong!",
				icon: "error"
			});
		}else{
			$.ajax({
				type: 'get',
				url: url,
				dataType: 'json',
				data: {
					'kodebarang':kodebarang, 
					'qty':qty, 
					'namabarang':namabarang
					/*'idstok':idstok*/
				},
				success: function(v){
					if(v.datafound == 0){

						Swal.fire({
							title: v.ms,
							icon: "error"
						});
					}else{
						$('#data-transaksi').html(v.data);
						$('#filebarang-nama_barang').val('');
						$('#field-kode-barang').val('');
						$('#qty-barang').val('');
						$('#filebarang-nama_barang').focus();
						$('#subtotal').html(v.subtotal);
						$('#total').html(v.total);
						$('#field-total-tagihan').val(v.hidtotal);
						$('#jumlah-bayar').val('');
						$('#cashback').html('<b>Rp.0,00</b>');
						$('#field-total-bayar').val('');
						$('#field-total-cashback').val('');
						$('#field-kode-barang').focus();
					}
					
				}
			});
		}
		
    });

    $(document).on("click", ".delete-item", function () {
    	rel = $(this).attr('rel');
    	url = $(this).attr('url');

    	$.ajax({
			type: 'get',
			url: url,
			dataType: 'json',
			data: {'rel':rel},
			success: function(v){
				$('#data-transaksi').html(v.data);
				$('#subtotal').html(v.subtotal);
				$('#total').html(v.total);
				$('#diskon').html(v.diskon);
				$('#field-total-tagihan').val(v.hidtotal);

				$('#jumlah-bayar').val('');
				$('#cashback').html('<b>Rp.0,00</b>');
				$('#field-total-bayar').val('');
				$('#field-total-cashback').val('');
			}
		});
    });
    
    var rupiah = document.getElementById('jumlah-bayar');
	rupiah.addEventListener('keyup', function(e){
		rupiah.value = formatRupiah(this.value, 'Rp. ');
		
	});
    
	$(document).on("keyup", "#jumlah-bayar", function () {
    	var bayar = $(this).val();
    	var remrp = bayar.replace("Rp. ","");
    	var remdot = remrp.split(".").join("");

		var total = $('#field-total-tagihan').val();
		var cashback = parseInt(remdot) - parseInt(total);
		var nilai = formatRupiah(cashback.toString(), 'Rp. ');

		$('#field-total-bayar').val(remdot);
		$('#field-total-cashback').val(cashback);

		if(cashback < 0){
			$('#cashback').html("<b>Rp. 0,00</b>");
		}else{
			$('#cashback').html("<b>"+nilai+"</b>");
		}
		
    });

    $(document).on("click", "#proses-trans", function () {
    	var totaltagihan = $('#field-total-tagihan').val();
    	var totalbayar = $('#field-total-bayar').val();
    	var cashback = $('#field-total-cashback').val();
    	var url = $(this).attr('url');

    	if(totalbayar == 0){
			Swal.fire({
				title: "Jumlah Bayar Wajib Di Isi",
				icon: "error"
			});
		}else{
			if(cashback < 0){
				Swal.fire({
					title: "Jumlah Bayar Kurang dari Total",
					icon: "error"
				});
	    	}else{
	    		$.ajax({
					type: 'post',
					url: url,
					dataType: 'json',
					'beforeSend':function(json)
					{ 
						SimpleLoading.start('gears'); 
					},
					data: {
						'totaltagihan':totaltagihan, 
						'totalbayar':totalbayar, 
						'cashback':cashback
					},
					success: function(v){
						if(v.success == 1){
							var head = 'Penjualan : '+v.nopenjualan;
							var msg = v.items;
							location.replace(v.redirect);
						}else{
							Swal.fire({
								title: 'Gagal Simpan',
								html: v.msg,
								icon: "error"
							});
						}
						
					},
					'complete':function(json)
					{
						SimpleLoading.stop();
					},
				});
	    	}
		}
    	
    });


	$(document).on("change", "#field-kode-barang", function () {
		var kodebarang = $(this).val();
		var qty = 1;
		var ajaxTimeout = null;

		var barcode = $(this).val();

		if (ajaxTimeout) {
			clearTimeout(ajaxTimeout);
		}
		
		ajaxTimeout = setTimeout(function() {
			if(kodebarang != ''){
				$.ajax({
					type: 'get',
					url: url_get_nama,
					dataType: 'json',
					'beforeSend':function(json)
					{ 
						SimpleLoading.start('gears'); 
					},
					data: {'kode_barang':kodebarang},
					success: function(v){
						if(v.itemfound == 1){
							$.ajax({
								type: 'get',
								url: url_proses_transaksi,
								dataType: 'json',
								data: {
									'kodebarang':kodebarang, 
									'qty':1, 
									'namabarang':v.nama_barang
								},
								success: function(v){
									if(v.datafound == 0){
										Swal.fire({
											title: v.msg,
											icon: "error"
										});
									}else{
										$('#data-transaksi').html(v.data);
										$('#filebarang-nama_barang').val('');
										$('#field-kode-barang').val('');
										$('#qty-barang').val('');
										$('#filebarang-nama_barang').focus();
										$('#subtotal').html(v.subtotal);
										$('#total').html(v.total);
										$('#field-total-tagihan').val(v.hidtotal);
										$('#jumlah-bayar').val('');
										$('#cashback').html('<b>Rp.0,00</b>');
										$('#field-total-bayar').val('');
										$('#field-total-cashback').val('');
										$('#field-kode-barang').focus();
									}
									
								}
							});
						}else{
							Swal.fire({
								title: "Barang Tidak Ditemukan!",
								showDenyButton: true,
								showCancelButton: false,
								confirmButtonText: '<i class="fa fa-thumbs-up"></i> BUAT BARANG BARU ',
								denyButtonText: `TUTUP`,
								icon: "error"
							}).then((result) => {
								if (result.isConfirmed) {
									let urlcreate = url_create_item+'&kodebarang='+kodebarang;
									$('#modal').modal('show')
										.find('#modalContent')
										.load(urlcreate, function (responseTxt, statusTxt, xhr) {
											
										});
										$('#modal .modal-header #header-info').html('<h4>BUAT BARANG BARU</h4>');

										$("#modal").on('shown.bs.modal', function () {
											$("#popup-namabarang").focus();
										});
								} 
							});
							

						}
						SimpleLoading.stop();
					},
					
				});
			}
		}, 200);
		

		
	});

	$(document).on("click", ".modalBtn", function () {
		var link = $(this).attr('url');

		$('#modal').modal('show')
			.find('#modalContent')
			.load(link, function (responseTxt, statusTxt, xhr) {
				if (statusTxt == "success") {
					hideload();
				}
	
				if (statusTxt == "error") {
					hideload();
					modalErr("Error: " + xhr.status + ": " + xhr.statusText);
				}
	
			});
		if (this.hasAttribute('headername')) {
			$('#modal .modal-header #header-info').html('<h4>'+$(this).attr('headername')+'</h4>');
		} else {
			$('#modal .modal-header #header-info').html('Data');
		}

		$("#modal").on('shown.bs.modal', function () {
			$("#filebarang-harga_jual").focus();
		});

	});

	$(document).on("click", "#update-button", function () {
		let harga = $('#filebarang-harga_jual').val();
		let kodebarang = $('#filebarang-kd_barang').val();
		let url = $(this).attr('link');

		$.ajax({
			type: 'get',
			url: url,
			dataType: 'json',
			beforeSend:function(json)
			{ 
				SimpleLoading.start('gears'); 
			},
			data: {
				'harga':harga,
				'kodebarang':kodebarang
			},
			success: function(v){
				if(v.success == 1){
					$('#modal').modal('hide');
					$('#data-transaksi').html(v.data);
					$('#subtotal').html(v.subtotal);
					$('#total').html(v.total);
					$('#diskon').html(v.diskon);
					$('#field-total-tagihan').val(v.hidtotal);

					$('#jumlah-bayar').val('');
					$('#cashback').html('<b>Rp.0,00</b>');
					$('#field-total-bayar').val('');
					$('#field-total-cashback').val('');
					$('#field-kode-barang').focus();
				}else{
					Swal.fire({
						title: 'Gagal Update !',
						html: v.msg,
						icon: "error"
					});
				}
				

				SimpleLoading.stop();
			}
		});

	});

	$(document).on("click", "#create-item-button", function () {
		let kodebarang = $('#filebarang-kd_barang').val();
		let namabarang = $('#popup-namabarang').val();
		let hargajual = $('#filebarang-harga_jual').val();
		let url = $(this).attr('link');

		$.ajax({
			type: 'post',
			url: url,
			dataType: 'json',
			beforeSend:function(json)
			{ 
				SimpleLoading.start('gears'); 
			},
			data: {
				'kodebarang':kodebarang,
				'namabarang':namabarang,
				'hargajual':hargajual
			},
			success: function(v){
				if(v.success == 1){
					$('#modal').modal('hide');
					$('#data-transaksi').html(v.data);
					$('#subtotal').html(v.subtotal);
					$('#total').html(v.total);
					$('#diskon').html(v.diskon);
					$('#field-total-tagihan').val(v.hidtotal);

					$('#jumlah-bayar').val('');
					$('#cashback').html('<b>Rp.0,00</b>');
					$('#field-total-bayar').val('');
					$('#field-total-cashback').val('');
					$('#field-kode-barang').val('');
					$('#field-kode-barang').focus();
				}else{
					Swal.fire({
						title: 'Gagal Simpan',
						html: v.msg,
						icon: "error"
					});
				}

				SimpleLoading.stop();
			}
		});

	});
	
	
    $(document).on("click", "#create-new-item", function () {
		let urlcreate = url_create_item;
		$('#modal').modal('show')
		.find('#modalContent')
		.load(urlcreate, function (responseTxt, statusTxt, xhr) {
			
		});
		

		$("#modal").on('shown.bs.modal', function () {
			$("#filebarang-kd_barang").focus();
		});

	});

	$(document).on("click", ".generate-code", function () {
        $.ajax({
            type: 'post',
            url: url_generate_code,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            success: function(v){
                $('#filebarang-kd_barang').val(v.kode);
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
                $('#popup-namabarang').focus();
            },
        });
    });

	$(document).on("click", "#uang-pas, #uang-5000, #uang-10000, #uang-20000, #uang-50000, #uang-100000", function () {
        let jumlah_bayar = $(this).attr('rel');
		let total_bayar = $('#field-total-tagihan').val();
		$.ajax({
            type: 'get',
            url: url_button_bayar,
			data:{
				'jumlah_bayar':jumlah_bayar,
				'total_bayar':total_bayar
			},
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            success: function(v){
                $('#field-total-bayar').val(v.jumlahbayar);
                $('#field-total-cashback').val(v.kembali);
				$('#jumlah-bayar').val(formatRupiah(v.jumlahbayar, 'Rp. '));

				var cashback = v.kembali;
				var nilai = formatRupiah(cashback.toString(), 'Rp. ');

				if(cashback < 0){
					$('#cashback').html("<b>Rp. 0,00</b>");
				}else{
					$('#cashback').html("<b>"+nilai+"</b>");
				}
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
                $('#proses-trans').focus();
            },
        });
    });
    
JS
);

?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.pin-pos-wrap { font-family: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; }
.pin-pos-wrap *, .pin-pos-wrap *::before, .pin-pos-wrap *::after { box-sizing: border-box; }

.pin-section-label {
	font-size: 14px; font-weight: 700; letter-spacing: 0.02em; color: #33332e;
	margin: 0 0 12px; padding: 10px 20px;
	background: #f6f6f3; border-radius: 16px; display: inline-block;
}
.pin-card {
	background: #ffffff; border-radius: 16px; padding: 24px;
	border: 1px solid #e5e5e0; margin-bottom: 16px;
}
.pin-card-soft {
	background: #fbfbf9; border-radius: 16px; padding: 24px;
	border: 1px solid #e5e5e0; margin-bottom: 16px;
}

.pin-input {
	font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 500; color: #000;
	background: #f6f6f3; border: 2px solid transparent; border-radius: 16px;
	padding: 10px 16px; height: 44px; width: 100%;
	transition: background .15s, border-color .15s;
}
.pin-input::placeholder { color: #91918c; font-weight: 400; }
.pin-input:focus { outline: none; background: #fff; border-color: #000; }
.pin-input:focus-visible { box-shadow: 0 0 0 3px #435ee5; }
.pin-input-group { position: relative; }
.pin-input-group .pin-input { padding-left: 42px; }
.pin-input-group .pin-input-icon {
	position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
	color: #91918c; font-size: 14px; pointer-events: none;
}

.pin-btn-primary {
	font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; line-height: 1;
	background: #e60023; color: #fff; border: none; border-radius: 16px;
	padding: 0 20px; height: 44px; cursor: pointer; width: 100%;
	transition: background .15s;
}
.pin-btn-primary:hover, .pin-btn-primary:focus { background: #cc001f; color: #fff; }

.pin-btn-secondary {
	font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; line-height: 1;
	background: #e5e5e0; color: #000; border: none; border-radius: 16px;
	padding: 0 20px; height: 44px; cursor: pointer; width: 100%;
	transition: background .15s;
}
.pin-btn-secondary:hover, .pin-btn-secondary:focus { background: #c8c8c1; color: #000; }

.pin-btn-accent {
	font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; line-height: 1;
	background: #f6f6f3; color: #000; border: 1px solid #e5e5e0; border-radius: 9999px;
	padding: 0 14px; height: 40px; cursor: pointer; width: 100%;
	transition: background .15s, border-color .15s;
}
.pin-btn-accent:hover { background: #e5e5e0; border-color: #c8c8c1; }

.pin-btn-process {
	font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 700; line-height: 1;
	background: #e60023; color: #fff; border: none; border-radius: 16px;
	padding: 0 24px; height: 52px; cursor: pointer; width: 100%;
	transition: background .15s; letter-spacing: -0.02em;
}
.pin-btn-process:hover, .pin-btn-process:focus { background: #cc001f; color: #fff; }

.pin-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.pin-table thead th {
	font-size: 12px; font-weight: 700; color: #62625b; text-transform: uppercase;
	padding: 12px 16px; border-bottom: 1px solid #e5e5e0; letter-spacing: 0.04em;
}
.pin-table tbody td {
	font-size: 14px; font-weight: 500; color: #000; padding: 14px 16px;
	border-bottom: 1px solid #f6f6f3;
}
.pin-table tbody tr:last-child td { border-bottom: none; }

.pin-pay-row {
	display: flex; justify-content: space-between; align-items: center;
	padding: 14px 0; border-bottom: 1px solid #f6f6f3;
}
.pin-pay-row:last-child { border-bottom: none; }
.pin-pay-label { font-size: 14px; font-weight: 500; color: #62625b; }
.pin-pay-value { font-size: 16px; font-weight: 700; color: #000; text-align: right; }
.pin-pay-total {
	display: flex; justify-content: space-between; align-items: center;
	padding: 16px 0; margin-top: 4px;
	border-top: 2px solid #000;
}
.pin-pay-total .pin-pay-label { font-size: 16px; font-weight: 700; color: #000; }
.pin-pay-total .pin-pay-value { font-size: 22px; font-weight: 700; color: #e60023; letter-spacing: -0.02em; }

.pin-pay-input {
	font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #000;
	background: #f6f6f3; border: 2px solid transparent; border-radius: 16px;
	padding: 10px 16px; height: 52px; width: 100%; text-align: right;
	transition: background .15s, border-color .15s;
}
.pin-pay-input:focus { outline: none; background: #fff; border-color: #000; }
.pin-pay-input:focus-visible { box-shadow: 0 0 0 3px #435ee5; }

.pin-cashback-row {
	display: flex; justify-content: space-between; align-items: center;
	padding: 16px 20px; margin-top: 12px;
	background: #f6f6f3; border-radius: 16px;
}
.pin-cashback-row .pin-pay-label { font-size: 14px; font-weight: 700; color: #33332e; }
.pin-cashback-row .pin-pay-value { font-size: 18px; font-weight: 700; color: #103c25; }

.pin-quick-pay { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin: 16px 0; }

.pin-heading-section {
	font-size: 22px; font-weight: 600; color: #000; letter-spacing: -0.02em;
	margin: 0 0 16px; line-height: 1.25;
}
</style>

<div class="pin-pos-wrap">

	<!-- Form Input -->
	<div class="row">
		<div class="col-md-12">
			<span class="pin-section-label"><i class="fa fa-shopping-cart" style="margin-right:6px;color:#e60023;"></i> Form Pembelian</span>
			<div class="pin-card">
				<div class="row" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:8px 0;">
					<div class="col-xs-2" style="padding-right:4px;">
						<label style="font-size:12px;font-weight:600;color:#62625b;margin-bottom:6px;display:block;">Kode Barang</label>
						<input type="text" class="pin-input" placeholder="Scan / ketik kode" id="field-kode-barang" tabindex="1">
					</div>
					<div class="col-xs-4" style="padding-left:4px;padding-right:4px;">
						<label style="font-size:12px;font-weight:600;color:#62625b;margin-bottom:6px;display:block;">Nama Barang</label>
						<div class="pin-input-group">
							<i class="fa fa-search pin-input-icon"></i>
							<?php
								echo Html::hiddenInput('total_tagihan', '', ['id'=>'field-total-tagihan']);
								echo Html::hiddenInput('total_bayar', '', ['id'=>'field-total-bayar']);
								echo Html::hiddenInput('total_cashback', '', ['id'=>'field-total-cashback']);
								echo AutoComplete::widget([
									'model' => $model,
									'attribute' => 'nama_barang',
									'options' => ['class' => 'pin-input','placeholder'=>'Cari nama barang...','tabindex'=>2, 'style'=>'padding-left:42px;'],
									'clientOptions' => [
										'source'=> Url::to(['filebarang/autocompletebarang']),
										'minLength'=>'2',
										'autoFill'=>true,
										'select' => new JsExpression("function( event, ui ) {
											$('#field-kode-barang').val(ui.item.id);
											$('#qty-barang').focus();
										}")
									],
								]); ?>
						</div>
					</div>
					<div class="col-xs-2" style="padding-left:4px;padding-right:4px;">
						<label style="font-size:12px;font-weight:600;color:#62625b;margin-bottom:6px;display:block;">Jumlah</label>
						<input type="number" class="pin-input" placeholder="Qty" id="qty-barang" tabindex="3" style="text-align:center;">
					</div>
					<div class="col-xs-2" style="padding-left:4px;padding-right:4px;">
						<button url="<?php echo Url::to(['site/prosestransaksi']);?>" id="process-transaction" type="button" class="pin-btn-primary" tabindex="4">
							<i class="fa fa-plus" style="margin-right:6px;"></i>Tambah
						</button>
					</div>
					<div class="col-xs-2" style="padding-left:4px;">
						<button url="<?php echo Url::to(['site/prosestransaksi']);?>" type="button" class="pin-btn-secondary" tabindex="4" id="create-new-item">
							<i class="fa fa-plus-circle" style="margin-right:6px;"></i>Barang Baru
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Transaction List + Payment -->
	<div class="row">
		<div class="col-md-8">
			<span class="pin-section-label"><i class="fa fa-list-ul" style="margin-right:6px;color:#e60023;"></i> Daftar Belanja</span>
			<div class="pin-card" style="padding:0;overflow:hidden;">
				<div id="data-transaksi" style="padding:0;">
					<table class="pin-table">
						<thead>
							<tr>
								<th style="width:50px;">No</th>
								<th>Nama Barang</th>
								<th>Harga</th>
								<th style="width:70px;">Qty</th>
								<th>Subtotal</th>
								<th style="width:80px;"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="6" style="text-align:center;padding:48px 16px;color:#91918c;font-size:14px;font-weight:500;">
									<i class="fa fa-inbox" style="font-size:32px;display:block;margin-bottom:12px;color:#c8c8c1;"></i>
									Belum ada barang ditambahkan
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<span class="pin-section-label"><i class="fa fa-credit-card" style="margin-right:6px;color:#e60023;"></i> Pembayaran</span>
			<div class="pin-card-soft">

				<div class="pin-pay-row">
					<span class="pin-pay-label">Subtotal</span>
					<span class="pin-pay-value" id="subtotal"><strong>Rp. 0,00</strong></span>
				</div>
				<div class="pin-pay-row">
					<span class="pin-pay-label">Diskon</span>
					<span class="pin-pay-value" id="diskon"><strong>Rp. 0,00</strong></span>
				</div>
				<div class="pin-pay-total">
					<span class="pin-pay-label">Total</span>
					<span class="pin-pay-value" id="total"><strong>Rp. 0,00</strong></span>
				</div>

				<div style="margin-top:20px;">
					<label style="font-size:12px;font-weight:700;color:#62625b;margin-bottom:8px;display:block;text-transform:uppercase;letter-spacing:0.04em;">Jumlah Bayar</label>
					<input id="jumlah-bayar" type="text" class="pin-pay-input" tabindex="5" placeholder="Rp. 0">
				</div>

				<div class="pin-quick-pay">
					<button rel="pas" id="uang-pas" type="button" class="pin-btn-accent" tabindex="6">Uang Pas</button>
					<button rel="5000" id="uang-5000" type="button" class="pin-btn-accent" tabindex="7">5.000</button>
					<button rel="10000" id="uang-10000" type="button" class="pin-btn-accent" tabindex="8">10.000</button>
					<button rel="20000" id="uang-20000" type="button" class="pin-btn-accent" tabindex="9">20.000</button>
					<button rel="50000" id="uang-50000" type="button" class="pin-btn-accent" tabindex="10">50.000</button>
					<button rel="100000" id="uang-100000" type="button" class="pin-btn-accent" tabindex="11">100.000</button>
				</div>

				<div class="pin-cashback-row">
					<span class="pin-pay-label">Kembali</span>
					<span class="pin-pay-value" id="cashback"><strong>Rp. 0,00</strong></span>
				</div>

				<div style="margin-top:16px;">
					<button url="<?php echo Url::to(['site/simpantransaksi']);?>" type="button" class="pin-btn-process" id="proses-trans" tabindex="12">
						<i class="fa fa-check-circle" style="margin-right:8px;"></i>Proses Pembayaran
					</button>
				</div>

			</div>
		</div>
	</div>

</div>
