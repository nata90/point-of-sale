<?php
use yii\helpers\Url;

/* ── DUMMY DATA — hapus & ganti query nyata saat production ── */
$dummy_kpi = [
    'penjualan_hari_ini' => 4875000,
    'transaksi_hari_ini' => 37,
    'item_terjual'       => 124,
    'rata_rata'          => 131757,
    'penjualan_kemarin'  => 3920000,
    'transaksi_kemarin'  => 29,
];
$dummy_transaksi = [
    ['no_transaksi'=>'TRX-20260530-037','waktu'=>'19:48','kasir'=>'Rina','items'=>5,'total'=>187500,'metode'=>'Tunai'],
    ['no_transaksi'=>'TRX-20260530-036','waktu'=>'19:31','kasir'=>'Budi','items'=>2,'total'=>64000,'metode'=>'QRIS'],
    ['no_transaksi'=>'TRX-20260530-035','waktu'=>'18:55','kasir'=>'Rina','items'=>8,'total'=>312000,'metode'=>'Tunai'],
    ['no_transaksi'=>'TRX-20260530-034','waktu'=>'18:22','kasir'=>'Ani','items'=>3,'total'=>95000,'metode'=>'Debit'],
    ['no_transaksi'=>'TRX-20260530-033','waktu'=>'17:44','kasir'=>'Budi','items'=>11,'total'=>478500,'metode'=>'QRIS'],
    ['no_transaksi'=>'TRX-20260530-032','waktu'=>'17:05','kasir'=>'Rina','items'=>1,'total'=>22000,'metode'=>'Tunai'],
    ['no_transaksi'=>'TRX-20260530-031','waktu'=>'16:30','kasir'=>'Ani','items'=>6,'total'=>215000,'metode'=>'Tunai'],
];
$dummy_stok_menipis = [
    ['kd_barang'=>'BRG-041','nama_barang'=>'Mie Goreng Indomie','stok'=>3,'min_stok'=>10,'satuan'=>'Pcs'],
    ['kd_barang'=>'BRG-017','nama_barang'=>'Teh Botol Sosro 350ml','stok'=>5,'min_stok'=>24,'satuan'=>'Btl'],
    ['kd_barang'=>'BRG-089','nama_barang'=>'Aqua Galon 19L','stok'=>2,'min_stok'=>5,'satuan'=>'Galon'],
    ['kd_barang'=>'BRG-023','nama_barang'=>'Sabun Lifebuoy 85gr','stok'=>7,'min_stok'=>15,'satuan'=>'Pcs'],
    ['kd_barang'=>'BRG-055','nama_barang'=>'Gula Pasir 1kg','stok'=>4,'min_stok'=>20,'satuan'=>'Kg'],
];
$this->registerJs('var url = "' . Url::to(['/site/grafikpenjualan']) . '";');
$this->registerJs('var daysago = "' . $days_ago . '";');
$this->registerJs('var daysnow = "' . $days_now . '";');
$this->registerJs('var url_search = "' . Url::to(['/site/searchgrafik']) . '";');
$this->registerJs('var ip_addr = "' . $setting->ip_address . '";');
$this->registerJsFile(Yii::$app->request->BaseUrl . '/js/numeral.min.js');
$this->registerJs(<<<JS
	var nama = 'INFO';
    var msg = 'Selamat datang';

    //var socket = io.connect('http://'+ip_addr+':3000');
    //socket.emit('notif',{name: nama, message: msg});
            
	
		$.ajax({
			type: 'get',
			url: url,
			dataType: 'json',
			success: function(v){
				$(function () {
					var ctx = document.getElementById('barChart').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'bar',
					    data: {
					        labels: v.label,
					        datasets: [{
					            label: 'GRAFIK DATA PENJUALAN (Rupiah)',
					            data: v.data,
					            backgroundColor: [
					                'rgba(255, 99, 132, 0.2)',
					                'rgba(54, 162, 235, 0.2)',
					                'rgba(255, 206, 86, 0.2)',
					                'rgba(75, 192, 192, 0.2)',
					                'rgba(153, 102, 255, 0.2)',
					                'rgba(255, 159, 64, 0.2)',
					                'rgba(72, 176, 69, 0.2)',
					                'rgba(176, 69, 137, 0.2)',
					                'rgba(64, 132, 191, 0.2)',
					                'rgba(169, 129, 213, 0.2)',
					                'rgba(129, 213, 132, 0.2)'
					            ],
					            borderColor: [
					                'rgba(255, 99, 132, 1)',
					                'rgba(54, 162, 235, 1)',
					                'rgba(255, 206, 86, 1)',
					                'rgba(75, 192, 192, 1)',
					                'rgba(153, 102, 255, 1)',
					                'rgba(255, 159, 64, 1)',
					                'rgba(72, 176, 69, 1)',
					                'rgba(176, 69, 137, 1)',
					                'rgba(64, 132, 191, 1)',
					                'rgba(169, 129, 213, 1)',
					                'rgba(129, 213, 132, 1)'
					            ],
					            borderWidth: 1
					        }]
					    },
					    options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero: true,
					                    callback: function (value) {
				                            return numeral(value).format('0,0')
				                        }
					                }
					            }]
					        },
					        tooltips: {
					            callbacks: {
					                label: function(tooltipItem, data) {
					                     return numeral(tooltipItem.yLabel).format('0,0');
					                }
					            }
					        }
					    }
					});
				})
			}
		});


	

	//Date range picker
    $('#reservation').daterangepicker({
    	"startDate": daysago,
    	"endDate": daysnow
    })

    $(document).on("click", "#search-grafik", function () {
    	var daterange = $('#reservation').val();
    	$('#barChart').remove();
    	$('.chart').append('<canvas id="barChart" style="height:230px"><canvas>')

    	$.ajax({
			type: 'get',
			url: url_search,
			data: {'daterange':daterange},
			dataType: 'json',
			success: function(v){
				$('#best-selling').html(v.html);
				$(function () {
					var ctx = document.getElementById('barChart').getContext('2d');
					var myChart = new Chart(ctx, {
					    type: 'bar',
					    data: {
					        labels: v.label,
					        datasets: [{
					            label: 'GRAFIK DATA PENJUALAN (Rupiah)',
					            data: v.data,
					            backgroundColor: v.rgba,
					            borderColor: v.rgba,
					            borderWidth: 1
					        }]
					    },
					    options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero: true,
					                    callback: function (value) {
				                            return numeral(value).format('0,0')
				                        }
					                }
					            }]
					        },
					        tooltips: {
					            callbacks: {
					                label: function(tooltipItem, data) {
					                     return numeral(tooltipItem.yLabel).format('0,0');
					                }
					            }
					        }
					    }
					});
				})
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
    --pin-primary:          #e60023;
    --pin-primary-pressed:  #cc001f;
    --pin-ink:              #000000;
    --pin-body:             #33332e;
    --pin-mute:             #62625b;
    --pin-ash:              #91918c;
    --pin-stone:            #c8c8c1;
    --pin-hairline:         #dadad3;
    --pin-hairline-soft:    #e5e5e0;
    --pin-secondary-bg:     #e5e5e0;
    --pin-secondary-pressed:#c8c8c1;
    --pin-canvas:           #ffffff;
    --pin-surface-soft:     #fbfbf9;
    --pin-surface-card:     #f6f6f3;
    --pin-success-pale:     #c7f0da;
    --pin-success-deep:     #103c25;
    --pin-r-md:             16px;
    --pin-r-lg:             32px;
    --pin-r-full:           9999px;
    --pin-font:             'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  }

  /* ── Dashboard wrapper ── */
  .pin-dashboard {
    background: var(--pin-surface-soft);
    font-family: var(--pin-font);
    color: var(--pin-body);
    padding: 24px;
  }

  /* ── Main card ── */
  .pin-card-main {
    background: var(--pin-canvas);
    border-radius: var(--pin-r-md);
    padding: 24px;
    margin-bottom: 24px;
  }

  /* ── Filter row ── */
  .pin-filter-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }

  .pin-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--pin-surface-card);
    border-radius: var(--pin-r-full);
    padding: 0 16px;
    height: 44px;
    min-width: 260px;
    border: 1px solid transparent;
    transition: border-color .15s, background .15s;
  }
  .pin-input-wrap:focus-within {
    background: var(--pin-canvas);
    border-color: var(--pin-ash);
  }
  .pin-input-wrap i {
    color: var(--pin-ash);
    margin-right: 8px;
    font-size: 13px;
    flex-shrink: 0;
  }
  .pin-input-wrap input {
    border: none;
    outline: none;
    background: transparent;
    font-family: var(--pin-font);
    font-size: 14px;
    color: var(--pin-ink);
    width: 100%;
    height: 100%;
  }
  .pin-input-wrap input::placeholder { color: var(--pin-ash); }

  /* ── Primary button (Pinterest Red) ── */
  .pin-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    padding: 6px 20px;
    background: var(--pin-primary);
    color: #fff;
    font-family: var(--pin-font);
    font-size: 14px;
    font-weight: 700;
    line-height: 1;
    border: none;
    border-radius: var(--pin-r-md);
    cursor: pointer;
    transition: background .15s;
    white-space: nowrap;
  }
  .pin-btn-primary:hover,
  .pin-btn-primary:focus  { background: var(--pin-primary-pressed); color: #fff; outline: none; }
  .pin-btn-primary:active { background: var(--pin-primary-pressed); }

  /* ── Chart section ── */
  .pin-chart-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--pin-ink);
    letter-spacing: -0.3px;
    margin-bottom: 16px;
  }
  .pin-chart-wrap {
    background: var(--pin-surface-card);
    border-radius: var(--pin-r-md);
    padding: 16px;
    margin-bottom: 24px;
  }

  /* ── Divider ── */
  .pin-divider {
    border: none;
    border-top: 1px solid var(--pin-hairline);
    margin: 24px 0;
  }

  /* ── Best-selling section ── */
  .pin-section-label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    color: var(--pin-mute);
    margin-bottom: 12px;
  }

  .pin-best-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .pin-best-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: var(--pin-surface-card);
    border-radius: var(--pin-r-md);
    padding: 12px 16px;
    transition: background .15s;
  }
  .pin-best-item:hover { background: var(--pin-hairline-soft); }

  .pin-best-rank {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    border-radius: var(--pin-r-full);
    background: var(--pin-secondary-bg);
    color: var(--pin-mute);
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .pin-best-rank.top { background: var(--pin-primary); color: #fff; }

  .pin-best-name {
    flex: 1;
    font-size: 14px;
    font-weight: 400;
    color: var(--pin-body);
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }
  .pin-best-name strong {
    font-weight: 600;
    color: var(--pin-ink);
    margin-right: 4px;
  }

  .pin-best-badge {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    height: 28px;
    padding: 0 12px;
    background: var(--pin-success-pale);
    color: var(--pin-success-deep);
    border-radius: var(--pin-r-full);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
  }

  .pin-empty {
    padding: 32px 16px;
    text-align: center;
    color: var(--pin-ash);
    font-size: 14px;
  }

  /* ── KPI cards ── */
  .pin-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 24px;
  }
  @media (max-width: 1024px) { .pin-kpi-grid { grid-template-columns: repeat(2,1fr); } }
  @media (max-width: 480px)  { .pin-kpi-grid { grid-template-columns: 1fr; } }

  .pin-kpi-card {
    background: var(--pin-canvas);
    border-radius: var(--pin-r-md);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
    overflow: hidden;
  }
  .pin-kpi-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--pin-hairline-soft);
    border-radius: var(--pin-r-md) var(--pin-r-md) 0 0;
  }
  .pin-kpi-card.accent::before { background: var(--pin-primary); }

  .pin-kpi-icon {
    width: 36px; height: 36px;
    border-radius: var(--pin-r-full);
    background: var(--pin-surface-card);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    color: var(--pin-mute);
    margin-bottom: 4px;
  }
  .pin-kpi-card.accent .pin-kpi-icon { background: #fde8eb; color: var(--pin-primary); }

  .pin-kpi-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--pin-mute);
    text-transform: uppercase;
    letter-spacing: .5px;
  }
  .pin-kpi-value {
    font-size: 22px;
    font-weight: 700;
    color: var(--pin-ink);
    letter-spacing: -0.5px;
    line-height: 1.1;
  }
  .pin-kpi-card.accent .pin-kpi-value { color: var(--pin-primary); }

  .pin-kpi-diff {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: var(--pin-r-full);
  }
  .pin-kpi-diff.up   { background: var(--pin-success-pale); color: var(--pin-success-deep); }
  .pin-kpi-diff.down { background: #fde8eb; color: var(--pin-error-deep, #cc001f); }
  .pin-kpi-diff.neutral { background: var(--pin-surface-card); color: var(--pin-mute); }

  /* ── 2-column grid ── */
  .pin-two-col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 768px) { .pin-two-col { grid-template-columns: 1fr; } }

  /* ── Section card ── */
  .pin-section-card {
    background: var(--pin-canvas);
    border-radius: var(--pin-r-md);
    padding: 20px;
  }
  .pin-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }
  .pin-section-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--pin-ink);
  }
  .pin-section-link {
    font-size: 13px;
    font-weight: 600;
    color: var(--pin-primary);
    text-decoration: none;
    border-radius: var(--pin-r-full);
    padding: 4px 10px;
    transition: background .15s;
  }
  .pin-section-link:hover { background: #fde8eb; color: var(--pin-primary); }

  /* ── Recent transactions table ── */
  .pin-trx-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  .pin-trx-table thead th {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--pin-ash);
    padding: 0 8px 10px;
    text-align: left;
    border-bottom: 1px solid var(--pin-hairline);
  }
  .pin-trx-table tbody tr {
    border-bottom: 1px solid var(--pin-hairline-soft);
    transition: background .12s;
  }
  .pin-trx-table tbody tr:last-child { border-bottom: none; }
  .pin-trx-table tbody tr:hover { background: var(--pin-surface-soft); }
  .pin-trx-table tbody td {
    padding: 10px 8px;
    color: var(--pin-body);
    vertical-align: middle;
  }
  .pin-trx-no {
    font-weight: 600;
    color: var(--pin-ink);
    font-size: 12px;
  }
  .pin-trx-time {
    color: var(--pin-ash);
    font-size: 12px;
  }
  .pin-trx-total {
    font-weight: 700;
    color: var(--pin-ink);
    text-align: right;
  }
  .pin-metode-badge {
    display: inline-flex;
    align-items: center;
    height: 22px;
    padding: 0 10px;
    border-radius: var(--pin-r-full);
    font-size: 11px;
    font-weight: 700;
    background: var(--pin-surface-card);
    color: var(--pin-mute);
  }
  .pin-metode-badge.qris  { background: #ede9fe; color: #5b21b6; }
  .pin-metode-badge.debit { background: #dbeafe; color: #1d4ed8; }

  /* ── Low stock ── */
  .pin-stock-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .pin-stock-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--pin-surface-card);
    border-radius: var(--pin-r-md);
    padding: 12px 14px;
  }
  .pin-stock-icon {
    width: 32px; height: 32px;
    border-radius: var(--pin-r-full);
    background: #fde8eb;
    color: var(--pin-primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
  }
  .pin-stock-info { flex: 1; min-width: 0; }
  .pin-stock-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--pin-ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .pin-stock-code {
    font-size: 11px;
    color: var(--pin-ash);
    margin-top: 1px;
  }
  .pin-stock-right { text-align: right; flex-shrink: 0; }
  .pin-stock-qty {
    font-size: 15px;
    font-weight: 700;
    color: var(--pin-primary);
  }
  .pin-stock-min {
    font-size: 11px;
    color: var(--pin-ash);
    margin-top: 1px;
  }
  .pin-stock-bar-wrap {
    width: 100%;
    height: 4px;
    background: var(--pin-hairline-soft);
    border-radius: var(--pin-r-full);
    margin-top: 6px;
    overflow: hidden;
  }
  .pin-stock-bar {
    height: 100%;
    border-radius: var(--pin-r-full);
    background: var(--pin-primary);
  }
</style>

<?php
  $selisih_penjualan = $dummy_kpi['penjualan_hari_ini'] - $dummy_kpi['penjualan_kemarin'];
  $pct_penjualan = $dummy_kpi['penjualan_kemarin'] > 0
    ? round(($selisih_penjualan / $dummy_kpi['penjualan_kemarin']) * 100, 1) : 0;
  $selisih_trx = $dummy_kpi['transaksi_hari_ini'] - $dummy_kpi['transaksi_kemarin'];
  $pct_trx = $dummy_kpi['transaksi_kemarin'] > 0
    ? round(($selisih_trx / $dummy_kpi['transaksi_kemarin']) * 100, 1) : 0;
?>

<div class="pin-dashboard">

  <!-- ══ KPI CARDS ══ -->
  <div class="pin-kpi-grid">

    <div class="pin-kpi-card accent">
      <div class="pin-kpi-icon"><i class="fa fa-money"></i></div>
      <span class="pin-kpi-label">Penjualan Hari Ini</span>
      <span class="pin-kpi-value">Rp <?php echo number_format($dummy_kpi['penjualan_hari_ini'],0,',','.'); ?></span>
      <span class="pin-kpi-diff <?php echo $pct_penjualan >= 0 ? 'up' : 'down'; ?>">
        <i class="fa fa-arrow-<?php echo $pct_penjualan >= 0 ? 'up' : 'down'; ?>"></i>
        <?php echo abs($pct_penjualan); ?>% vs kemarin
      </span>
    </div>

    <div class="pin-kpi-card">
      <div class="pin-kpi-icon"><i class="fa fa-shopping-cart"></i></div>
      <span class="pin-kpi-label">Transaksi</span>
      <span class="pin-kpi-value"><?php echo number_format($dummy_kpi['transaksi_hari_ini']); ?></span>
      <span class="pin-kpi-diff <?php echo $pct_trx >= 0 ? 'up' : 'down'; ?>">
        <i class="fa fa-arrow-<?php echo $pct_trx >= 0 ? 'up' : 'down'; ?>"></i>
        <?php echo abs($pct_trx); ?>% vs kemarin
      </span>
    </div>

    <div class="pin-kpi-card">
      <div class="pin-kpi-icon"><i class="fa fa-cubes"></i></div>
      <span class="pin-kpi-label">Item Terjual</span>
      <span class="pin-kpi-value"><?php echo number_format($dummy_kpi['item_terjual']); ?> pcs</span>
      <span class="pin-kpi-diff neutral">Hari ini</span>
    </div>

    <div class="pin-kpi-card">
      <div class="pin-kpi-icon"><i class="fa fa-bar-chart"></i></div>
      <span class="pin-kpi-label">Rata-rata / Transaksi</span>
      <span class="pin-kpi-value">Rp <?php echo number_format($dummy_kpi['rata_rata'],0,',','.'); ?></span>
      <span class="pin-kpi-diff neutral">Per struk</span>
    </div>

  </div><!-- /.pin-kpi-grid -->

  <!-- ══ TRANSAKSI TERBARU + STOK MENIPIS ══ -->
  <div class="pin-two-col">

    <!-- Transaksi terbaru -->
    <div class="pin-section-card">
      <div class="pin-section-head">
        <span class="pin-section-title"><i class="fa fa-clock-o" style="margin-right:6px;color:var(--pin-ash);"></i>Transaksi Terbaru</span>
        <a href="#" class="pin-section-link">Lihat semua</a>
      </div>
      <table class="pin-trx-table">
        <thead>
          <tr>
            <th>No. Transaksi</th>
            <th>Kasir</th>
            <th style="text-align:center;">Item</th>
            <th>Metode</th>
            <th style="text-align:right;">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dummy_transaksi as $trx): ?>
          <tr>
            <td>
              <span class="pin-trx-no"><?php echo htmlspecialchars($trx['no_transaksi']); ?></span><br>
              <span class="pin-trx-time"><i class="fa fa-clock-o"></i> <?php echo $trx['waktu']; ?></span>
            </td>
            <td><?php echo htmlspecialchars($trx['kasir']); ?></td>
            <td style="text-align:center;"><?php echo $trx['items']; ?></td>
            <td>
              <?php
                $m = strtolower($trx['metode']);
                $cls = $m === 'qris' ? 'qris' : ($m === 'debit' ? 'debit' : '');
              ?>
              <span class="pin-metode-badge <?php echo $cls; ?>"><?php echo $trx['metode']; ?></span>
            </td>
            <td class="pin-trx-total">Rp <?php echo number_format($trx['total'],0,',','.'); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Stok menipis -->
    <div class="pin-section-card">
      <div class="pin-section-head">
        <span class="pin-section-title"><i class="fa fa-exclamation-triangle" style="margin-right:6px;color:var(--pin-primary);"></i>Stok Menipis</span>
        <a href="#" class="pin-section-link">Kelola stok</a>
      </div>
      <ul class="pin-stock-list">
        <?php foreach ($dummy_stok_menipis as $stok):
          $pct_stok = $stok['min_stok'] > 0 ? min(100, round(($stok['stok'] / $stok['min_stok']) * 100)) : 0;
        ?>
        <li class="pin-stock-item">
          <div class="pin-stock-icon"><i class="fa fa-warning"></i></div>
          <div class="pin-stock-info">
            <div class="pin-stock-name"><?php echo htmlspecialchars($stok['nama_barang']); ?></div>
            <div class="pin-stock-code"><?php echo $stok['kd_barang']; ?></div>
            <div class="pin-stock-bar-wrap">
              <div class="pin-stock-bar" style="width:<?php echo $pct_stok; ?>%;"></div>
            </div>
          </div>
          <div class="pin-stock-right">
            <div class="pin-stock-qty"><?php echo $stok['stok']; ?> <?php echo $stok['satuan']; ?></div>
            <div class="pin-stock-min">min <?php echo $stok['min_stok']; ?></div>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div><!-- /.pin-two-col -->


  <!-- ══ GRAFIK ══ -->
  <div class="pin-card-main">
    <div class="pin-filter-row">
      <div class="pin-input-wrap">
        <i class="fa fa-calendar"></i>
        <input type="text" id="reservation" placeholder="Pilih rentang tanggal…">
      </div>
      <button class="pin-btn-primary" id="search-grafik">
        <i class="fa fa-search" style="margin-right:6px;font-size:12px;"></i>Cari
      </button>
    </div>
    <p class="pin-chart-title">Grafik Penjualan</p>
    <div class="pin-chart-wrap chart">
      <canvas id="barChart" style="height:230px;"></canvas>
    </div>
  </div>

  <!-- ══ 10 BARANG TERLARIS ══ -->
  <div class="pin-section-card">
    <div class="pin-section-head">
      <span class="pin-section-title"><i class="fa fa-trophy" style="margin-right:6px;color:var(--pin-primary);"></i>10 Barang Terlaris</span>
      <span style="font-size:12px;color:var(--pin-ash);"><?php echo $days_ago; ?> – <?php echo $days_now; ?></span>
    </div>
    <div id="best-selling">
      <ul class="pin-best-list">
        <?php if ($popular != null):
          $rank = 0;
          foreach ($popular as $row):
            $rank++;
        ?>
          <li class="pin-best-item">
            <span class="pin-best-rank <?php echo $rank <= 3 ? 'top' : ''; ?>"><?php echo $rank; ?></span>
            <span class="pin-best-name">
              <strong><?php echo htmlspecialchars($row['kd_barang']); ?></strong><?php echo htmlspecialchars($row['nama_barang']); ?>
            </span>
            <span class="pin-best-badge"><?php echo $row['total']; ?> item</span>
          </li>
        <?php endforeach;
        else: ?>
          <li class="pin-empty">
            <i class="fa fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
            Belum ada data penjualan di periode ini
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

</div><!-- /.pin-dashboard -->
