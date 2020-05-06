<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HeaderPembelianSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="header-pembelian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_pembelian') ?>

    <?= $form->field($model, 'tgl_pembelian') ?>

    <?= $form->field($model, 'keterangan') ?>

    <?= $form->field($model, 'total_pembelian') ?>

    <?= $form->field($model, 'status_delete') ?>

    <?php // echo $form->field($model, 'tgl_delete') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
