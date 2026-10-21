<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\components\Utility;
use yii\web\View;


$this->title = 'RESUME TRANSAKSI';
$this->registerJs(<<<JS
	$(document).on("click", "#new-transaction", function () {
    	var url = $(this).attr("url");
    	location.replace(url);
    });   

    $(document).on("click", "#cancel-transaction", function () {
    	var url = $(this).attr("url");
    	
    	if(confirm("Anda yakin ingin menghapus transaksi ini ?")){
    		$.ajax({
				type: 'get',
				url: url,
				dataType: 'json',
				'beforeSend':function(json)
				{ 
					SimpleLoading.start('gears'); 
				},
				success: function(v){
					location.replace(v.redirect);
				},
				'complete':function(json)
				{
					SimpleLoading.stop();
				},
			});
    	}
    	
    }); 

	$('#new-transaction').focus();

	$(document).on("click", "#print-transaction", function (e) {
        e.preventDefault();
        var link = $(this).attr('url');
        $('#iframe-preview-nota').attr('src', link);
        $('#btn-open-pdf-tab').attr('href', link);
        $('#modal-preview-nota').modal('show');
    });

    $(document).on("click", "#btn-print-iframe", function (e) {
        e.preventDefault();
        var iframe = document.getElementById('iframe-preview-nota');
        if (iframe) {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } catch (err) {
                var tabUrl = $('#btn-open-pdf-tab').attr('href');
                if (tabUrl && tabUrl !== '#') {
                    window.open(tabUrl, '_blank');
                }
            }
        }
    });

    // Pintasan keyboard: Tekan P untuk cetak nota 80mm
    $(document).on("keydown", function (e) {
        if ((e.key === 'p' || e.key === 'P') && !$('input, textarea').is(':focus')) {
            if (!$('#modal-preview-nota').hasClass('in')) {
                e.preventDefault();
                $('#print-transaction').click();
            }
        }
    });

JS
);

?>

<style>
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
  --pin-secondary-pressed:#c8c8c1;
  --pin-canvas:          #ffffff;
  --pin-surface-soft:    #fbfbf9;
  --pin-surface-card:    #f6f6f3;
  --pin-success-pale:    #c7f0da;
  --pin-success-deep:    #103c25;
  --pin-error:           #9e0a0a;
  --pin-r-md:            16px;
  --pin-r-lg:            32px;
  --pin-r-full:          9999px;
  --pin-font: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

.pin-resume-page {
  background: var(--pin-surface-soft);
  font-family: var(--pin-font);
  color: var(--pin-body);
  padding: 24px;
}

.pin-resume-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  padding: 24px;
  max-width: 960px;
  margin: 0 auto;
}

.pin-resume-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}
.pin-resume-title {
  font-size: 22px;
  font-weight: 600;
  color: var(--pin-ink);
  line-height: 1.25;
  margin: 0 0 4px;
}
.pin-resume-sub {
  font-size: 14px;
  color: var(--pin-mute);
  margin: 0;
}
.pin-resume-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 32px;
  padding: 0 14px;
  background: var(--pin-success-pale);
  color: var(--pin-success-deep);
  border-radius: var(--pin-r-full);
  font-size: 12px;
  font-weight: 700;
  white-space: nowrap;
}

.pin-resume-table-wrap {
  background: var(--pin-surface-card);
  border-radius: var(--pin-r-md);
  overflow: hidden;
  margin-bottom: 20px;
}
.pin-resume-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.pin-resume-table thead th {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-ash);
  padding: 12px 16px;
  text-align: left;
  background: var(--pin-surface-card);
  border-bottom: 1px solid var(--pin-hairline);
}
.pin-resume-table thead th.num,
.pin-resume-table tbody td.num,
.pin-resume-table thead th.qty,
.pin-resume-table tbody td.qty { text-align: center; width: 48px; }
.pin-resume-table thead th.price,
.pin-resume-table tbody td.price,
.pin-resume-table thead th.subtotal,
.pin-resume-table tbody td.subtotal { text-align: right; white-space: nowrap; }

