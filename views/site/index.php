<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;



$storeName = ($setting && !empty($setting->app_name)) ? $setting->app_name : 'POS';
$this->title = 'PENJUALAN - ' . strtoupper($storeName);
$this->registerJs('var ip_addr = "' . $setting->ip_address . '";');
$this->registerJs('var url_get_nama = "' .Url::to(['site/getnamabarang']). '";');
$this->registerJs('var url_proses_transaksi = "' .Url::to(['site/prosestransaksi']). '";');
$this->registerJs('var url_create_item = "' .Url::to(['filebarang/createnewbarang']). '";');
$this->registerJs('var url_generate_code = "' .Url::to(['filebarang/getkodebarang']). '";');
$this->registerJs('var url_button_bayar = "' .Url::to(['transaksi/hitungpembayaran']). '";');
$this->registerJs(<<<JS
	$('#field-kode-barang').focus();

	var diskonMode = 'rp'; // 'rp' or 'percent'

	function hitungUlangTagihan() {
		var rawSubtotal = parseInt($('#field-subtotal').val()) || 0;
		var inputVal = ($('#field-input-diskon').val() || '').trim();
		var rawDiskon = 0;

		if (diskonMode === 'percent') {
			var pct = parseFloat(inputVal.replace('%', '').replace(',', '.')) || 0;
			if (pct < 0) pct = 0;
			if (pct > 100) pct = 100;
			rawDiskon = Math.round(rawSubtotal * (pct / 100));
		} else {
			var remrp = inputVal.replace("Rp. ", "").replace("Rp ", "").replace("Rp.", "");
			var remdot = remrp.split(".").join("");
			rawDiskon = parseInt(remdot) || 0;
		}

		if (rawDiskon < 0) rawDiskon = 0;
		if (rawDiskon > rawSubtotal) rawDiskon = rawSubtotal;

		var totalTagihan = rawSubtotal - rawDiskon;
		if (totalTagihan < 0) totalTagihan = 0;

		$('#field-diskon').val(rawDiskon);
		$('#field-total-tagihan').val(totalTagihan);

		// Format display subtotal
		$('#subtotal').html('<strong>' + formatRupiah(rawSubtotal.toString(), 'Rp. ') + '</strong>');

		// Format display diskon
		if (rawDiskon > 0) {
			var diskonText = '- ' + formatRupiah(rawDiskon.toString(), 'Rp. ');
			if (diskonMode === 'percent') {
				var pctDisplay = parseFloat(inputVal) || 0;
				diskonText += ' <span style="font-size:12px;font-weight:600;opacity:0.85;">(' + pctDisplay + '%)</span>';
			}
			$('#diskon').html('<strong>' + diskonText + '</strong>');
			$('#btn-reset-diskon').show();
		} else {
			$('#diskon').html('<strong>Rp. 0,00</strong>');
			$('#btn-reset-diskon').hide();
		}

		// Format display total
		$('#total').html('<strong>' + formatRupiah(totalTagihan.toString(), 'Rp. ') + '</strong>');

		// Hitung ulang cashback jika jumlah bayar sudah diisi
		var bayar = $('#jumlah-bayar').val();
		if (bayar) {
			var remrp = bayar.replace("Rp. ", "").replace("Rp ", "").replace("Rp.", "");
			var remdot = remrp.split(".").join("");
			var bayarNominal = parseInt(remdot) || 0;
			var cashback = bayarNominal - totalTagihan;

			$('#field-total-bayar').val(bayarNominal);
			$('#field-total-cashback').val(cashback);

			if (cashback < 0) {
				$('#cashback').html("<b>Rp. 0,00</b>");
			} else {
				$('#cashback').html("<b>" + formatRupiah(cashback.toString(), 'Rp. ') + "</b>");
			}
		}
	}

	$(document).on("click", ".pin-btn-mode", function () {
		var mode = $(this).data('mode');
		diskonMode = mode;
		$('.pin-btn-mode').removeClass('active');
		$(this).addClass('active');
		if (mode === 'percent') {
			$('#field-input-diskon').attr('placeholder', 'Ketik diskon (% 0-100)...');
		} else {
			$('#field-input-diskon').attr('placeholder', 'Ketik diskon (Rp)...');
		}
		$('#field-input-diskon').val('');
		hitungUlangTagihan();
		$('#field-input-diskon').focus();
	});

	$(document).on("keyup", "#field-input-diskon", function () {
		if (diskonMode === 'rp') {
			this.value = formatRupiah(this.value, 'Rp. ');
		} else {
			var clean = this.value.replace(/[^0-9.]/g, '');
			if (parseFloat(clean) > 100) clean = '100';
			this.value = clean;
		}
		hitungUlangTagihan();
	});

	$(document).on("click", ".pin-pill-diskon", function () {
		var pct = $(this).data('val');
		diskonMode = 'percent';
		$('.pin-btn-mode').removeClass('active');
		$('#btn-mode-percent').addClass('active');
		$('#field-input-diskon').attr('placeholder', 'Ketik diskon (% 0-100)...');
		$('#field-input-diskon').val(pct > 0 ? pct : '');
		hitungUlangTagihan();
	});

	$(document).on("click", "#btn-reset-diskon", function () {
		$('#field-input-diskon').val('');
		hitungUlangTagihan();
		$('#field-input-diskon').focus();
	});

	$(document).on("click", "#process-transaction", function () {
		var url = $(this).attr('url');
		var kodebarang = $('#field-kode-barang').val();
		var namabarang = $('#filebarang-nama_barang').val();
		var qty = $('#qty-barang').val();
		var diskon = $('#field-diskon').val() || 0;

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
					'namabarang':namabarang,
					'diskon':diskon
				},
				success: function(v){
					if(v.datafound == 0){
						Swal.fire({
							title: v.msg || v.ms,
							icon: "error"
						});
					}else{
						$('#data-transaksi').html(v.data);
						$('#filebarang-nama_barang').val('');
						$('#field-kode-barang').val('');
						$('#qty-barang').val('');
						$('#filebarang-nama_barang').focus();
						var subtotal = (v.hidsubtotal !== undefined) ? v.hidsubtotal : (v.hidtotal || 0);
						$('#field-subtotal').val(subtotal);
						hitungUlangTagihan();
						$('#field-kode-barang').focus();
					}
					
				}
			});
		}
		
    });

    $(document).on("click", ".delete-item", function () {
    	var rel = $(this).attr('rel');
    	var url = $(this).attr('url');
    	var diskon = $('#field-diskon').val() || 0;

    	$.ajax({
			type: 'get',
			url: url,
			dataType: 'json',
			data: {'rel':rel, 'diskon':diskon},
			success: function(v){
				$('#data-transaksi').html(v.data);
				var subtotal = (v.hidsubtotal !== undefined) ? v.hidsubtotal : (v.hidtotal || 0);
				$('#field-subtotal').val(subtotal);
				hitungUlangTagihan();
			}
		});
    });
    
    var rupiah = document.getElementById('jumlah-bayar');
	if (rupiah) {
		rupiah.addEventListener('keyup', function(e){
			rupiah.value = formatRupiah(this.value, 'Rp. ');
		});
	}
    
	$(document).on("keyup", "#jumlah-bayar", function () {
    	var bayar = $(this).val();
    	var remrp = bayar.replace("Rp. ","").replace("Rp ","").replace("Rp.","");
    	var remdot = remrp.split(".").join("");

		var total = $('#field-total-tagihan').val();
		var cashback = parseInt(remdot || 0) - parseInt(total || 0);
		var nilai = formatRupiah(cashback.toString(), 'Rp. ');

		$('#field-total-bayar').val(remdot || 0);
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
    	var diskon = $('#field-diskon').val() || 0;
    	var url = $(this).attr('url');

    	if(!totaltagihan || parseInt(totaltagihan) < 0 || $('#field-subtotal').val() == 0){
			Swal.fire({
				title: "Belum Ada Item Belanja",
				text: "Silakan masukkan barang belanja terlebih dahulu.",
				icon: "warning"
			});
			return;
		}

    	if(!totalbayar || parseInt(totalbayar) === 0){
			Swal.fire({
				title: "Jumlah Bayar Wajib Di Isi",
				icon: "error"
			});
		}else{
			if(parseInt(cashback) < 0){
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
						'cashback':cashback,
						'diskon':diskon
					},
					success: function(v){
						if(v.success == 1){
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
									'namabarang':v.nama_barang,
									'diskon': $('#field-diskon').val() || 0
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
										var subtotal = (v.hidsubtotal !== undefined) ? v.hidsubtotal : (v.hidtotal || 0);
										$('#field-subtotal').val(subtotal);
										hitungUlangTagihan();
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
		let diskon = $('#field-diskon').val() || 0;
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
				'kodebarang':kodebarang,
				'diskon':diskon
			},
			success: function(v){
				if(v.success == 1){
					$('#modal').modal('hide');
					$('#data-transaksi').html(v.data);
					var subtotal = (v.hidsubtotal !== undefined) ? v.hidsubtotal : (v.hidtotal || 0);
					$('#field-subtotal').val(subtotal);
					hitungUlangTagihan();
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
		let stok = $('#filebarang-stok').val();
		let diskon = $('#field-diskon').val() || 0;
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
				'hargajual':hargajual,
				'stok':stok,
				'diskon':diskon
			},
			success: function(v){
				if(v.success == 1){
					$('#modal').modal('hide');
					$('#data-transaksi').html(v.data);
					var subtotal = (v.hidsubtotal !== undefined) ? v.hidsubtotal : (v.hidtotal || 0);
					$('#field-subtotal').val(subtotal);
					hitungUlangTagihan();
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

.pin-diskon-card {
	background: #ffffff;
	border: 1px solid #e5e5e0;
	border-radius: 16px;
	padding: 14px 16px;
	margin-top: 14px;
	transition: border-color .15s;
}
.pin-diskon-card:focus-within {
	border-color: #000;
}
.pin-btn-mode {
	border: none;
	background: transparent;
	color: #62625b;
	font-family: 'Inter', sans-serif;
	font-size: 11px;
	font-weight: 700;
	padding: 4px 12px;
	border-radius: 9999px;
	cursor: pointer;
	transition: background .15s, color .15s;
}
.pin-btn-mode.active {
	background: #000 !important;
	color: #fff !important;
}
.pin-pill-diskon {
	border: 1px solid #e5e5e0;
	background: #f6f6f3;
	color: #000;
	font-family: 'Inter', sans-serif;
	font-size: 11px;
	font-weight: 700;
	padding: 5px 8px;
	border-radius: 9999px;
	cursor: pointer;
	text-align: center;
	transition: background .15s, border-color .15s, color .15s;
	flex: 1;
}
.pin-pill-diskon:hover {
	background: #000;
	color: #fff;
	border-color: #000;
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
								echo Html::hiddenInput('total_subtotal', '0', ['id'=>'field-subtotal']);
								echo Html::hiddenInput('diskon', '0', ['id'=>'field-diskon']);
								echo Html::hiddenInput('total_tagihan', '0', ['id'=>'field-total-tagihan']);
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
				<div class="pin-pay-row" style="background:#fff;border-radius:12px;padding:10px 14px;margin:8px 0;border:1px dashed #e5e5e0;">
					<span class="pin-pay-label" style="display:flex;align-items:center;gap:6px;font-weight:600;color:#33332e;">
						<i class="fa fa-tag" style="color:#e60023;"></i>
						Diskon
					</span>
					<span class="pin-pay-value" id="diskon" style="color:#e60023;"><strong>Rp. 0,00</strong></span>
				</div>
				<div class="pin-pay-total">
					<span class="pin-pay-label">Total Tagihan</span>
					<span class="pin-pay-value" id="total"><strong>Rp. 0,00</strong></span>
				</div>

				<!-- Form Input Diskon Penjualan -->
				<div class="pin-diskon-card">
					<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
						<label style="font-size:12px;font-weight:700;color:#33332e;margin:0;display:flex;align-items:center;gap:6px;text-transform:uppercase;letter-spacing:0.04em;">
							<i class="fa fa-percent" style="color:#e60023;font-size:11px;"></i> Form Diskon
						</label>
						<div class="pin-diskon-toggle" style="display:inline-flex;background:#f6f6f3;padding:2px;border-radius:9999px;">
							<button type="button" class="pin-btn-mode active" id="btn-mode-rp" data-mode="rp">Rp</button>
							<button type="button" class="pin-btn-mode" id="btn-mode-percent" data-mode="percent">%</button>
						</div>
					</div>

					<div style="position:relative;">
						<input type="text" id="field-input-diskon" class="pin-input" placeholder="Ketik nominal diskon (Rp)..." style="height:40px;font-size:14px;font-weight:600;padding-right:32px;">
						<button type="button" id="btn-reset-diskon" title="Hapus Diskon" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);border:none;background:transparent;color:#91918c;cursor:pointer;padding:4px;display:none;">
							<i class="fa fa-times-circle" style="font-size:16px;"></i>
						</button>
					</div>

					<div class="pin-quick-diskon" style="display:flex;gap:4px;margin-top:8px;flex-wrap:wrap;">
						<button type="button" class="pin-pill-diskon" data-val="0">0%</button>
						<button type="button" class="pin-pill-diskon" data-val="5">5%</button>
						<button type="button" class="pin-pill-diskon" data-val="10">10%</button>
						<button type="button" class="pin-pill-diskon" data-val="15">15%</button>
						<button type="button" class="pin-pill-diskon" data-val="20">20%</button>
						<button type="button" class="pin-pill-diskon" data-val="50">50%</button>
					</div>
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
