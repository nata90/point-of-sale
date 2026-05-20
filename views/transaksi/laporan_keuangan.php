<?php

use app\assets\Datatables2Asset;
use yii\helpers\Html;
use app\components\Utility;
use app\models\DtTransaksiSearch;
use app\models\SettingApp;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DtTransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Datatables2Asset::register($this);

$this->title = Yii::t('app', 'Laporan Keuangan');
$this->registerJs('var url_total = "' . Url::to(['transaksi/hitungtotalpenjualan']) . '";');
$this->registerJs('var url_get_penjualan = "' . Url::to(['ajaxtransaksi/getdatapenjualan']) . '"');
$this->registerJs('var url_get_pengeluaran = "' . Url::to(['ajaxtransaksi/getdatapengeluaran']) . '"');
$this->registerJs('var url_search_laporan_keuangan = "' . Url::to(['ajaxtransaksi/searchlaporankeuangan']) . '"');
$this->registerJs('var last_tgl_transaksi = "";');
$this->registerJs(<<<JS

    $(document).on("pjax:beforeSend", function(){
        SimpleLoading.start('gears');
    });

    $(document).on("pjax:complete", function(){
        SimpleLoading.stop();
        $.ajax({
            type: 'post',
            url: url_total,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            success: function(v){
                $('.total-rupiah-jual').html(v.rupiahtotal);
                $('.total-quantity-jual').html(v.qtytotal);
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
            },
        });
    });

    $('#table-laporan-penjualan').DataTable({
        ajax: {
            url: url_get_penjualan, 
            dataSrc: '' 
        },
        info: false,
        paging:true,
        sorting:false,
        searching:false,
        orderCellsTop: true,
        scrollY: "200px",
        scrollCollapse: true,
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Export to Excel', 
                        filename: 'excel_laporan_penjualan', 
                        exportOptions: {
                            modifier: {
                                page: 'all' 
                            }
                        }
                    }
                ]
            }
        },
        columns:[
            { 
                data: null,
                className: 'center-text',
                render: function (data, type, row, meta) {
                    return meta.row + 1; 
                }
            },
            {
                data: 'no_transaksi',
                className: 'center-text',
            },
            {
                data: 'kode_barang'
            },
           
            {
                data: 'tgl_transaksi',
                className: 'center-text',
            },
            {
                data: 'nama_barang'
            },
            {
                data: 'harga_satuan',
                render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp ' )
            },
            {
                data: 'qty',
                className: 'center-text',
            },
            {
                data: 'total',
                render: $.fn.dataTable.render.number( ',', '.', 0, 'Rp ' )
            },
        ],

    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");

        if (target === "#tab_1") {
            $('#table-laporan-penjualan').DataTable().columns.adjust().draw();
        }

        if (target === "#tab_2") {
            if (!$.fn.DataTable.isDataTable('#table-laporan-pengeluaran')) {
                $('#table-laporan-pengeluaran').DataTable({
                    ajax: {
                        url: url_get_pengeluaran + (last_tgl_transaksi ? '&tgl_transaksi=' + encodeURIComponent(last_tgl_transaksi) : ''),
                        dataSrc: ''
                    },
                    info: false,
                    paging: true,
                    sorting: false,
                    searching: false,
                    orderCellsTop: true,
                    scrollY: "200px",
                    scrollCollapse: true,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    extend: 'excel',
                                    text: 'Export to Excel',
                                    filename: 'excel_laporan_pengeluaran',
                                    exportOptions: {
                                        modifier: {
                                            page: 'all'
                                        }
                                    }
                                }
                            ]
                        }
                    },
                    columns: [
                        {
                            data: null,
                            className: 'center-text',
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'tgl_transaksi',
                            className: 'center-text'
                        },
                        {
                            data: 'keterangan'
                        },
                        {
                            data: 'nominal_pengeluaran',
                            render: $.fn.dataTable.render.number(',', '.', 0, 'Rp ')
                        }
                    ]
                });
            } else {
                $('#table-laporan-pengeluaran').DataTable().columns.adjust().draw();
            }
        }
    });

    $(document).on('click', '#search-laporan-keuangan', function(e){
        let tgl_transaksi = $('#tgl-transaksi').val();
        last_tgl_transaksi = tgl_transaksi; // simpan pencarian terakhir

        // Tampilkan loading
        SimpleLoading.start('gears');

        let penjualanDone = false;
        let pengeluaranDone = false;

        function stopLoadingIfDone() {
            if (penjualanDone && pengeluaranDone) {
                SimpleLoading.stop();
            }
        }

        // Penjualan
        if ($.fn.DataTable.isDataTable('#table-laporan-penjualan')) {
            $('#table-laporan-penjualan').DataTable().ajax.url(
                url_get_penjualan + '&tgl_transaksi=' + encodeURIComponent(tgl_transaksi)
            ).load(function(){
                penjualanDone = true;
                stopLoadingIfDone();
            });
        } else {
            penjualanDone = true;
            stopLoadingIfDone();
        }

        // Pengeluaran: hanya reload jika sudah pernah diinisialisasi
        if ($.fn.DataTable.isDataTable('#table-laporan-pengeluaran')) {
            $('#table-laporan-pengeluaran').DataTable().ajax.url(
                url_get_pengeluaran + '&tgl_transaksi=' + encodeURIComponent(tgl_transaksi)
            ).load(function(){
                pengeluaranDone = true;
                stopLoadingIfDone();
            });
        } else {
            pengeluaranDone = true;
            stopLoadingIfDone();
        }
    });

    
