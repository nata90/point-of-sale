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
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-danger">
                <p class="callout-description">BUAT BARANG BARU</p>
            </div>
        </div>
    </div>
</div>
<div class="box-footer">

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'kd_barang')->textInput(['maxlength' => true, 'value'=>$kodebarang]);?>
        </div>
        <div class="col-md-4">
            <?= Html::button(Yii::t('app', 'BUAT KODE'), ['class' => 'btn btn-success generate-code', 'style'=>'margin-top:25px;']) ?>
        </div>
    </div>
    

    <?= $form->field($model, 'nama_barang')->textInput(['maxlength' => true, 'id'=>'popup-namabarang']) ?>

    <?= $form->field($model, 'harga_jual')->textInput(['type'=>'number']) ?>

    <div class="form-group">
        <?= Html::button(Yii::t('app', 'TAMBAH BARANG'), ['class' => 'btn btn-success', 'id'=>'create-item-button', 'link'=>Url::to(['filebarang/popupcreatebarang'])]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
