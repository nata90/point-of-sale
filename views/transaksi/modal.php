<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Utility;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Modal */
/* @var $modal_hari_ini app\models\Modal|null */

$this->title = Yii::t('app', 'Set Modal Awal');
$this->params['breadcrumbs'][] = $this->title;

// Isi nilai form jika sudah ada modal awal hari ini dan belum diisi ulang
if (!empty($modal_hari_ini) && empty($model->modal_awal)) {
    $model->keterangan = $modal_hari_ini->keterangan;
    $model->modal_awal = $modal_hari_ini->modal_awal;
}

$this->registerJs('var url = "' . Url::to(['/transaksi/simpanmodal']) . '";');
$this->registerJs(<<<JS
	$(document).on("click", "#simpan-modal", function () {
		var keterangan = $('#modal-keterangan').val();
		var modal = $('#modal-modal_awal').val();

        if (!modal || modal.trim() === '' || isNaN(modal) || Number(modal) < 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: "Perhatian",
                    text: "Silakan masukkan nominal modal awal yang valid!",
                    icon: "warning"
                });
            } else {
                alert("Silakan masukkan nominal modal awal yang valid!");
            }
            $('#modal-modal_awal').focus();
            return;
        }

		$.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                if (typeof SimpleLoading !== 'undefined') {
                    SimpleLoading.start('gears'); 
                }
            },
            data: {
                'keterangan':keterangan,
                'modal':modal
            },
            success: function(v){
                if(v.success == 1){
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Modal awal berhasil disimpan.",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        location.reload();
                    }
                }else{
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: "Gagal Menyimpan",
                            text: v.msg || "Terjadi kesalahan saat menyimpan modal awal.",
                            icon: "error"
                        });
                    } else {
                        alert(v.msg || "Terjadi kesalahan.");
                    }
                }
            },
            error: function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Error",
                        text: "Gagal menghubungi server. Silakan coba lagi.",
                        icon: "error"
                    });
                }
            },
            'complete':function(json)
            {
                if (typeof SimpleLoading !== 'undefined') {
                    SimpleLoading.stop();
                }
            },
        });
	});

    $(document).on("keypress", "#modal-keterangan, #modal-modal_awal", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $("#simpan-modal").click();
        }
    });
JS
);
?>

<div class="pin-modal-page">
  <div class="pin-modal-grid">

    <!-- Card 1: Input Modal Awal -->
    <div class="pin-card">
      <div class="pin-card-header">
        <div class="pin-header-left">
          <span class="pin-header-icon"><i class="fa fa-calculator"></i></span>
          <h2 class="pin-header-title">Input Modal Awal</h2>
        </div>
      </div>
      <div class="pin-card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'form-modal',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'pin-label'],
                'inputOptions' => ['class' => 'form-control pin-input'],
            ],
        ]); ?>

            <?= $form->field($model, 'keterangan')->textInput([
                'maxlength' => true,
                'placeholder' => 'Contoh: Modal Kasir Pagi / Buka Kasir',
                'autofocus' => empty($modal_hari_ini),
            ]) ?>

            <?= $form->field($model, 'modal_awal', [
                'template' => "{label}\n<div class=\"pin-input-wrap\"><span class=\"pin-input-prefix\">Rp</span>{input}</div>\n{error}",
            ])->textInput([
                'maxlength' => true,
                'type' => 'number',
                'placeholder' => '0',
                'class' => 'form-control pin-input pin-input-has-prefix',
            ]) ?>

            <div style="margin-top: 24px;">
                <?= Html::button('<i class="fa fa-check"></i> ' . Yii::t('app', 'Simpan Modal Awal'), [
                    'class' => 'pin-btn-save',
                    'id' => 'simpan-modal',
                ]) ?>
            </div>

            <div class="pin-card-note">
                <i class="fa fa-info-circle"></i>
                <span>Modal awal dicatat sebagai saldo awal laci kasir per hari ini dan digunakan pada perhitungan rekap transaksi.</span>
            </div>

        <?php ActiveForm::end(); ?>
      </div>
    </div>

    <!-- Card 2: Modal Awal Hari Ini -->
    <div class="pin-card">
      <div class="pin-card-header">
        <div class="pin-header-left">
          <span class="pin-header-icon"><i class="fa fa-calendar-check-o"></i></span>
          <h2 class="pin-header-title">Modal Awal Hari Ini</h2>
        </div>
        <div class="pin-header-right">
          <span class="pin-header-badge">
            <i class="fa fa-calendar"></i>
            <?= date('d M Y') ?>
          </span>
        </div>
      </div>

      <!-- Stat Hero Box -->
      <div class="pin-stat-hero">
        <div class="pin-stat-left">
          <div class="pin-stat-icon-wrap">
            <i class="fa fa-money"></i>
          </div>
          <div>
            <div class="pin-stat-label">Total Modal Awal Terdaftar</div>
            <div class="pin-stat-value">
              <?= $modal_hari_ini ? Utility::rupiah($modal_hari_ini->modal_awal) : 'Rp 0' ?>
            </div>
          </div>
        </div>
        <div>
          <?php if ($modal_hari_ini): ?>
            <span class="pin-stat-badge is-set">
              <i class="fa fa-check-circle"></i> Sudah Diatur
            </span>
          <?php else: ?>
            <span class="pin-stat-badge not-set">
              <i class="fa fa-clock-o"></i> Belum Diatur
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="pin-card-body pin-card-body-table">
        <div class="pin-table-responsive">
          <table class="pin-table">
            <thead>
              <tr>
                <th style="width: 140px;">Tanggal</th>
                <th>Keterangan</th>
                <th style="text-align: right; width: 180px;">Nominal Modal</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($modal_hari_ini): ?>
                <tr>
                    <td>
                        <span style="font-weight: 600; color: var(--pin-body);">
                            <i class="fa fa-calendar-o" style="margin-right: 4px; color: var(--pin-ash);"></i>
                            <?= date('d-m-Y', strtotime($modal_hari_ini->tanggal)) ?>
                        </span>
                    </td>
                    <td>
                        <span class="pin-name">
                            <?= Html::encode($modal_hari_ini->keterangan ?: '(Tanpa keterangan)') ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <span class="pin-price">
                            <?= Utility::rupiah($modal_hari_ini->modal_awal) ?>
                        </span>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="pin-empty-state">
                        <div class="pin-empty-icon">
                            <i class="fa fa-inbox"></i>
                        </div>
                        <div class="pin-empty-title">Belum Ada Modal Awal Hari Ini</div>
                        <div>Silakan isi formulir di sebelah kiri untuk menetapkan saldo awal kasir.</div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if ($modal_hari_ini): ?>
          <div class="pin-info-alert">
            <i class="fa fa-info-circle"></i>
            <div>Modal awal untuk hari ini sudah aktif. Jika ada penyesuaian nominal, ubah nilai pada formulir sebelah kiri lalu klik <strong>Simpan Modal Awal</strong> untuk memperbarui data.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>