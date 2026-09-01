<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
.pin-popup-create {
  --pin-primary:         #e60023;
  --pin-primary-pressed: #cc001f;
  --pin-ink:             #000000;
  --pin-body:            #33332e;
  --pin-mute:            #62625b;
  --pin-ash:             #91918c;
  --pin-hairline:        #dadad3;
  --pin-hairline-soft:   #e5e5e0;
  --pin-canvas:          #ffffff;
  --pin-surface-soft:    #fbfbf9;
  --pin-surface-card:    #f6f6f3;
  --pin-secondary-bg:    #e5e5e0;
  --pin-secondary-pressed:#c8c8c1;
  --pin-r-md:            16px;
  --pin-r-lg:            32px;
  --pin-r-full:          9999px;
  --pin-font: 'Inter', -apple-system, system-ui, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  font-family: var(--pin-font);
  color: var(--pin-body);
  background: transparent;
}

/* Sembunyikan header modal Bootstrap — header ada di dalam popup */
#modal:has(.pin-popup-create) .modal-header { display: none !important; }
#modal:has(.pin-popup-create) .modal-body {
  padding: 0 !important;
  background: transparent !important;
}
#modal:has(.pin-popup-create) .modal-content {
  border: none;
  border-radius: var(--pin-r-md);
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.14);
  background: transparent !important;
}

.pin-popup-create .pin-popup-card {
  background: var(--pin-canvas);
  overflow: hidden;
}

/* Header merah */
.pin-popup-create .pin-popup-header {
  background: var(--pin-primary);
  color: #fff;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.pin-popup-create .pin-popup-header-icon {
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
.pin-popup-create .pin-popup-header-title {
  font-size: 16px;
  font-weight: 700;
  letter-spacing: .3px;
  text-transform: uppercase;
  line-height: 1.2;
  margin: 0;
}

/* Body putih */
.pin-popup-create .pin-popup-body {
  padding: 20px;
  background: var(--pin-canvas);
}

/* Info banner — subtitle lebih jelas */
.pin-popup-create .pin-popup-subtitle {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: var(--pin-surface-card);
  border: 1px solid var(--pin-hairline-soft);
  border-left: 4px solid var(--pin-primary);
  border-radius: var(--pin-r-md);
  padding: 14px 16px;
  margin: 0 0 20px;
}
.pin-popup-create .pin-popup-subtitle-icon {
  flex-shrink: 0;
  width: 32px;
  height: 32px;
  border-radius: var(--pin-r-full);
  background: #fde8eb;
  color: var(--pin-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
}
.pin-popup-create .pin-popup-subtitle-text {
  flex: 1;
  min-width: 0;
}
.pin-popup-create .pin-popup-subtitle-title {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: var(--pin-ink);
  line-height: 1.3;
  margin-bottom: 4px;
}
.pin-popup-create .pin-popup-subtitle-desc {
  display: block;
  font-size: 13px;
  font-weight: 400;
  color: var(--pin-mute);
  line-height: 1.45;
  margin: 0;
}

.pin-popup-create .form-group {
  margin-bottom: 14px;
}
.pin-popup-create .form-group label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-mute);
  margin-bottom: 6px;
}

.pin-popup-create .form-control {
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
.pin-popup-create .form-control:focus {
  border-color: var(--pin-ink);
  box-shadow: 0 0 0 3px rgba(67,94,229,.18);
}
.pin-popup-create .form-group.has-error .form-control {
  border-color: var(--pin-primary);
}
.pin-popup-create .help-block {
  font-size: 12px;
  color: var(--pin-primary);
  margin-top: 4px;
}

.pin-popup-create .pin-code-row {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 10px;
  align-items: end;
}
.pin-popup-create .pin-code-row .form-group { margin-bottom: 0; }

.pin-popup-create .pin-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  height: 44px;
  padding: 6px 18px;
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
.pin-popup-create .pin-btn-primary {
  background: var(--pin-primary);
  color: #fff;
  width: 100%;
}
.pin-popup-create .pin-btn-primary:hover,
.pin-popup-create .pin-btn-primary:focus {
  background: var(--pin-primary-pressed);
  color: #fff;
  outline: none;
}
.pin-popup-create .pin-btn-secondary {
  background: var(--pin-secondary-bg);
  color: var(--pin-ink);
}
.pin-popup-create .pin-btn-secondary:hover,
.pin-popup-create .pin-btn-secondary:focus {
  background: var(--pin-secondary-pressed);
  color: var(--pin-ink);
  outline: none;
}

.pin-popup-create .pin-popup-actions {
  margin-top: 8px;
}
</style>

<div class="pin-popup-create">
  <div class="pin-popup-card">

    <div class="pin-popup-header">
      <span class="pin-popup-header-icon"><i class="fa fa-cube"></i></span>
      <h2 class="pin-popup-header-title">Buat Barang Baru</h2>
    </div>

    <div class="pin-popup-body">
<?php $form = ActiveForm::begin(['options' => ['class' => 'pin-popup-form']]); ?>

      <div class="pin-popup-subtitle">
        <span class="pin-popup-subtitle-icon"><i class="fa fa-plus-circle"></i></span>
        <div class="pin-popup-subtitle-text">
          <span class="pin-popup-subtitle-title">Tambah barang baru</span>
          <p class="pin-popup-subtitle-desc">Lengkapi kode, nama, dan harga jual. Barang akan langsung ditambahkan ke transaksi aktif.</p>
        </div>
      </div>

    <div class="pin-code-row">
        <div>
            <?= $form->field($model, 'kd_barang')->textInput([
                'maxlength' => true,
                'value' => $kodebarang,
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
        'id' => 'popup-namabarang',
        'class' => 'form-control',
    ]) ?>

    <?= $form->field($model, 'harga_jual')->textInput([
        'type' => 'number',
        'class' => 'form-control',
    ]) ?>

    <div class="pin-popup-actions">
        <?= Html::button(Yii::t('app', 'Tambah Barang'), [
            'class' => 'pin-btn pin-btn-primary',
            'id' => 'create-item-button',
            'link' => Url::to(['filebarang/popupcreatebarang']),
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>
    </div><!-- /.pin-popup-body -->
  </div><!-- /.pin-popup-card -->
</div>
