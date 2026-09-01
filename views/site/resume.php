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

	$(document).on("click", "#print-transaction", function () {
        var link = $(this).attr('url');
        $.ajax({
            type: 'get',
            url: link,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
            },
        });
    });

JS
);

?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

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
    <div class="pin-resume-summary">
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
              url="<?php echo Url::to(['transaksi/cetaknota', 'id' => $model->no_transaksi]); ?>">
        <i class="fa fa-print"></i> Print
      </button>
      <button type="button" class="pin-btn pin-btn-danger" id="cancel-transaction"
              url="<?php echo Url::to(['site/canceltransaction', 'id' => $id]); ?>">
        <i class="fa fa-times"></i> Batal
      </button>
    </div>

  </div>
</div>

