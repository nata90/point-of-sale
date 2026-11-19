<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AppUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kelola User');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pin-user-page">
  <div class="pin-user-card">

    <div class="pin-user-header">
      <div class="pin-user-header-left">
        <span class="pin-user-header-icon"><i class="fa fa-users"></i></span>
        <h1 class="pin-user-header-title"><?= Html::encode($this->title) ?></h1>
      </div>
      <?= Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Buat User'), ['create'], [
          'class' => 'pin-btn pin-btn-primary',
      ]) ?>
    </div>

    <div class="pin-user-body">
      <?php Pjax::begin([
          'id' => 'grid-user',
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
                  'attribute' => 'id',
                  'headerOptions' => ['style' => 'width: 70px; text-align: center;'],
                  'contentOptions' => ['style' => 'width: 70px; text-align: center;'],
                  'format' => 'raw',
                  'value' => function ($model) {
                      return Html::tag('span', Html::encode($model->id), ['class' => 'pin-code']);
                  },
              ],
              [
                  'attribute' => 'username',
                  'format' => 'raw',
                  'value' => function ($model) {
                      return '<span class="pin-username"><span class="pin-avatar-icon"><i class="fa fa-user"></i></span> ' . Html::encode($model->username) . '</span>';
                  },
              ],
              [
                  'attribute' => 'name',
                  'format' => 'raw',
                  'value' => function ($model) {
                      return Html::tag('span', Html::encode($model->name), ['class' => 'pin-name']);
                  },
              ],
              [
                  'attribute' => 'password',
                  'format' => 'raw',
                  'headerOptions' => ['style' => 'width: 140px;'],
                  'value' => function ($model) {
                      return Html::tag('span', str_repeat('•', 8), ['class' => 'pin-password']);
                  },
                  'filter' => false,
              ],
              [
                  'class' => 'yii\grid\ActionColumn',
                  'template' => '{view} {update} {delete}',
                  'header' => 'Aksi',
                  'headerOptions' => ['style' => 'width: 120px; text-align: center;'],
                  'contentOptions' => ['style' => 'width: 120px; text-align: center; white-space: nowrap;'],
                  'visibleButtons' => [
                      'delete' => function ($model) {
                          return (int)$model->id !== 1;
                      },
                  ],
                  'buttons' => [
                      'view' => function ($url) {
                          return Html::a('<i class="fa fa-eye"></i>', $url, [
                              'class' => 'pin-action',
                              'title' => Yii::t('app', 'Lihat'),
                              'data-pjax' => '0',
                          ]);
                      },
                      'update' => function ($url) {
                          return Html::a('<i class="fa fa-pencil"></i>', $url, [
                              'class' => 'pin-action',
                              'title' => Yii::t('app', 'Ubah'),
                              'data-pjax' => '0',
                          ]);
                      },
                      'delete' => function ($url, $model) {
                          if ((int)$model->id === 1) {
                              return '';
                          }
                          return Html::a('<i class="fa fa-trash-o"></i>', $url, [
                              'class' => 'pin-action pin-action-danger',
                              'title' => Yii::t('app', 'Hapus'),
                              'data' => [
                                  'confirm' => Yii::t('app', 'Yakin ingin menghapus user ini?'),
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
