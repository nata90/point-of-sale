<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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
                <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::class,[
                    'options'=>['class'=>'form-control'],
                ]) ?>
            </div>
        </div> 
        <div class="col-lg-2">
            <div class="input-group">
                 <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::class,[
                    'options'=>['class'=>'form-control'],
                ]) ?>
            </div>
        </div>            
    </div>
    <div class="box-footer">
        <div class="col-lg-6">
            <div class="input-group">
                <?= Html::submitButton(Yii::t('app', 'Cari'), ['class' => 'btn btn-primary']) ?>&nbsp;
                <button type="button" id="xls-rekap" class="btn btn-success" url="<?php echo Url::to(['transaksi/excelrekap']);?>">Download(.xls)</button>&nbsp;
                <button type="button" id="xls-rekap" class="btn btn-danger" url="<?php echo Url::to(['transaksi/reportpenjualan']);?>">Download(.PDF)</button>&nbsp;
                <button type="button" id="send-email" class="btn btn-warning" url="<?php echo Url::to(['transaksi/sendpenjualan']);?>">Send Email</button>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
