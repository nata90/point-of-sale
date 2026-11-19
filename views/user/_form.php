<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\AppUserGroup;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */
/* @var $form yii\widgets\ActiveForm */

if (!isset($listData) || empty($listData)) {
    $listData = ArrayHelper::map(AppUserGroup::find()->where('aktif = 1')->all(), 'id_group', 'group_name');
}

$formTitle = $model->isNewRecord ? Yii::t('app', 'Buat User Baru') : Yii::t('app', 'Ubah Data User: {name}', ['name' => $model->name]);
?>

<div class="pin-form-user col-md-8 col-md-offset-2">
  <div class="pin-form-card">

    <div class="pin-form-header">
      <span class="pin-form-header-icon"><i class="fa fa-user"></i></span>
      <h2 class="pin-form-header-title"><?= Html::encode($formTitle) ?></h2>
    </div>

    <div class="pin-form-body">
      <?php $form = ActiveForm::begin([
          'options' => ['class' => 'pin-user-form-inner'],
      ]); ?>

      <div class="pin-form-row-2">
        <?= $form->field($model, 'username')->textInput([
            'maxlength' => true,
            'placeholder' => 'Username akun',
            'class' => 'form-control',
            'autofocus' => $model->isNewRecord,
        ]) ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true,
            'placeholder' => 'Nama lengkap',
            'class' => 'form-control',
        ]) ?>
      </div>

      <div class="pin-form-row-2">
        <div>
          <?= $form->field($model, 'password')->passwordInput([
              'maxlength' => true,
              'value' => '',
              'placeholder' => $model->isNewRecord ? 'Password akun' : 'Kosongkan jika tidak diubah',
              'class' => 'form-control',
          ]) ?>
          <?php if (!$model->isNewRecord): ?>
            <div class="pin-hint"><i class="fa fa-info-circle"></i> Biarkan kosong jika tidak ingin mengganti password saat ini.</div>
          <?php endif; ?>
        </div>

        <?= $form->field($model, 'id_group')->dropDownList(
            $listData,
            ['prompt' => 'Pilih Grup Pengguna', 'class' => 'form-control']
        ) ?>
      </div>

      <div class="pin-checkbox-wrap">
        <?= $form->field($model, 'aktif')->checkbox([
            'label' => 'Status Aktif (user dapat masuk ke sistem)',
            'labelOptions' => ['style' => 'font-weight:600;'],
        ]) ?>
      </div>

      <div class="pin-form-actions">
        <?= Html::submitButton(
            $model->isNewRecord ? '<i class="fa fa-plus"></i> ' . Yii::t('app', 'Buat User') : '<i class="fa fa-save"></i> ' . Yii::t('app', 'Simpan Perubahan'),
            ['class' => 'pin-btn pin-btn-primary']
        ) ?>
        <?= Html::a(Yii::t('app', 'Batal'), ['index'], ['class' => 'pin-btn pin-btn-secondary']) ?>
      </div>

      <?php ActiveForm::end(); ?>
    </div>

  </div>
</div>
