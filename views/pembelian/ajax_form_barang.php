<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */
/* @var $form yii\widgets\ActiveForm */
?>


        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <?= $form->field($model, 'kd_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'nama_barang')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'add-barang', 'url'=>Url::to(['pembelian/simpanbarang'])]) ?>
                <?= Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-info', 'id'=>'close', 'onclick' => '(function ( $event ) { $("#modal").modal("hide") })();']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    