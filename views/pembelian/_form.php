<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HeaderPembelian */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                Buat User
            </h3>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group">
                        <?= $form->field($model, 'tgl_pembelian')->textInput() ?>
                    </div>
                </div>  
                <div class="col-lg-8">
                    <div class="input-group">
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>
                    </div>
                </div> 
            </div>
            
        </div>

        

        <div class="box-footer">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>



</div>