JS
);
?>
<style>
    .center-text {
        text-align: center !important;
    }

    .right-text {
        text-align: right;
    }

    .left-text {
        text-align: left;
    }

</style>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
                <?php echo $this->render('_search_laporan_keuangan', ['model' => $searchModel]); ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12" style="padding-left:0px;">
    <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-suitcase"></i></span>
        <div class="info-box-content">
            
            <span class="info-box-text"><strong>MODAL AWAL</strong></span>
            <span class="info-box-number total-rupiah-jual"><?php echo Utility::rupiah($modal_awal)?></span>
        </div>
        
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12" style="padding-left:0px;">
    <div class="info-box">
        <span class="info-box-icon bg-blue"><i class="fa fa-money"></i></span>
        <div class="info-box-content">
            <span class="info-box-text"><strong>TOTAL PENJUALAN</strong></span>
            <span class="info-box-number total-quantity-jual"><?php echo Utility::rupiah($total_penjualan);?></span>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12" style="padding-left:0px;">
    <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>
        <div class="info-box-content">
            <span class="info-box-text"><strong>TOTAL PENGELUARAN</strong></span>
            <span class="info-box-number total-quantity-jual"><?php echo Utility::rupiah($pengeluaran);?></span>
        </div>
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-12" style="padding-left:0px;">
    <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-tags"></i></span>
        <div class="info-box-content">
            <span class="info-box-text"><strong>KEUNTUNGAN</strong></span>
            <span class="info-box-number total-quantity-jual"><?php echo Utility::rupiah($keuntungan)?></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Transaksi</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Rekap Penjualan</a></li>
                        <li><a href="#tab_2" data-toggle="tab">Rekap Pengeluaran</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <table id="table-laporan-penjualan" class="display compact" style="width:100%;">
                                <thead style="background-color: #E6E6FA;color:black;">
                                    <tr>
                                        <th>NO</th>
                                        <th>NO TRANSAKSI</th>
                                        <th>KODE BARANG</th>
                                        <th>TANGGAL TRANSAKSI</th>
                                        <th>NAMA BARANG</th>
                                        <th>HARGA SATUAN</th>
                                        <th>QTY</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                            </table>
                            
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <table id="table-laporan-pengeluaran" class="display compact" style="width:100%;">
                                <thead style="background-color: #E6E6FA;color:black;">
                                    <tr>
                                        <th>NO</th>
                                        <th>TANGGAL TRANSAKSI</th>
                                        <th>KETERANGAN</th>
                                        <th>NOMINAL PENGELUARAN</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        

    </div>
</div>