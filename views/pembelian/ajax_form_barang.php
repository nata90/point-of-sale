<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */
/* @var $form yii\widgets\ActiveForm */
?>


        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <?= $form->field($model, 'kd_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'nama_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'aktif')->checkBox() ?>

            <div class="form-group">
                <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    