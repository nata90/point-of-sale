<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */
/* @var $form yii\widgets\ActiveForm */

$formTitle = $model->isNewRecord ? 'Buat Barang Baru' : 'Ubah Data Barang';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.pin-form-barang {
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
  --pin-secondary-pressed:#c8c8c1;
  --pin-r-md:             16px;
  --pin-r-full:           9999px;
  --pin-font: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  font-family: var(--pin-font);
  color: var(--pin-body);
  max-width: 640px;
}

.pin-form-barang .pin-form-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
  border: 1px solid var(--pin-hairline-soft);
}

.pin-form-barang .pin-form-header {
  background: var(--pin-primary);
  color: #fff;
  padding: 16px 24px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.pin-form-barang .pin-form-header-icon {
  width: 32px;
  height: 32px;
  border-radius: var(--pin-r-full);
  background: rgba(255,255,255,.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
  flex-shrink: 0;
}
.pin-form-barang .pin-form-header-title {
  font-size: 16px;
  font-weight: 700;
  letter-spacing: .3px;
  text-transform: uppercase;
  margin: 0;
  line-height: 1.2;
}

.pin-form-barang .pin-form-body {
  padding: 24px;
}

.pin-form-barang .form-group {
  margin-bottom: 16px;
}
.pin-form-barang .form-group label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-mute);
  margin-bottom: 6px;
}

.pin-form-barang .form-control {
  display: block;
  width: 100%;
  height: 44px;
  padding: 11px 15px;
  background: var(--pin-canvas);
  border: 1px solid var(--pin-hairline);
  border-radius: var(--pin-r-md);
  font-family: var(--pin-font);
  font-size: 16px;
  color: var(--pin-ink);
  box-sizing: border-box;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
  box-shadow: none;
}
.pin-form-barang .form-control:focus {
  border-color: var(--pin-ink);
  box-shadow: 0 0 0 3px rgba(67,94,229,.18);
}
.pin-form-barang .form-group.has-error .form-control {
  border-color: var(--pin-primary);
}
.pin-form-barang .help-block {
  font-size: 12px;
  color: var(--pin-primary);
  margin-top: 4px;
}

.pin-form-barang .pin-code-row {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 10px;
  align-items: end;
}
.pin-form-barang .pin-code-row .form-group { margin-bottom: 0; }

.pin-form-barang .pin-form-row-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
@media (max-width: 600px) {
  .pin-form-barang .pin-form-row-2 { grid-template-columns: 1fr; }
}

.pin-form-barang .pin-checkbox-wrap {
  background: var(--pin-surface-card);
  border: 1px solid var(--pin-hairline-soft);
  border-radius: var(--pin-r-md);
  padding: 14px 16px;
}
.pin-form-barang .pin-checkbox-wrap label {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  font-weight: 600;
  text-transform: none;
  letter-spacing: 0;
  color: var(--pin-ink);
  margin: 0;
  cursor: pointer;
}
.pin-form-barang .pin-checkbox-wrap input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: var(--pin-primary);
  cursor: pointer;
}

.pin-form-barang .pin-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  height: 44px;
  padding: 6px 24px;
  font-family: var(--pin-font);
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  border: none;
  border-radius: var(--pin-r-md);
  cursor: pointer;
  transition: background .15s;
}
.pin-form-barang .pin-btn-primary {
  background: var(--pin-primary);
  color: #fff;
}
.pin-form-barang .pin-btn-primary:hover,
.pin-form-barang .pin-btn-primary:focus {
  background: var(--pin-primary-pressed);
  color: #fff;
  outline: none;
}
.pin-form-barang .pin-btn-secondary {
  background: var(--pin-secondary-bg);
  color: var(--pin-ink);
}
.pin-form-barang .pin-btn-secondary:hover,
.pin-form-barang .pin-btn-secondary:focus {
  background: var(--pin-secondary-pressed);
  outline: none;
}

.pin-form-barang .pin-form-actions {
  display: flex;
  gap: 10px;
  margin-top: 8px;
  padding-top: 8px;
  border-top: 1px solid var(--pin-hairline-soft);
}
</style>

<div class="pin-form-barang col-md-8">
  <div class="pin-form-card">

    <div class="pin-form-header">
      <span class="pin-form-header-icon"><i class="fa fa-cube"></i></span>
      <h2 class="pin-form-header-title"><?= Html::encode($formTitle) ?></h2>
    </div>

    <div class="pin-form-body">
      <?php $form = ActiveForm::begin(['options' => ['class' => 'pin-form-barang-inner']]); ?>

      <div class="pin-code-row">
        <div>
          <?= $form->field($model, 'kd_barang')->textInput([
              'maxlength' => true,
              'class' => 'form-control',
          ]) ?>
        </div>
        <div>
          <?= Html::button(Yii::t('app', 'Buat Kode'), [
              'class' => 'pin-btn pin-btn-secondary generate-code',
          ]) ?>
        </div>
      </div>

      <?= $form->field($model, 'nama_barang')->textInput([
          'maxlength' => true,
          'class' => 'form-control',
      ]) ?>

      <?= $form->field($model, 'lokasi')->textInput([
          'maxlength' => true,
          'value' => $model->lokasi ?: '-',
          'class' => 'form-control',
      ]) ?>

      <div class="pin-form-row-2">
        <?= $form->field($model, 'harga_beli')->textInput([
            'type' => 'number',
            'min' => 0,
            'class' => 'form-control',
        ]) ?>
        <?= $form->field($model, 'harga_jual')->textInput([
            'type' => 'number',
            'min' => 0,
            'class' => 'form-control',
        ]) ?>
      </div>

      <div class="pin-form-row-2">
        <?= $form->field($model, 'stok')->textInput([
            'type' => 'number',
            'min' => 0,
            'class' => 'form-control',
            'value' => $model->stok ?? 0,
        ]) ?>
        <?= $form->field($model, 'min_stok')->textInput([
            'type' => 'number',
            'min' => 0,
            'class' => 'form-control',
            'value' => $model->min_stok ?? 5,
            'hint' => 'Ambang stok minimum untuk notifikasi Stok Menipis',
        ]) ?>
      </div>

      <div class="pin-checkbox-wrap">
        <?= $form->field($model, 'aktif')->checkbox([
            'label' => 'Barang aktif (tampil di penjualan)',
            'labelOptions' => ['style' => 'font-weight:600;'],
        ]) ?>
      </div>

      <div class="pin-form-actions">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('app', 'Simpan Barang') : Yii::t('app', 'Update'),
            ['class' => 'pin-btn pin-btn-primary']
        ) ?>
        <?= Html::a(Yii::t('app', 'Batal'), ['index'], ['class' => 'pin-btn pin-btn-secondary']) ?>
      </div>

      <?php ActiveForm::end(); ?>
    </div>

  </div>
</div>
