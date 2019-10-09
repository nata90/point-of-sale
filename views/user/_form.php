<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                Buat User
            </h3>
        </div>
        <?php $form = ActiveForm::begin([
            
            'options'=>[
                'layout' => 'horizontal',
                'class'=>'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => '<label class="col-sm-2 control-label">{label}</label><div class="col-sm-10">{input}</div>',
            ]
        ]); ?>
            <div class="box-body">
               
            <?= $form->field($model, 'username')->passwordInput(['maxlength' => true]) ?>
                
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'id_group')->dropDownList(
                $listData,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'aktif')->checkBox() ?>


                <div class="box-footer">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
