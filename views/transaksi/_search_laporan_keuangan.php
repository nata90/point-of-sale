<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\DtTransaksiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => ['data-pjax' => 1, 'style' => 'display:contents;'],
]); ?>

<div class="pin-filter-group">
    <?php echo Html::label('Tanggal Transaksi', 'tgl-transaksi', ['class' => 'pin-filter-label']); ?>
    <?php echo DatePicker::widget([
        'name'    => 'start_date',
        'value'   => date('Y-m-d'),
        'options' => ['class' => 'pin-filter-input', 'id' => 'tgl-transaksi'],
    ]); ?>
</div>

<?= Html::button('<i class="fa fa-search"></i> ' . Yii::t('app', 'Cari'), [
    'class' => 'pin-btn-primary',
    'id'    => 'search-laporan-keuangan',
]) ?>

<?php ActiveForm::end(); ?>
