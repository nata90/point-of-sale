<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
use app\models\HdTransaksi;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kelola Penjualan');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(<<<JS
    $(document).on("click", ".cetak-nota", function (e) {
        e.preventDefault();
        var link = $(this).attr('link');
        window.open(link, '_blank');
    });
JS
);

// Hitung ringkasan transaksi
$totalNominal = 0;
$totalDiskon = 0;
$totalBayar = 0;
$totalCount = $dataProvider->getTotalCount();
if (!empty($dataProvider->models)) {
    foreach ($dataProvider->models as $m) {
        $totalNominal += (float) ($m->total ?? 0);
        $totalDiskon += (float) ($m->diskon ?? 0);
        $totalBayar += (float) ($m->jumlah_bayar ?? 0);
    }
}
?>

<div class="pin-penjualan-page">

  <!-- KPI Stats Summary Row -->
  <div class="pin-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
    <div class="pin-stat-card">
      <div class="pin-stat-icon pin-stat-icon-red">
        <i class="fa fa-shopping-bag"></i>
      </div>
      <div>
        <div class="pin-stat-label">Total Transaksi</div>
        <div class="pin-stat-val"><?= number_format($totalCount, 0, ',', '.') ?></div>
      </div>
    </div>

    <div class="pin-stat-card">
      <div class="pin-stat-icon pin-stat-icon-green">
        <i class="fa fa-money"></i>
      </div>
      <div>
        <div class="pin-stat-label">Total Nilai Penjualan</div>
        <div class="pin-stat-val"><?= Utility::rupiah($totalNominal) ?></div>
      </div>
    </div>

    <div class="pin-stat-card">
      <div class="pin-stat-icon" style="background:#fee2e2;color:#e60023;">
        <i class="fa fa-tag"></i>
      </div>
      <div>
        <div class="pin-stat-label">Total Diskon Diberikan</div>
        <div class="pin-stat-val"><?= Utility::rupiah($totalDiskon) ?></div>
      </div>
    </div>

    <div class="pin-stat-card">
      <div class="pin-stat-icon pin-stat-icon-yellow">
        <i class="fa fa-credit-card"></i>
      </div>
      <div>
        <div class="pin-stat-label">Total Pembayaran Masuk</div>
        <div class="pin-stat-val"><?= Utility::rupiah($totalBayar) ?></div>
      </div>
    </div>
  </div>

  <!-- Main Card -->
  <div class="pin-main-card">
    <div class="pin-card-header">
      <div class="pin-header-left">
        <span class="pin-header-icon"><i class="fa fa-list-alt"></i></span>
        <h1 class="pin-header-title"><?= Html::encode($this->title) ?></h1>
      </div>
      <div>
        <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Transaksi Baru'), ['/site/index'], [
            'class' => 'pin-btn-header',
        ]) ?>
      </div>
    </div>

    <div class="pin-card-body">
      <?php Pjax::begin([
          'id' => 'grid-penjualan',
          'timeout' => false,
          'enablePushState' => false,
          'clientOptions' => ['method' => 'GET'],
      ]); ?>

      <?= GridView::widget([
          'dataProvider' => $dataProvider,
          'filterModel' => false,
          'tableOptions' => ['class' => 'table'],
          'emptyText' => '<div class="pin-empty-state"><div class="pin-empty-icon"><i class="fa fa-inbox"></i></div><div class="pin-empty-title">Belum Ada Transaksi</div><div>Tidak ada riwayat transaksi penjualan yang ditemukan.</div></div>',
          'columns' => [
              [
                  'class' => 'yii\grid\SerialColumn',
                  'header' => '#',
                  'headerOptions' => ['style' => 'width: 48px; text-align: center;'],
                  'contentOptions' => ['style' => 'text-align: center; font-weight: 600; color: var(--pin-ash);'],
              ],
              [
                  'label' => 'Aksi Nota',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width: 130px; text-align: center;'],
                  'contentOptions' => ['style' => 'text-align: center; white-space: nowrap;'],
                  'value' => function($model) {
                      return '<button type="button" class="pin-btn-print cetak-nota" link="' . Url::to(['transaksi/cetaknota', 'id' => $model->no_transaksi]) . '"><i class="fa fa-print"></i> Cetak Nota</button>';
                  },
              ],
              [
                  'attribute' => 'no_transaksi',
                  'label' => 'No Transaksi',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width: 170px;'],
                  'value' => function($model) {
                      return '<span class="pin-trx-badge"><i class="fa fa-barcode"></i> ' . Html::encode($model->no_transaksi) . '</span>';
                  },
              ],
              [
                  'label' => 'Tanggal Bayar',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width: 140px;'],
                  'value' => function($model) {
                      return '<span class="pin-date"><i class="fa fa-calendar-o"></i> ' . date('d-m-Y', strtotime($model->tgl_bayar)) . '</span>';
                  },
              ],
              [
                  'label' => 'Item Terjual',
                  'format' => 'raw',
                  'value' => function($model) {
                      $items = HdTransaksi::getListObat($model->no_transaksi);
                      return '<div class="pin-items-text">' . Html::encode($items ?: '(Tidak ada item)') . '</div>';
                  },
              ],
              [
                  'attribute' => 'diskon',
                  'label' => 'Diskon',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'text-align: right; width: 120px;'],
                  'contentOptions' => ['style' => 'text-align: right;'],
                  'value' => function($model) {
                      if (!empty($model->diskon) && $model->diskon > 0) {
                          return '<span style="font-weight:700;color:#e60023;background:#fff1f2;padding:3px 10px;border-radius:9999px;font-size:12px;border:1px dashed #fca5a5;white-space:nowrap;">- ' . Utility::rupiah($model->diskon) . '</span>';
                      }
                      return '<span style="color:#91918c;font-size:12px;">Rp 0</span>';
                  },
              ],
              [
                  'label' => 'Total Belanja',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'text-align: right; width: 140px;'],
                  'contentOptions' => ['style' => 'text-align: right;'],
                  'value' => function($model) {
                      return '<span class="pin-price-total">' . Utility::rupiah($model->total) . '</span>';
                  },
              ],
              [
                  'label' => 'Jumlah Bayar',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'text-align: right; width: 140px;'],
                  'contentOptions' => ['style' => 'text-align: right;'],
                  'value' => function($model) {
                      return '<span class="pin-price-paid">' . Utility::rupiah($model->jumlah_bayar) . '</span>';
                  },
              ],
              [
                  'class' => 'yii\grid\ActionColumn',
                  'template' => '{delete}',
                  'header' => 'Hapus',
                  'headerOptions' => ['style' => 'width: 60px; text-align: center;'],
                  'contentOptions' => ['style' => 'text-align: center;'],
                  'buttons' => [
                      'delete' => function ($url, $model) {
                          return Html::a('<i class="fa fa-trash-o"></i>', $url, [
                              'class' => 'pin-action-danger',
                              'title' => Yii::t('app', 'Hapus Transaksi'),
                              'data' => [
                                  'confirm' => Yii::t('app', 'Yakin ingin menghapus transaksi {no}? Stok barang akan dikembalikan ke inventaris.', ['no' => $model->no_transaksi]),
                                  'method' => 'post',
                              ],
                              'data-pjax' => '0',
                          ]);
                      },
                  ],
              ],
          ],
      ]); ?>

      <?php Pjax::end(); ?>
    </div>
  </div>

</div>