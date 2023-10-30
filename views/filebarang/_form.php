<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8">
    <div class="box box-danger">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'kd_barang')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= Html::button(Yii::t('app', 'BUAT KODE'), ['class' => 'btn btn-success generate-code', 'style'=>'margin-top:25px;']) ?>
                </div>
            </div>
            

            <?= $form->field($model, 'nama_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true, 'value'=>'-']) ?>

            <?= $form->field($model, 'harga_beli')->textInput() ?>

            <?= $form->field($model, 'harga_jual')->textInput() ?>

            <?= $form->field($model, 'aktif')->checkBox() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
