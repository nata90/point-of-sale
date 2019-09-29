<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DtTransaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dt-transaksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no_transaksi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kd_barang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'harga_satuan')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'total_harga')->textInput() ?>

    <?= $form->field($model, 'status_hapus')->textInput() ?>

    <?= $form->field($model, 'tgl_hapus')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
