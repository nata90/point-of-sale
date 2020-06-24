<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;


$this->title = 'PENJUALAN - POS';
$this->registerJs('var url = "' . Url::to(['site/autocompletebarang']) . '";');
$this->registerJs('var new_url = "' . Url::to(['site/loaddatabarang']) . '";');
$this->registerJs(<<<JS
	
	/*var data = [
		{kd_barang : 'A0001', nama_barang : 'PARACETAMOL 500 MG', harga : 10000, jumlah : 3, total : 30000},
		{kd_barang : 'A0002', nama_barang : 'DEXAMETHAZONE', harga : 20000, jumlah : 3, total : 60000},
		{kd_barang : 'BRG000003', nama_barang : 'BUSCHOPAN', harga : 15000, jumlah : 2, total : 30000},
	];*/
	var data = [];

	var container = document.getElementById('data-trans');
	var hot = new Handsontable(container, {
	  data: data,
	  rowHeaders: true,
	  colHeaders: [
	  	'NAMA BARANG',
	  	'KODE BARANG',
	    'HARGA',
	    'TGL ED',
	    'JUMLAH',
	    'STOK AKHIR',
	    'TOTAL',
	  ],
	  columns: [
	  	{
	  		data : 'nama_barang',
	  		type : 'autocomplete',
	  		source: function (query, process) {
		        $.ajax({
		          url: url,
		          dataType: 'json',
		          data: {
		            query: query
		          },
		          success: function (response) {
		            console.log("response", response);
		            process(response.data);

		          }
		        });
		      },
		      strict: true,
		      allowInvalid: true
	  	},
	  	{
	  		data : 'kd_barang',
	  		type : 'text'
	  	},
	  	{
	  		data : 'harga',
	  		type : 'numeric',
	  		numericFormat : {
	  			pattern: '0,00',
	  		}
	  	},
	  	{
	  		data : 'tgl_ed',
	  		type : 'date'
	  	},
	  	
	  	{
	  		data : 'jumlah',
	  		type : 'numeric'
	  	},
	  	{
	  		data : 'stok_akhir',
	  		type : 'numeric'
	  	},
	  	{
	  		data : 'total',
	  		type : 'numeric',
	  		numericFormat : {
	  			pattern: '0,00',
	  		}
	  	},
	  ],
	  filters: false,
	  dropdownMenu: false,
	  minSpareRows: 1,
      colWidths: [250, 150, 120, 120, 100, 120, 120],
	  licenseKey: 'non-commercial-and-evaluation',
	  afterChange : function( changes, source ) {
	  	var string = String(changes);
	  	var explode = string.split(",");
	  	
	  	var value = explode[3];
	  	var rows = explode[0];
	  	var name_column = explode[1];
	  	var that = this;

	  	if(name_column == 'nama_barang'){
	  		$.ajax({
	  		  type: 'get',
	          url: new_url,
	          dataType: 'json',
	          data: {
	            value: value
	          },
	          success: function (v) {
	            that.setDataAtCell(rows, 1, v.kode);
	            that.setDataAtCell(rows, 2, v.harga);
	            that.setDataAtCell(rows, 3, v.ed);
	            that.setDataAtCell(rows, 4, v.jumlah);
	            that.setDataAtCell(rows, 5, v.stok);
	            that.setDataAtCell(rows, 6, v.total);

	          }
	        });
	  		
	  	}
	  }
	});
    
    hot.selectCell(0,0);
    
JS
);

?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-danger box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Penjualan</h3>
			</div>
			<div class="box-body">
				
				<div id="data-trans">
					
				</div>
			</div>
		</div>
	</div>	
			
</div>



