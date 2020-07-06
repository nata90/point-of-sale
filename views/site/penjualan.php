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
	    'DISKON',
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
	  		type : 'dropdown'
	  	},
	  	
	  	{
	  		data : 'jumlah',
	  		type : 'numeric'
	  	},
	  	{
	  		data : 'diskon',
	  		type : 'numeric',
	  		numericFormat : {
	  			pattern: '0,00',
	  		}
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
	  minSpareRows: 15,
	  maxRows: 15,
      colWidths: [180, 130, 120, 120, 80, 120, 120, 120],
	  licenseKey: 'non-commercial-and-evaluation',
	  formulas: true,
	 columnSummary: [{
	  	  sourceColumn:7,
	      destinationRow: 0,
	      destinationColumn: 7,
	      reversedRowCoords: true,
	      type: 'sum',
	      forceNumeric: true
	  }],
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
	            that.setDataAtCell(rows, 5, v.diskon);
	            that.setDataAtCell(rows, 6, v.stok);
	            that.setDataAtCell(rows, 7, v.total);

	          }
	        });
	  		
	  	}

	  	if(name_column == 'jumlah'){
	  		var harga = that.getDataAtCell(rows,2);

	  		var new_total = parseInt(value) * parseInt(harga);
	  		that.setDataAtCell(rows, 7, new_total);
	  		that.setDataAtCell(rows, 5, 0);
	  	}

	  	if(name_column == 'diskon'){
	  		var harga = that.getDataAtCell(rows,2);
	  		var qty = that.getDataAtCell(rows,4);

	  		var new_total_2 = (parseInt(harga) * parseInt(qty)) - parseInt(value);
	  		that.setDataAtCell(rows, 7, new_total_2);
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