.pin-resume-table tbody tr {
  border-bottom: 1px solid var(--pin-hairline-soft);
  transition: background .12s;
}
.pin-resume-table tbody tr:last-child { border-bottom: none; }
.pin-resume-table tbody tr:hover { background: rgba(255,255,255,.6); }
.pin-resume-table tbody td {
  padding: 12px 16px;
  color: var(--pin-body);
  vertical-align: middle;
}
.pin-resume-table tbody td.name {
  font-weight: 600;
  color: var(--pin-ink);
}
.pin-resume-table tbody td.code {
  font-size: 12px;
  color: var(--pin-mute);
  font-family: monospace;
}

.pin-resume-summary {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 24px;
}
@media (max-width: 600px) {
  .pin-resume-summary { grid-template-columns: 1fr; }
}
.pin-summary-item {
  background: var(--pin-surface-card);
  border-radius: var(--pin-r-md);
  padding: 16px 18px;
}
.pin-summary-item.highlight {
  background: var(--pin-ink);
  color: #fff;
}
.pin-summary-label {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-mute);
  margin-bottom: 6px;
}
.pin-summary-item.highlight .pin-summary-label { color: rgba(255,255,255,.7); }
.pin-summary-value {
  font-size: 20px;
  font-weight: 700;
  color: var(--pin-ink);
  letter-spacing: -0.3px;
}
.pin-summary-item.highlight .pin-summary-value { color: #fff; }
.pin-summary-item.change .pin-summary-value { color: var(--pin-success-deep); }

.pin-resume-actions {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 12px;
}
@media (max-width: 600px) {
  .pin-resume-actions { grid-template-columns: 1fr; }
}

.pin-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  height: 44px;
  padding: 6px 20px;
  font-family: var(--pin-font);
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  border: none;
  border-radius: var(--pin-r-md);
  cursor: pointer;
  transition: background .15s;
  width: 100%;
}
.pin-btn-primary   { background: var(--pin-primary); color: #fff; }
.pin-btn-primary:hover,
.pin-btn-primary:focus { background: var(--pin-primary-pressed); outline: none; color: #fff; }
.pin-btn-secondary { background: var(--pin-secondary-bg); color: var(--pin-ink); }
.pin-btn-secondary:hover,
.pin-btn-secondary:focus { background: var(--pin-secondary-pressed); outline: none; color: var(--pin-ink); }
.pin-btn-danger    { background: transparent; color: var(--pin-error); border: 1px solid var(--pin-hairline); }
.pin-btn-danger:hover,
.pin-btn-danger:focus { background: #fde8eb; border-color: var(--pin-primary); color: var(--pin-primary); outline: none; }
</style>

<div class="pin-resume-page">
  <div class="pin-resume-card" id="data-transaksi">

    <!-- Header -->
    <div class="pin-resume-header">
      <div>
        <h1 class="pin-resume-title">Resume Transaksi</h1>
        <p class="pin-resume-sub">
          <?php if ($model): ?>
            <strong><?php echo htmlspecialchars($model->no_transaksi); ?></strong>
            &nbsp;&middot;&nbsp;
            <?php echo $model->tgl_bayar ? date('d/m/Y H:i', strtotime($model->tgl_bayar)) : '-'; ?>
          <?php endif; ?>
        </p>
      </div>
      <span class="pin-resume-badge">
        <i class="fa fa-check-circle"></i> Transaksi Berhasil
      </span>
    </div>

    <!-- Items table -->
    <div class="pin-resume-table-wrap">
      <table class="pin-resume-table">
        <thead>
          <tr>
            <th class="num">No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th class="price">Harga</th>
            <th class="qty">Qty</th>
            <th class="subtotal">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($model != null) {
            $no = 1;
            foreach ($model->details as $val) {
          ?>
            <tr>
              <td class="num"><?php echo $no; ?></td>
              <td class="code"><?php echo htmlspecialchars($val->kd_barang); ?></td>
              <td class="name"><?php echo htmlspecialchars($val->barang->nama_barang); ?></td>
              <td class="price"><?php echo Utility::rupiah($val->harga_satuan); ?></td>
              <td class="qty"><?php echo $val->qty; ?></td>
              <td class="subtotal"><?php echo Utility::rupiah($val->total_harga); ?></td>
            </tr>
          <?php
              $no++;
            }
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="pin-resume-summary" style="<?php echo (!empty($model->diskon) && $model->diskon > 0) ? 'grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));' : ''; ?>">
      <?php if (!empty($model->diskon) && $model->diskon > 0): ?>
      <div class="pin-summary-item">
        <div class="pin-summary-label">Subtotal</div>
        <div class="pin-summary-value"><?php echo Utility::rupiah($model->total + $model->diskon); ?></div>
      </div>
      <div class="pin-summary-item" style="background:#fff1f2;border:1px dashed #fca5a5;">
        <div class="pin-summary-label" style="color:#e60023;">Diskon</div>
        <div class="pin-summary-value" style="color:#e60023;">- <?php echo Utility::rupiah($model->diskon); ?></div>
      </div>
      <?php endif; ?>
      <div class="pin-summary-item highlight">
        <div class="pin-summary-label">Total</div>
        <div class="pin-summary-value"><?php echo Utility::rupiah($model->total); ?></div>
      </div>
      <div class="pin-summary-item">
        <div class="pin-summary-label">Total Bayar</div>
        <div class="pin-summary-value"><?php echo Utility::rupiah($model->jumlah_bayar); ?></div>
      </div>
      <div class="pin-summary-item change">
        <div class="pin-summary-label">Kembalian</div>
        <div class="pin-summary-value"><?php echo Utility::rupiah($model->jumlah_bayar - $model->total); ?></div>
      </div>
    </div>

    <!-- Actions -->
    <div class="pin-resume-actions">
      <button type="button" class="pin-btn pin-btn-primary" id="new-transaction"
              url="<?php echo Url::to(['site/index']); ?>">
        <i class="fa fa-plus"></i> Baru
      </button>
      <button type="button" class="pin-btn pin-btn-secondary" id="print-transaction"
              url="<?php echo Url::to(['transaksi/cetaknota', 'id' => $model->no_transaksi]); ?>"
              title="Cetak Nota Ukuran 80 mm (Tekan P)">
        <i class="fa fa-print"></i> Cetak Nota (80 mm)
      </button>
      <button type="button" class="pin-btn pin-btn-danger" id="cancel-transaction"
              url="<?php echo Url::to(['site/canceltransaction', 'id' => $id]); ?>">
        <i class="fa fa-times"></i> Batal
      </button>
    </div>

  </div>
</div>

<!-- Modal Preview Nota PDF Ukuran 80 mm -->
<div class="modal fade" id="modal-preview-nota" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 440px; margin: 25px auto;">
    <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 12px 36px rgba(0,0,0,.22);">
      <div class="modal-header" style="background: var(--pin-primary, #e60023); color: #fff; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between;">
        <h4 class="modal-title" style="font-weight: 700; font-size: 15px; margin: 0; display: flex; align-items: center; gap: 8px;">
          <i class="fa fa-print"></i> Cetak Nota Struk (Thermal 80 mm)
        </h4>
        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.9; font-size: 22px; font-weight: 300; border: none; background: transparent; cursor: pointer;">&times;</button>
      </div>
      <div class="modal-body" style="padding: 16px; background: #eef0f3;">
        <div style="background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #dcdfe4; box-shadow: 0 4px 16px rgba(0,0,0,.08); max-width: 360px; margin: 0 auto;">
          <iframe id="iframe-preview-nota" src="" style="width: 100%; height: 500px; border: none; display: block;"></iframe>
        </div>
      </div>
      <div class="modal-footer" style="background: #fff; border-top: 1px solid #e5e5e0; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;">
        <div style="display: flex; gap: 8px;">
          <button type="button" class="pin-btn pin-btn-primary" id="btn-print-iframe" style="height: 38px; padding: 0 16px; font-size: 13px;">
            <i class="fa fa-print"></i> Cetak
          </button>
          <a id="btn-open-pdf-tab" href="#" target="_blank" class="pin-btn pin-btn-secondary" style="height: 38px; padding: 0 14px; font-size: 13px; text-decoration: none;">
            <i class="fa fa-external-link"></i> Tab Baru
          </a>
        </div>
        <button type="button" class="pin-btn pin-btn-secondary" data-dismiss="modal" style="height: 38px; padding: 0 16px; font-size: 13px;">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

