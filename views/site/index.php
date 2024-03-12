<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;



$this->title = 'PENJUALAN - POS';
$style = <<< CSS

.ui-menu-item .ui-menu-item-wrapper.ui-state-active {
    background: #ADD8E6 !important;
    font-weight: bold !important;
    color: #000000 !important;
} 
CSS;
$this->registerCss($style);
$this->registerJs('var ip_addr = "' . $setting->ip_address . '";');
$this->registerJs('var url_get_nama = "' .Url::to(['site/getnamabarang']). '";');
$this->registerJs('var url_proses_transaksi = "' .Url::to(['site/prosestransaksi']). '";');
$this->registerJs('var url_create_item = "' .Url::to(['filebarang/createnewbarang']). '";');
$this->registerJs('var url_generate_code = "' .Url::to(['filebarang/getkodebarang']). '";');
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
						var head = 'Penjualan : '+v.nopenjualan;
					    var msg = v.items;
						location.replace(v.redirect);
					},
					'complete':function(json)
					{
						SimpleLoading.stop();
					},
				});
	    	}
		}
    	
    });


	$(document).on("focusout", "#field-kode-barang", function () {
		var kodebarang = $(this).val();
		var qty = 1;
		var ajaxTimeout = null;

		var barcode = $(this).val();

		if (ajaxTimeout) {
			clearTimeout(ajaxTimeout);
		}

		

		if(kodebarang != ''){
			ajaxTimeout = setTimeout(function() {
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
			}, 200);
		}

		
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
		$('#modal .modal-header #header-info').html('<h4>BUAT BARANG BARU</h4>');

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
    
JS
);

?>


	<div class="row">

		<div class="col-md-12">
			<div class="box box-danger">
				
				<div class="box-body">
					<div class="row">
							<div class="col-xs-2">
								<input type="text" class="form-control input-sm" placeholder="KODE BARANG" id="field-kode-barang" tabindex="1">
							</div>
							<div class="col-xs-4">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-tasks"></i></span>

										<?php 
										
										/*echo Html::hiddenInput('id_stok', '', ['id'=>'field-id-stokbarang']);*/
										echo Html::hiddenInput('total_tagihan', '', ['id'=>'field-total-tagihan']);
										echo Html::hiddenInput('total_bayar', '', ['id'=>'field-total-bayar']);
										echo Html::hiddenInput('total_cashback', '', ['id'=>'field-total-cashback']);
										echo AutoComplete::widget([
											'model' => $model,
											'attribute' => 'nama_barang',
											'options' => ['class' => 'form-control input-sm','placeholder'=>'NAMA BARANG','tabindex'=>2],
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
							<div class="col-xs-2">
								<input type="number" class="form-control input-sm" placeholder="JUMLAH" id="qty-barang" tabindex="3">
							</div>
							<div class="col-xs-2">
								<button url="<?php echo Url::to(['site/prosestransaksi']);?>" id="process-transaction" type="button" class="btn btn-block btn-primary btn-sm" tabindex="4">ADD</button>
							</div>
							<div class="col-xs-2">
								<button url="<?php echo Url::to(['site/prosestransaksi']);?>"type="button" class="btn btn-block btn-success btn-sm" tabindex="4" id="create-new-item">CREATE BARANG</button>
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
					<table class="table table-stripped">
						<tbody>
							<tr class="table-title">
								<th>NO</th>
								<th>NAMA BARANG</th>
								<th>HARGA</th>
								<th>QTY</th>
								<th>SUBTOTAL</th>
								<th></th>
							</tr>
							
						</tbody>
						<?php /*<tfoot>
							<tr>
								<td colspan="6">
									<div class="col-xs-4">
										<button type="button" class="btn btn-block btn-danger btn-sm">BATALKAN TRANSAKSI</button>
									</div>
								</td>
							</tr>
						</tfoot>*/ ?>
					</table>
				</div>
			</div>
		</div>	
		<div class="col-md-4">
			<div class="box box-danger">
				<div class="box-body">
					<ul class="todo-list ui-sortable">
					    <li><span class="text">SUBTOTAL</span><span class="pull-right" id="subtotal"><strong>Rp. 0,00</strong></span></li>
					    <li><span class="text">DISKON</span><span class="pull-right" id="diskon"><strong>Rp. 0,00</strong></span></li>
					    <li><span class="text">TOTAL</span><span class="pull-right" id="total"><strong>Rp. 0,00</strong></span></li>
					    <li><span class="text">BAYAR</span><span class="pull-right"><input id="jumlah-bayar" type="text" class="form-control input-sm" size="14" tabindex="5"></span></li>
					    <li><span class="text">KEMBALI</span><span class="pull-right" id="cashback"><strong>Rp. 0,00</strong></span></li>
					    <li><button url="<?php echo Url::to(['site/simpantransaksi']);?>" type="button" class="btn btn-block btn-success" id="proses-trans" tabindex="6">PROSES</button></li>
					</ul>	
				    
				</div>
			</div>
		</div>		
	</div>



