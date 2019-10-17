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
        <div class="col-lg-3">
            <div class="input-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>&nbsp;
                <button type="button" id="xls-rekap" class="btn btn-success" url="<?php echo Url::to(['transaksi/excelrekap']);?>">Download(.xls)</button>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
