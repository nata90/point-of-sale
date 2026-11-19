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
    <?= $form->field($model, 'kd_barang')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'harga_jual')->textInput(['type'=>'number']) ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'UPDATE'), ['class' => 'btn btn-success', 'id'=>'update-button', 'link'=>Url::to(['filebarang/simpanupdateharga'])]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
