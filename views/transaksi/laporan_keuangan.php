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
$this->registerJs('var url_hitung_kpi = "' . Url::to(['ajaxtransaksi/hitungkpi']) . '"');
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

        // Perbarui kartu ringkasan (KPI) sesuai tanggal yang dipilih
        $.ajax({
            type: 'get',
            url: url_hitung_kpi,
            dataType: 'json',
            data: { 'tgl_transaksi': tgl_transaksi },
            success: function(v){
                $('.total-rupiah-jual').html(v.modal_awal);
                $('.total-quantity-jual').html(v.total_penjualan);
                $('.kpi-pengeluaran').html(v.pengeluaran);
                $('.kpi-keuntungan').html(v.keuntungan);
            }
        });
    });

    
JS
);
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

/* ── Pinterest Design System tokens ── */
:root {
  --pin-primary:         #e60023;
  --pin-primary-pressed: #cc001f;
  --pin-ink:             #000000;
  --pin-body:            #33332e;
  --pin-mute:            #62625b;
  --pin-ash:             #91918c;
  --pin-hairline:        #dadad3;
  --pin-hairline-soft:   #e5e5e0;
  --pin-secondary-bg:    #e5e5e0;
  --pin-canvas:          #ffffff;
  --pin-surface-soft:    #fbfbf9;
  --pin-surface-card:    #f6f6f3;
  --pin-success-pale:    #c7f0da;
  --pin-success-deep:    #103c25;
  --pin-r-md:            16px;
  --pin-r-lg:            32px;
  --pin-r-full:          9999px;
  --pin-font:            'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* ── Page wrapper ── */
.pin-lk-page {
  font-family: var(--pin-font);
  color: var(--pin-body);
  background: var(--pin-surface-soft);
  padding: 8px 0 24px;
}

/* ── Filter card ── */
.pin-filter-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  padding: 20px 24px;
  margin-bottom: 16px;
  display: flex;
  align-items: flex-end;
  gap: 12px;
  flex-wrap: wrap;
}
.pin-filter-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.pin-filter-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--pin-mute);
  text-transform: uppercase;
  letter-spacing: .5px;
}
.pin-filter-input {
  height: 44px;
  padding: 0 16px;
  border: 1px solid var(--pin-hairline);
  border-radius: var(--pin-r-md);
  background: var(--pin-surface-card);
  font-family: var(--pin-font);
  font-size: 14px;
  color: var(--pin-ink);
  outline: none;
  transition: border-color .15s, background .15s;
  min-width: 180px;
}
.pin-filter-input:focus {
  background: var(--pin-canvas);
  border-color: var(--pin-ink);
}

