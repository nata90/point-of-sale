<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Utility;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = Yii::t('app', 'Set Modal Awal');
$this->registerJs('var url = "' . Url::to(['/transaksi/simpanmodal']) . '";');
$this->registerJs(<<<JS
	$(document).on("click", "#simpan-modal", function () {
		var keterangan = $('#modal-keterangan').val();
		var modal = $('#modal-modal_awal').val();

		$.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'keterangan':keterangan,
                'modal':modal
            },
            success: function(v){
                if(v.success == 1){
                    location.reload();
                }else{
                    Swal.fire({
                        title: v.msg,
                        icon: "error"
                    });
                }
                
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
            },
        });
	});
JS
);
?>

<div class="row">

    <div class="col-md-6">
        <div class="callout callout-danger">
            <p class="callout-description">INPUT MODAL AWAL</p>
        </div>
	    <div class="box box-danger">
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
	                
	            <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>

	            <?= $form->field($model, 'modal_awal')->textInput(['maxlength' => true, 'type'=>'number']) ?>


	                <div class="box-footer">
	                    <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'simpan-modal']) ?>
	                </div>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>

	</div>
    <div class="col-md-6">
        <div class="callout callout-danger">
            <p class="callout-description">MODAL AWAL HARI INI</p>
        </div>
	    <div class="box box-danger">
        <table class="table table-striped">
            <tbody>
            <tr>
                <th>Deskripsi</th>
                <th>Rupiah</th>
            </tr>
            <?php if($modal_hari_ini){   
            ?>
                    <tr>
                        <td><?php echo $modal_hari_ini->keterangan;?></td>
                        <td>
                            <?php echo Utility::rupiah($modal_hari_ini->modal_awal);?>
                        </td>
                    </tr>
            <?php 
                
            } ?>
            </tbody></table>
	    </div>

	</div>

</div>