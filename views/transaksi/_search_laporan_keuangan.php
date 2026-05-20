<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\DtTransaksiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-transaksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="box-body">
        <div class="col-lg-2">
            <div class="input-group">
                <?php echo Html::label('Tanggal Transaksi', 'start_date'); ?>
                <?php echo DatePicker::widget([
                    'name' => 'start_date',
                    'value' => date('Y-m-d'),
                    'options' => ['class' => 'form-control', 'id'=>'tgl-transaksi'],
                ]); ?>
            </div>
        </div> 
                  
    </div>
    <div class="box-footer">
        <div class="col-lg-6">
            <div class="input-group">
                <?= Html::button(Yii::t('app', 'Cari'), ['class' => 'btn btn-primary', 'id'=>'search-laporan-keuangan']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
