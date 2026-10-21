<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Utility;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Pengeluaran */
/* @var $pengeluaran_hari_ini app\models\Pengeluaran[] */

$this->title = Yii::t('app', 'Pengeluaran');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('var url = "' . Url::to(['/transaksi/simpanpengeluaran']) . '"');
$this->registerJs('var url_delete = "' . Url::to(['/transaksi/deletepengeluaran']) . '"');
$this->registerJs(<<<JS
	$(document).on("click", "#simpan-pengeluaran", function () {
		var deskripsi = $('#pengeluaran-deskripsi').val();
		var nilai = $('#pengeluaran-nilai').val();

		$.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'deskripsi':deskripsi,
                'nilai':nilai
            },
            success: function(v){
                if(v.success == 1){
                    location.reload();
                }else{
                    Swal.fire({
                        title: v.msg,
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

    $(document).on("click", ".delete-pengeluaran", function () {
		var id = $(this).attr('id');

		$.ajax({
            type: 'get',
            url: url_delete,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'id':id
            },
            success: function(v){
                if(v.success == 1){
                    location.reload();
                }else{
                    Swal.fire({
                        title: v.msg,
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

    $(document).on("keypress", "#pengeluaran-deskripsi, #pengeluaran-nilai", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $("#simpan-pengeluaran").click();
        }
    });
JS
);

$totalPengeluaranHariIni = 0;
if (!empty($pengeluaran_hari_ini)) {
    foreach ($pengeluaran_hari_ini as $item) {
        $totalPengeluaranHariIni += (float) $item['nilai'];
    }
}
?>

<div class="pin-pengeluaran-page">
  <div class="pin-pengeluaran-grid">

    <!-- Card 1: Input Pengeluaran -->
    <div class="pin-card">
      <div class="pin-card-header">
        <div class="pin-header-left">
          <span class="pin-header-icon"><i class="fa fa-pencil-square-o"></i></span>
          <h2 class="pin-header-title">Input Pengeluaran</h2>
        </div>
      </div>
      <div class="pin-card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'form-pengeluaran',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'pin-label'],
                'inputOptions' => ['class' => 'form-control pin-input'],
            ],
        ]); ?>

            <?= $form->field($model, 'deskripsi')->textInput([
                'maxlength' => true,
                'placeholder' => 'Contoh: Beli Kertas Struk, Galon, dll',
                'autofocus' => true,
            ]) ?>

            <?= $form->field($model, 'nilai', [
                'template' => "{label}\n<div class=\"pin-input-wrap\"><span class=\"pin-input-prefix\">Rp</span>{input}</div>\n{error}",
            ])->textInput([
                'maxlength' => true,
                'type' => 'number',
                'placeholder' => '0',
                'class' => 'form-control pin-input pin-input-has-prefix',
            ]) ?>

            <div style="margin-top: 24px;">
                <?= Html::button('<i class="fa fa-check"></i> ' . Yii::t('app', 'Simpan Pengeluaran'), [
                    'class' => 'pin-btn-save',
                    'id' => 'simpan-pengeluaran',
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>
      </div>
    </div>

    <!-- Card 2: Pengeluaran Hari Ini -->
    <div class="pin-card">
      <div class="pin-card-header">
        <div class="pin-header-left">
          <span class="pin-header-icon"><i class="fa fa-calendar-check-o"></i></span>
          <h2 class="pin-header-title">Pengeluaran Hari Ini</h2>
        </div>
        <div class="pin-header-right">
          <span class="pin-header-badge"><?= count($pengeluaran_hari_ini ?? []) ?> Transaksi</span>
        </div>
      </div>
      <div class="pin-card-body pin-card-body-table">
        <div class="pin-table-responsive">
          <table class="pin-table">
            <thead>
              <tr>
                <th style="width: 48px; text-align: center;">#</th>
                <th>Deskripsi</th>
                <th style="text-align: right;">Nominal</th>
                <th style="width: 60px; text-align: center;">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php if (!empty($pengeluaran_hari_ini)): ?>
                <?php 
                    $number = 1;
                    foreach ($pengeluaran_hari_ini as $val): 
                ?>
                    <tr>
                        <td style="text-align: center;">
                            <span class="pin-code"><?= $number ?>.</span>
                        </td>
                        <td>
                            <span class="pin-name"><?= Html::encode($val['deskripsi']) ?></span>
                        </td>
                        <td style="text-align: right;">
                            <span class="pin-price"><?= Utility::rupiah($val['nilai']) ?></span>
                        </td>
                        <td style="text-align: center;">
                            <?= Html::button('<i class="fa fa-trash-o"></i>', [
                                'class' => 'pin-action-delete delete-pengeluaran',
                                'id' => $val['id'],
                                'title' => Yii::t('app', 'Hapus'),
                            ]) ?>
                        </td>
                    </tr>
                <?php 
                        $number++;
                    endforeach; 
                ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="pin-empty-state">
                        <div class="pin-empty-icon">
                            <i class="fa fa-inbox"></i>
                        </div>
                        <div>Belum ada pengeluaran yang dicatat hari ini.</div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
            <?php if (!empty($pengeluaran_hari_ini)): ?>
            <tfoot>
              <tr>
                <td colspan="2" class="pin-total-label">Total Pengeluaran Hari Ini</td>
                <td style="text-align: right;" class="pin-total-amount">
                    <?= Utility::rupiah($totalPengeluaranHariIni) ?>
                </td>
                <td></td>
              </tr>
            </tfoot>
            <?php endif; ?>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>