/* ── Primary button ── */
.pin-btn-primary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  height: 44px;
  padding: 0 24px;
  background: var(--pin-primary);
  color: #fff;
  font-family: var(--pin-font);
  font-size: 14px;
  font-weight: 700;
  border: none;
  border-radius: var(--pin-r-md);
  cursor: pointer;
  transition: background .15s;
  white-space: nowrap;
  text-decoration: none;
}
.pin-btn-primary:hover, .pin-btn-primary:focus { background: var(--pin-primary-pressed); color: #fff; outline: none; }

/* ── KPI summary strip ── */
.pin-kpi-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 16px;
}
@media (max-width: 1024px) { .pin-kpi-strip { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 480px)  { .pin-kpi-strip { grid-template-columns: 1fr; } }

.pin-kpi {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  padding: 18px 20px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  position: relative;
  overflow: hidden;
}
.pin-kpi::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  border-radius: var(--pin-r-md) var(--pin-r-md) 0 0;
}
.pin-kpi.modal-awal::before    { background: #f59e0b; }
.pin-kpi.penjualan::before     { background: var(--pin-primary); }
.pin-kpi.pengeluaran::before   { background: #cc001f; }
.pin-kpi.keuntungan::before    { background: #059669; }

.pin-kpi-icon-wrap {
  width: 34px; height: 34px;
  border-radius: var(--pin-r-full);
  display: flex; align-items: center; justify-content: center;
  font-size: 14px;
  margin-bottom: 2px;
}
.pin-kpi.modal-awal  .pin-kpi-icon-wrap { background: #fef3c7; color: #b45309; }
.pin-kpi.penjualan   .pin-kpi-icon-wrap { background: #fde8eb; color: var(--pin-primary); }
.pin-kpi.pengeluaran .pin-kpi-icon-wrap { background: #fde8eb; color: #cc001f; }
.pin-kpi.keuntungan  .pin-kpi-icon-wrap { background: var(--pin-success-pale); color: var(--pin-success-deep); }

.pin-kpi-label {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-mute);
}
.pin-kpi-value {
  font-size: 20px;
  font-weight: 700;
  color: var(--pin-ink);
  letter-spacing: -.4px;
  line-height: 1.1;
}

/* ── Rekap card ── */
.pin-rekap-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  overflow: hidden;
}
.pin-rekap-head {
  padding: 18px 24px 0;
  font-size: 16px;
  font-weight: 600;
  color: var(--pin-ink);
}

/* ── Filter chip tabs ── */
.pin-tab-strip {
  display: flex;
  gap: 8px;
  padding: 16px 24px 0;
  border-bottom: 1px solid var(--pin-hairline);
}
.pin-tab-chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 36px;
  padding: 0 16px;
  border-radius: var(--pin-r-full) var(--pin-r-full) 0 0;
  font-family: var(--pin-font);
  font-size: 13px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  background: var(--pin-surface-card);
  color: var(--pin-mute);
  transition: background .12s, color .12s;
  position: relative;
  bottom: -1px;
  border-bottom: 2px solid transparent;
}
.pin-tab-chip.active,
.pin-tab-chip:focus {
  background: var(--pin-canvas);
  color: var(--pin-primary);
  border-color: var(--pin-primary);
  outline: none;
}

/* ── Tab pane ── */
.pin-tab-pane { display: none; padding: 20px 24px 24px; }
.pin-tab-pane.active { display: block; }

/* ── DataTable overrides ── */
.pin-lk-page table.dataTable {
  border-collapse: collapse !important;
  font-family: var(--pin-font);
  font-size: 13px;
  width: 100% !important;
}
.pin-lk-page table.dataTable thead th {
  background: var(--pin-surface-card) !important;
  color: var(--pin-mute) !important;
  font-size: 11px !important;
  font-weight: 700 !important;
  text-transform: uppercase;
  letter-spacing: .5px;
  border-bottom: 1px solid var(--pin-hairline) !important;
  border-top: none !important;
  padding: 10px 12px !important;
}
.pin-lk-page table.dataTable tbody td {
  border-bottom: 1px solid var(--pin-hairline-soft) !important;
  padding: 10px 12px !important;
  color: var(--pin-body);
  vertical-align: middle;
}
.pin-lk-page table.dataTable tbody tr:last-child td { border-bottom: none !important; }
.pin-lk-page table.dataTable tbody tr:hover td { background: var(--pin-surface-soft) !important; }

.pin-lk-page .dataTables_wrapper .dt-layout-row {
  margin-bottom: 12px;
}
.pin-lk-page .dt-search input,
.pin-lk-page .dataTables_filter input {
  border: 1px solid var(--pin-hairline);
  border-radius: var(--pin-r-full);
  padding: 6px 14px;
  font-family: var(--pin-font);
  font-size: 13px;
  outline: none;
  background: var(--pin-surface-card);
}
.pin-lk-page .dt-search input:focus,
.pin-lk-page .dataTables_filter input:focus {
  background: var(--pin-canvas);
  border-color: var(--pin-ink);
}
/* Excel button */
.pin-lk-page .dt-button,
.pin-lk-page .buttons-excel {
  display: inline-flex !important;
  align-items: center;
  gap: 6px;
  height: 36px !important;
  padding: 0 16px !important;
  background: var(--pin-secondary-bg) !important;
  color: var(--pin-ink) !important;
  font-family: var(--pin-font) !important;
  font-size: 13px !important;
  font-weight: 700 !important;
  border: none !important;
  border-radius: var(--pin-r-md) !important;
  cursor: pointer;
  transition: background .15s !important;
  box-shadow: none !important;
}
.pin-lk-page .dt-button:hover,
.pin-lk-page .buttons-excel:hover {
  background: var(--pin-hairline) !important;
  color: var(--pin-ink) !important;
}

.center-text { text-align: center !important; }
.right-text  { text-align: right  !important; }
.left-text   { text-align: left   !important; }
</style>

<div class="pin-lk-page">

  <!-- ── FILTER ── -->
  <div class="pin-filter-card">
    <?php echo $this->render('_search_laporan_keuangan', ['model' => $searchModel]); ?>
  </div>

  <!-- ── KPI STRIP ── -->
  <div class="pin-kpi-strip">

    <div class="pin-kpi modal-awal">
      <div class="pin-kpi-icon-wrap"><i class="fa fa-suitcase"></i></div>
      <span class="pin-kpi-label">Modal Awal</span>
      <span class="pin-kpi-value total-rupiah-jual"><?php echo Utility::rupiah($modal_awal); ?></span>
    </div>

    <div class="pin-kpi penjualan">
      <div class="pin-kpi-icon-wrap"><i class="fa fa-money"></i></div>
      <span class="pin-kpi-label">Total Penjualan</span>
      <span class="pin-kpi-value total-quantity-jual"><?php echo Utility::rupiah($total_penjualan); ?></span>
    </div>

    <div class="pin-kpi pengeluaran">
      <div class="pin-kpi-icon-wrap"><i class="fa fa-shopping-cart"></i></div>
      <span class="pin-kpi-label">Total Pengeluaran</span>
      <span class="pin-kpi-value kpi-pengeluaran"><?php echo Utility::rupiah($pengeluaran); ?></span>
    </div>

    <div class="pin-kpi keuntungan">
      <div class="pin-kpi-icon-wrap"><i class="fa fa-tags"></i></div>
      <span class="pin-kpi-label">Keuntungan</span>
      <span class="pin-kpi-value kpi-keuntungan"><?php echo Utility::rupiah($keuntungan); ?></span>
    </div>

  </div><!-- /.pin-kpi-strip -->

  <!-- ── REKAP TRANSAKSI ── -->
  <div class="pin-rekap-card">

    <p class="pin-rekap-head">Rekap Transaksi</p>

    <!-- Tab chips -->
    <div class="pin-tab-strip">
      <button class="pin-tab-chip active" data-target="tab_1">
        <i class="fa fa-list-alt"></i> Rekap Penjualan
      </button>
      <button class="pin-tab-chip" data-target="tab_2">
        <i class="fa fa-minus-circle"></i> Rekap Pengeluaran
      </button>
    </div>

    <!-- Tab pane: Penjualan -->
    <div class="pin-tab-pane active" id="tab_1">
      <table id="table-laporan-penjualan" class="display compact" style="width:100%;">
        <thead>
          <tr>
            <th>No</th>
            <th>No Transaksi</th>
            <th>Kode Barang</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Qty</th>
            <th>Total</th>
          </tr>
        </thead>
      </table>
    </div>

    <!-- Tab pane: Pengeluaran -->
    <div class="pin-tab-pane" id="tab_2">
      <table id="table-laporan-pengeluaran" class="display compact" style="width:100%;">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Nominal Pengeluaran</th>
          </tr>
        </thead>
      </table>
    </div>

  </div><!-- /.pin-rekap-card -->

</div><!-- /.pin-lk-page -->

<?php $this->registerJs(<<<JS
  // ── Custom tab chip handler ──
  $(document).on('click', '.pin-tab-chip', function () {
    var target = $(this).data('target');
    $('.pin-tab-chip').removeClass('active');
    $(this).addClass('active');
    $('.pin-tab-pane').removeClass('active');
    $('#' + target).addClass('active');

    // trigger DataTable adjust on show
    if (target === 'tab_1') {
      $('#table-laporan-penjualan').DataTable().columns.adjust().draw();
    }
    if (target === 'tab_2') {
      if (!$.fn.DataTable.isDataTable('#table-laporan-pengeluaran')) {
        $('#table-laporan-pengeluaran').DataTable({
          ajax: { url: url_get_pengeluaran + (last_tgl_transaksi ? '&tgl_transaksi=' + encodeURIComponent(last_tgl_transaksi) : ''), dataSrc: '' },
          info: false, paging: true, sorting: false, searching: false,
          scrollY: "280px", scrollCollapse: true,
          layout: { topStart: { buttons: [{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Export Excel', filename: 'excel_laporan_pengeluaran', exportOptions: { modifier: { page: 'all' } } }] } },
          columns: [
            { data: null, className: 'center-text', render: function(d,t,r,m){ return m.row+1; } },
            { data: 'tgl_transaksi', className: 'center-text' },
            { data: 'keterangan' },
            { data: 'nominal_pengeluaran', render: $.fn.dataTable.render.number(',','.', 0,'Rp ') }
          ]
        });
      } else {
        $('#table-laporan-pengeluaran').DataTable().columns.adjust().draw();
      }
    }
  });
JS
); ?>