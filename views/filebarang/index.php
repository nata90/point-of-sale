<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Barang');
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.pin-barang-page {
  --pin-primary:          #e60023;
  --pin-primary-pressed:  #cc001f;
  --pin-ink:              #000000;
  --pin-body:             #33332e;
  --pin-mute:             #62625b;
  --pin-ash:              #91918c;
  --pin-hairline:         #dadad3;
  --pin-hairline-soft:    #e5e5e0;
  --pin-canvas:           #ffffff;
  --pin-surface-soft:     #fbfbf9;
  --pin-surface-card:     #f6f6f3;
  --pin-secondary-bg:     #e5e5e0;
  --pin-success-pale:     #c7f0da;
  --pin-success-deep:     #103c25;
  --pin-r-md:             16px;
  --pin-r-full:           9999px;
  --pin-font: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  font-family: var(--pin-font);
  color: var(--pin-body);
  padding: 24px;
  background: var(--pin-surface-soft);
  margin: -15px -15px 0;
  min-height: calc(100vh - 100px);
}

.pin-barang-page .pin-barang-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  overflow: hidden;
  border: 1px solid var(--pin-hairline-soft);
  box-shadow: 0 2px 12px rgba(0,0,0,.05);
}

.pin-barang-page .pin-barang-header {
  background: var(--pin-primary);
  color: #fff;
  padding: 16px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
}
.pin-barang-page .pin-barang-header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}
.pin-barang-page .pin-barang-header-icon {
  width: 32px;
  height: 32px;
  border-radius: var(--pin-r-full);
  background: rgba(255,255,255,.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
}
.pin-barang-page .pin-barang-header-title {
  font-size: 16px;
  font-weight: 700;
  letter-spacing: .3px;
  text-transform: uppercase;
  margin: 0;
  line-height: 1.2;
}

.pin-barang-page .pin-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  height: 40px;
  padding: 6px 18px;
  font-family: var(--pin-font);
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  border: none;
  border-radius: var(--pin-r-md);
  cursor: pointer;
  text-decoration: none;
  transition: background .15s;
  white-space: nowrap;
}
.pin-barang-page .pin-btn-primary {
  background: #fff;
  color: var(--pin-primary);
}
.pin-barang-page .pin-btn-primary:hover,
.pin-barang-page .pin-btn-primary:focus {
  background: #fde8eb;
  color: var(--pin-primary);
  text-decoration: none;
  outline: none;
}

.pin-barang-page .pin-barang-body {
  padding: 20px 24px 24px;
}

/* Tabs → filter chips */
.pin-barang-page .nav-tabs-custom {
  margin-bottom: 16px;
  box-shadow: none;
}
.pin-barang-page .nav-tabs {
  border: none;
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.pin-barang-page .nav-tabs > li {
  float: none;
  margin: 0;
}
.pin-barang-page .nav-tabs > li > a {
  border: none !important;
  border-radius: var(--pin-r-full) !important;
  padding: 8px 16px !important;
  font-size: 14px;
  font-weight: 700;
  color: var(--pin-ink) !important;
  background: var(--pin-surface-card) !important;
  margin: 0;
  line-height: 1;
}
.pin-barang-page .nav-tabs > li.active > a,
.pin-barang-page .nav-tabs > li.active > a:hover,
.pin-barang-page .nav-tabs > li.active > a:focus {
  background: var(--pin-ink) !important;
  color: #fff !important;
}

/* GridView */
.pin-barang-page .grid-view {
  overflow-x: auto;
}
.pin-barang-page .grid-view .summary {
  font-size: 13px;
  color: var(--pin-mute);
  margin-bottom: 12px;
}
.pin-barang-page .grid-view table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  background: var(--pin-canvas);
}
.pin-barang-page .grid-view thead th {
  background: var(--pin-surface-card);
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-ash);
  padding: 12px 14px;
  border-bottom: 1px solid var(--pin-hairline);
  border-top: none;
  white-space: nowrap;
}
.pin-barang-page .grid-view thead tr.filters td {
  background: var(--pin-surface-soft);
  padding: 8px 10px;
  border-bottom: 1px solid var(--pin-hairline);
}
.pin-barang-page .grid-view thead tr.filters input,
.pin-barang-page .grid-view thead tr.filters select {
  width: 100%;
  height: 36px;
  padding: 6px 12px;
  border: 1px solid var(--pin-hairline);
  border-radius: var(--pin-r-md);
  font-family: var(--pin-font);
  font-size: 13px;
  color: var(--pin-ink);
  background: var(--pin-canvas);
  outline: none;
  box-sizing: border-box;
}
.pin-barang-page .grid-view thead tr.filters input:focus,
.pin-barang-page .grid-view thead tr.filters select:focus {
  border-color: var(--pin-ink);
  box-shadow: 0 0 0 2px rgba(67,94,229,.15);
}
.pin-barang-page .grid-view tbody td {
  padding: 12px 14px;
  border-bottom: 1px solid var(--pin-hairline-soft);
  color: var(--pin-body);
  vertical-align: middle;
}
.pin-barang-page .grid-view tbody tr:hover td {
  background: var(--pin-surface-soft);
}
.pin-barang-page .grid-view tbody tr:last-child td {
  border-bottom: none;
}
.pin-barang-page .grid-view .pin-code {
  font-size: 12px;
  font-weight: 600;
  color: var(--pin-mute);
  font-family: monospace;
}
.pin-barang-page .grid-view .pin-name {
  font-weight: 600;
  color: var(--pin-ink);
}
.pin-barang-page .grid-view .pin-stok {
  display: inline-flex;
  align-items: center;
  height: 26px;
  padding: 0 10px;
  border-radius: var(--pin-r-full);
  font-size: 12px;
  font-weight: 700;
  background: var(--pin-success-pale);
  color: var(--pin-success-deep);
}
.pin-barang-page .grid-view .pin-stok.low {
  background: #fde8eb;
  color: var(--pin-primary);
}
.pin-barang-page .grid-view .pin-price {
  font-weight: 600;
  color: var(--pin-ink);
  white-space: nowrap;
}

