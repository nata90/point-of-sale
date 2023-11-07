<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Utility;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = Yii::t('app', 'Pengeluaran');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('var url = "' . Url::to(['/transaksi/simpanpengeluaran']) . '";');
$this->registerJs(<<<JS
	$(document).on("click", "#simpan-pengeluaran", function () {
		var deskripsi = $('#pengeluaran-deskripsi').val();
		var nilai = $('#pengeluaran-nilai').val();

		$.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'deskripsi':deskripsi,
                'nilai':nilai
            },
            success: function(v){
                if(v.success == 1){
                    location.reload();
                }else{
                    
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
            <p class="callout-description">INPUT PENGELUARAN</p>
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
	                
	            <?= $form->field($model, 'deskripsi')->textInput(['maxlength' => true]) ?>

	            <?= $form->field($model, 'nilai')->textInput(['maxlength' => true, 'type'=>'number']) ?>


	                <div class="box-footer">
	                    <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'simpan-pengeluaran']) ?>
	                </div>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>

	</div>
    <div class="col-md-6">
        <div class="callout callout-danger">
            <p class="callout-description">PENGELUARAN HARI INI</p>
        </div>
	    <div class="box box-danger">
        <table class="table table-striped">
            <tbody>
            <tr>
                <th style="width: 10px">#</th>
                <th>Deskripsi</th>
                <th>Rupiah</th>
                <th style="width: 40px">Label</th>
            </tr>
            <?php if($pengeluaran_hari_ini){ 
                $number = 1;
                foreach($pengeluaran_hari_ini as $val){    
            ?>
                    <tr>
                        <td><?php echo $number;?>.</td>
                        <td><?php echo $val['deskripsi'];?></td>
                        <td>
                            <?php echo Utility::rupiah($val['nilai']);?>
                        </td>
                        <td><span class="badge bg-red">Hapus</span></td>
                    </tr>
            <?php 
                    $number++;
                }
            } ?>
            </tbody></table>
	    </div>

	</div>

</div>