<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-8">
    <div class="box box-danger">
	    <?php $form = ActiveForm::begin(); ?>
	    <div class="box-body">
		    <?= $form->field($model, 'nama_supplier')->textInput(['maxlength' => true]) ?>

		    <?= $form->field($model, 'alamat_supplier')->textInput(['maxlength' => true]) ?>

		    <?= $form->field($model, 'no_telp')->textInput(['maxlength' => true]) ?>

		    <?= $form->field($model, 'cp')->textInput(['maxlength' => true]) ?>

		    <div class="form-group">
		        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
		    </div>
	    </div>

	    <?php ActiveForm::end(); ?>
	</div>

</div>