/* Action column */
.pin-barang-page .grid-view a.pin-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: var(--pin-r-full);
  background: var(--pin-surface-card);
  color: var(--pin-ink);
  text-decoration: none;
  transition: background .12s;
}
.pin-barang-page .grid-view a.pin-action:hover {
  background: var(--pin-secondary-bg);
  color: var(--pin-ink);
  text-decoration: none;
}

/* Pagination */
.pin-barang-page .pagination {
  margin: 16px 0 0;
  display: flex;
  gap: 4px;
  flex-wrap: wrap;
}
.pin-barang-page .pagination > li > a,
.pin-barang-page .pagination > li > span {
  border: none;
  border-radius: var(--pin-r-md);
  padding: 8px 14px;
  font-size: 13px;
  font-weight: 600;
  color: var(--pin-body);
  background: var(--pin-surface-card);
  margin: 0;
}
.pin-barang-page .pagination > .active > a,
.pin-barang-page .pagination > .active > span {
  background: var(--pin-ink);
  color: #fff;
}
.pin-barang-page .pagination > li > a:hover {
  background: var(--pin-secondary-bg);
  color: var(--pin-ink);
}
</style>

<div class="pin-barang-page">
  <div class="pin-barang-card">

    <div class="pin-barang-header">
      <div class="pin-barang-header-left">
        <span class="pin-barang-header-icon"><i class="fa fa-folder-open"></i></span>
        <h1 class="pin-barang-header-title">Kelola Barang</h1>
      </div>
      <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Tambah Barang'), ['create'], [
          'class' => 'pin-btn pin-btn-primary',
      ]) ?>
    </div>

    <div class="pin-barang-body">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Semua</a></li>
          <?php /* <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Stok Habis</a></li>
          <li><a href="#tab_3" data-toggle="tab" aria-expanded="true">Barang ED</a></li>
          <li><a href="#tab_4" data-toggle="tab" aria-expanded="true">Hampir ED</a></li> */ ?>
        </ul>
      </div>

      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">

          <?php Pjax::begin([
              'id' => 'grid-barang',
              'timeout' => false,
              'enablePushState' => false,
              'clientOptions' => ['method' => 'GET'],
          ]); ?>

          <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'tableOptions' => ['class' => 'table'],
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],

                  [
                      'attribute' => 'kd_barang',
                      'format' => 'raw',
                      'contentOptions' => ['style' => 'width:10%; white-space: normal'],
                      'value' => function ($model) {
                          return Html::tag('span', Html::encode($model->kd_barang), ['class' => 'pin-code']);
                      },
                  ],
                  [
                      'attribute' => 'nama_barang',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return Html::tag('span', Html::encode($model->nama_barang), ['class' => 'pin-name']);
                      },
                  ],
                  [
                      'attribute' => 'lokasi',
                      'format' => 'raw',
                      'contentOptions' => ['style' => 'width:10%; white-space: normal'],
                      'value' => function ($model) {
                          return Html::encode($model->lokasi);
                      },
                  ],
                  [
                      'label' => 'Stok Total',
                      'format' => 'raw',
                      'value' => function ($model) {
                          $min = (int) $model->min_stok > 0 ? (int) $model->min_stok : 5;
                          $low = (int) $model->stok <= $min;
                          $label = (int) $model->stok . ' <span style="font-weight:400;color:#91918c;">/ min ' . $min . '</span>';
                          return Html::tag(
                              'span',
                              $label,
                              ['class' => 'pin-stok' . ($low ? ' low' : '')]
                          );
                      },
                  ],
                  [
                      'label' => 'Harga Beli',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return Html::tag('span', Utility::rupiah($model->harga_beli), ['class' => 'pin-price']);
                      },
                  ],
                  [
                      'label' => 'Harga Jual',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return Html::tag('span', Utility::rupiah($model->harga_jual), ['class' => 'pin-price']);
                      },
                  ],
                  [
                      'class' => 'yii\grid\ActionColumn',
                      'template' => '{update}',
                      'buttons' => [
                          'update' => function ($url) {
                              return Html::a('<i class="fa fa-pencil"></i>', $url, [
                                  'class' => 'pin-action',
                                  'title' => 'Ubah',
                                  'data-pjax' => '0',
                              ]);
                          },
                      ],
                  ],
              ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>

        <div class="tab-pane" id="tab_2">
          <?php Pjax::begin([
              'id' => 'grid-barang-habis',
              'timeout' => false,
              'enablePushState' => false,
              'clientOptions' => ['method' => 'GET'],
          ]); ?>

          <?= GridView::widget([
              'dataProvider' => $dataProvider2,
              'filterModel' => $searchModel,
              'tableOptions' => ['class' => 'table'],
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                  'kd_barang',
                  'nama_barang',
                  'lokasi',
                  'stok',
                  [
                      'label' => 'Harga Beli',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return Utility::rupiah($model->harga_beli);
                      },
                  ],
                  [
                      'label' => 'Harga Jual',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return Utility::rupiah($model->harga_jual);
                      },
                  ],
                  [
                      'class' => 'yii\grid\ActionColumn',
                      'template' => '{update}&nbsp{delete}',
                  ],
              ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>

        <div class="tab-pane" id="tab_3">
          <?php Pjax::begin([
              'id' => 'grid-barang-ed',
              'timeout' => false,
              'enablePushState' => false,
              'clientOptions' => ['method' => 'GET'],
          ]); ?>

          <?= GridView::widget([
              'dataProvider' => $dataProvider3,
              'filterModel' => $searchModel,
              'tableOptions' => ['class' => 'table'],
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                  [
                      'attribute' => 'kd_barang',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return $model->kd_barang;
                      },
                  ],
                  [
                      'attribute' => 'nama_barang',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return $model->barang->nama_barang;
                      },
                  ],
                  [
                      'label' => 'Expired Date',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return date('d-m-Y', strtotime($model->tgl_ed));
                      },
                  ],
              ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>

        <div class="tab-pane" id="tab_4">
          <?php Pjax::begin([
              'id' => 'grid-barang-before-ed',
              'timeout' => false,
              'enablePushState' => false,
              'clientOptions' => ['method' => 'GET'],
          ]); ?>

          <?= GridView::widget([
              'dataProvider' => $dataProvider4,
              'filterModel' => $searchModel,
              'tableOptions' => ['class' => 'table'],
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                  [
                      'attribute' => 'kd_barang',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return $model->kd_barang;
                      },
                  ],
                  [
                      'attribute' => 'nama_barang',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return $model->barang->nama_barang;
                      },
                  ],
                  [
                      'label' => 'Expired Date',
                      'format' => 'raw',
                      'value' => function ($model) {
                          return date('d-m-Y', strtotime($model->tgl_ed));
                      },
                  ],
              ],
          ]); ?>

          <?php Pjax::end(); ?>
        </div>
      </div>
    </div>

  </div>
</div>
