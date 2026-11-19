<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SettingApp */

$this->title = Yii::t('app', 'Setting Aplikasi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('var url = "' . Url::to(['/setting/simpansetting']) . '";');
$this->registerJs(<<<JS
	$(document).on("click", "#simpan-setting", function () {
		var nama_app = $('#settingapp-app_name').val();
		var alamat = $('#settingapp-alamat').val();
		var no_telp = $('#settingapp-no_telp').val();
		var email = $('#settingapp-email').val();

		$.ajax({
            type: 'post',
            url: url,
            dataType: 'html',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'nama_app':nama_app,
                'alamat':alamat,
                'no_telp':no_telp,
                'email':email
            },
            success: function(v){
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Pengaturan aplikasi berhasil disimpan.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    location.reload();
                }
            },
            error: function(){
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan pengaturan.",
                        icon: "error"
                    });
                }
            },
            'complete':function(json)
            { 
                SimpleLoading.stop();
            },
        });
	});

    $(document).on("keypress", "#settingapp-app_name, #settingapp-no_telp, #settingapp-email", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $("#simpan-setting").click();
        }
    });
JS
);
?>

<style>
.pin-setting-page {
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
  --pin-danger-pale:      #fde8eb;
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

.pin-setting-page * {
  box-sizing: border-box;
}

.pin-setting-grid {
  display: grid;
  grid-template-columns: minmax(360px, 1.3fr) 1fr;
  gap: 24px;
  align-items: start;
}

@media (max-width: 900px) {
  .pin-setting-grid {
    grid-template-columns: 1fr;
  }
}

.pin-setting-page .pin-card {
  background: var(--pin-canvas);
  border-radius: var(--pin-r-md);
  overflow: hidden;
  border: 1px solid var(--pin-hairline-soft);
  box-shadow: 0 2px 12px rgba(0,0,0,.05);
}

.pin-setting-page .pin-card-header {
  background: var(--pin-primary);
  color: #fff;
  padding: 16px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
}

.pin-setting-page .pin-header-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

.pin-setting-page .pin-header-icon {
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

.pin-setting-page .pin-header-title {
  font-size: 16px;
  font-weight: 700;
  letter-spacing: .3px;
  text-transform: uppercase;
  margin: 0;
  line-height: 1.2;
  color: #fff;
}

.pin-setting-page .pin-card-body {
  padding: 24px;
}

/* Form Styles */
.pin-setting-page .form-group {
  margin-bottom: 20px;
}

.pin-setting-page .pin-label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--pin-mute);
  margin-bottom: 8px;
}

.pin-setting-page .form-control {
  width: 100%;
  height: 44px;
  padding: 10px 16px;
  background: var(--pin-canvas);
  border: 1px solid var(--pin-hairline);
  border-radius: var(--pin-r-md);
  font-family: var(--pin-font);
  font-size: 14px;
  color: var(--pin-ink);
  outline: none;
  box-shadow: none;
  transition: border-color .15s, box-shadow .15s;
}

.pin-setting-page .form-control:focus {
  border-color: var(--pin-ink);
  box-shadow: 0 0 0 2px rgba(67,94,229,.15);
}

.pin-setting-page textarea.form-control {
  height: auto;
  min-height: 72px;
  resize: vertical;
  line-height: 1.45;
}

.pin-setting-page .pin-hint {
  font-size: 12px;
  color: var(--pin-mute);
  margin-top: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.pin-setting-page .help-block {
  font-size: 12px;
  color: var(--pin-primary);
  margin-top: 6px;
  margin-bottom: 0;
}

.pin-setting-page .has-error .form-control {
  border-color: var(--pin-primary);
}

.pin-setting-page .pin-btn-save {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  height: 44px;
  padding: 0 28px;
  font-family: var(--pin-font);
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  border: none;
  border-radius: var(--pin-r-md);
  cursor: pointer;
  background: var(--pin-primary);
  color: #fff;
  transition: background .15s, transform .1s;
}

.pin-setting-page .pin-btn-save:hover,
.pin-setting-page .pin-btn-save:focus {
  background: var(--pin-primary-pressed);
  color: #fff;
  outline: none;
}

.pin-setting-page .pin-btn-save:active {
  transform: scale(0.99);
}

/* Info Side Card */
.pin-setting-page .pin-info-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 0;
  border-bottom: 1px solid var(--pin-hairline-soft);
  font-size: 13px;
}

.pin-setting-page .pin-info-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.pin-setting-page .pin-info-item:first-child {
  padding-top: 0;
}

.pin-setting-page .pin-info-label {
  color: var(--pin-mute);
  font-weight: 600;
}

.pin-setting-page .pin-info-value {
  color: var(--pin-ink);
  font-weight: 700;
  font-family: monospace;
}

.pin-setting-page .pin-badge-status {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  border-radius: var(--pin-r-full);
  font-size: 12px;
  font-weight: 700;
  background: var(--pin-success-pale);
  color: var(--pin-success-deep);
}

.pin-setting-page .pin-badge-status .pin-dot {
  width: 7px;
  height: 7px;
  border-radius: var(--pin-r-full);
  background: #189849;
}

