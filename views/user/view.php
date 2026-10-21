<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kelola User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="pin-view-user col-md-8 col-md-offset-2">
  <div class="pin-view-card">

    <div class="pin-view-header">
      <span class="pin-header-icon"><i class="fa fa-user"></i></span>
      <h2 class="pin-header-title">Detail User: <?= Html::encode($this->title) ?></h2>
    </div>

    <div class="pin-view-body">
      <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
              'id',
              'username',
              'name',
              [
                  'label' => 'Password',
                  'value' => str_repeat('•', 8),
              ],
              [
                  'label' => 'Grup',
                  'value' => function($model) {
                      $group = \app\models\AppUserGroup::findOne($model->id_group);
                      return $group ? $group->group_name : $model->id_group;
                  },
              ],
              [
                  'label' => 'Status',
                  'format' => 'raw',
                  'value' => function($model) {
                      return $model->aktif == 1 
                          ? '<span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:12px;font-weight:700;background:#c7f0da;color:#103c25;">Aktif</span>'
                          : '<span style="display:inline-flex;align-items:center;padding:2px 10px;border-radius:9999px;font-size:12px;font-weight:700;background:#fde8eb;color:#e60023;">Non-Aktif</span>';
                  },
              ],
              'created_at',
              'updated_at',
          ],
      ]) ?>

      <div class="pin-view-actions">
        <?= Html::a('<i class="fa fa-pencil"></i> ' . Yii::t('app', 'Ubah'), ['update', 'id' => $model->id], ['class' => 'pin-btn pin-btn-primary']) ?>
        <?= Html::a('<i class="fa fa-arrow-left"></i> ' . Yii::t('app', 'Kembali'), ['index'], ['class' => 'pin-btn pin-btn-secondary']) ?>
        <?= Html::a('<i class="fa fa-trash-o"></i> ' . Yii::t('app', 'Hapus'), ['delete', 'id' => $model->id], [
            'class' => 'pin-btn pin-btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Yakin ingin menghapus user ini?'),
                'method' => 'post',
            ],
        ]) ?>
      </div>
    </div>

  </div>
</div>
