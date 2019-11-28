<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\HeaderPembelian */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-4">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                Pembelian
            </h3>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <?= $form->field($model, 'tgl_pembelian')->widget(\yii\jui\DatePicker::class,[
                    'options'=>['class'=>'form-control'],
                ]) ?>

            <?= $form->field($model, 'kd_barang')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'nama_barang')->widget(\yii\jui\AutoComplete::classname(), [
                'options' => ['class' => 'form-control input-sm'],
                'clientOptions' => [
                    'source' => $data,
                    'minLength'=>'2', 
                    'autoFill'=>true,
                    'select' => new JsExpression("function( event, ui ) {
                        $('#field-kode-barang').val(ui.item.id);
                     }")
                ],
            ]) ?>
           

            <?= $form->field($model, 'jumlah')->textInput() ?>

            <?= $form->field($model, 'harga_beli')->textInput() ?>

            <?= $form->field($model, 'harga_jual')->textInput() ?>

        </div>

        <div class="box-footer">
            <?= Html::button(Yii::t('app', 'Tambah'), ['class' => 'btn btn-success pull-right']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
<div class="col-md-8">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                Item Pembelian
            </h3>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