.pin-setting-page .pin-btn-sm {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 10px;
  border-radius: var(--pin-r-full);
  font-size: 11px;
  font-weight: 700;
  background: var(--pin-surface-card);
  border: 1px solid var(--pin-hairline);
  color: var(--pin-ink);
  cursor: pointer;
  transition: background .12s;
  text-decoration: none;
}

.pin-setting-page .pin-btn-sm:hover {
  background: var(--pin-secondary-bg);
}

.pin-setting-page .pin-notice-card {
  margin-top: 20px;
  background: var(--pin-surface-card);
  border: 1px solid var(--pin-hairline-soft);
  border-radius: var(--pin-r-md);
  padding: 16px;
  font-size: 13px;
  line-height: 1.5;
  color: var(--pin-body);
}

.pin-setting-page .pin-notice-title {
  font-weight: 700;
  color: var(--pin-ink);
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}
</style>

<div class="pin-setting-page">
  <div class="pin-setting-grid">

    <!-- Card 1: Form Pengaturan Aplikasi -->
    <div class="pin-card">
      <div class="pin-card-header">
        <div class="pin-header-left">
          <span class="pin-header-icon"><i class="fa fa-sliders"></i></span>
          <h2 class="pin-header-title">Pengaturan Aplikasi</h2>
        </div>
      </div>
      <div class="pin-card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'form-setting',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'pin-label'],
                'inputOptions' => ['class' => 'form-control'],
            ],
        ]); ?>

            <?= $form->field($model, 'app_name')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nama Toko / Usaha',
                'autofocus' => true,
            ]) ?>

            <?= $form->field($model, 'alamat')->textarea([
                'rows' => 2,
                'placeholder' => 'Alamat lengkap toko (contoh: Kauman, Pedan, Klaten)',
            ])->hint('<div class="pin-hint"><i class="fa fa-map-marker"></i> Alamat ini akan dicetak pada kepala nota transaksi dan ditampilkan pada informasi toko.</div>') ?>

            <?= $form->field($model, 'no_telp')->textInput([
                'maxlength' => true,
                'placeholder' => 'Contoh: (0272) 898246 atau 08123456789',
            ])->hint('<div class="pin-hint"><i class="fa fa-phone"></i> Nomor telepon / WhatsApp toko untuk dicetak pada nota transaksi.</div>') ?>

            <?= $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => 'Email kontak toko',
                'type' => 'email',
            ]) ?>

            <div style="margin-top: 28px; padding-top: 16px; border-top: 1px solid var(--pin-hairline-soft);">
                <?= Html::button('<i class="fa fa-check"></i> ' . Yii::t('app', 'Simpan Pengaturan'), [
                    'class' => 'pin-btn-save',
                    'id' => 'simpan-setting',
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>
      </div>
    </div>

    <!-- Card 2: Informasi Sistem -->
    <div class="pin-card">
      <div class="pin-card-header">
        <div class="pin-header-left">
          <span class="pin-header-icon"><i class="fa fa-server"></i></span>
          <h2 class="pin-header-title">Informasi Sistem</h2>
        </div>
      </div>
      <div class="pin-card-body">
        
        <div class="pin-info-item">
          <span class="pin-info-label">Status Server POS</span>
          <span class="pin-badge-status"><span class="pin-dot"></span> Aktif</span>
        </div>

        <div class="pin-info-item">
          <span class="pin-info-label">Nama Toko Aktif</span>
          <span style="font-weight:700; color:var(--pin-ink);"><?= Html::encode($model->app_name ?: '-') ?></span>
        </div>

        <div class="pin-info-item">
          <span class="pin-info-label">Alamat Toko Aktif</span>
          <span style="font-weight:600; color:var(--pin-ink); text-align:right; max-width:60%; word-break:break-word;"><?= Html::encode($model->alamat ?: '-') ?></span>
        </div>

        <div class="pin-info-item">
          <span class="pin-info-label">Kontak / Telp Toko</span>
          <span style="font-weight:600; color:var(--pin-ink);"><?= Html::encode($model->no_telp ?: '-') ?></span>
        </div>

        <div class="pin-info-item">
          <span class="pin-info-label">Email Kontak</span>
          <span style="font-weight:600; color:var(--pin-ink);"><?= Html::encode($model->email ?: '-') ?></span>
        </div>

        <div class="pin-info-item">
          <span class="pin-info-label">Waktu Server</span>
          <span style="font-weight:600; color:var(--pin-ink);"><?= date('d M Y, H:i') ?> WIB</span>
        </div>

        <div class="pin-info-item">
          <span class="pin-info-label">Versi PHP</span>
          <span class="pin-info-value">PHP <?= PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION ?></span>
        </div>

        <div class="pin-notice-card">
          <div class="pin-notice-title">
            <i class="fa fa-info-circle" style="color:var(--pin-primary);"></i> Informasi Struk
          </div>
          Nama toko, alamat, dan kontak toko yang disimpan akan dicetak pada kepala (header) nota transaksi dan ditampilkan pada laporan POS.
        </div>

      </div>
    </div>

  </div>
</div>