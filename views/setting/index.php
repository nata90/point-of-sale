<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\Utility;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = Yii::t('app', 'Setting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('var ip = "' . $model->ip_address . '";');
$this->registerJs('var url = "' . Url::to(['/setting/simpansetting']) . '";');
$this->registerJs(<<<JS
	$(document).on("click", "#simpan-setting", function () {
		var nama_app = $('#settingapp-app_name').val();
		var email = $('#settingapp-email').val();
		var ip_addr = $('#settingapp-ip_address').val();

		$.ajax({
            type: 'post',
            url: url,
            dataType: 'html',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'nama_app':nama_app,
                'email':email,
                'ip_addr':ip_addr
            },
            success: function(v){
                var head = 'INFO';
                var msg = 'Setting baru berhasil disimpan';

                //var socket = io.connect( 'http://127.0.0.1:3000');
                //socket.emit('notif',{name: head, message: msg});

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

    <div class="col-md-8">
	    <div class="box box-danger">
	        <div class="box-header with-border">
	            <h3 class="box-title">
	                Setting Aplikasi
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
	               
	            <?= $form->field($model, 'app_name')->textInput(['maxlength' => true]) ?>
	                
	            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

	            <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>


	                <div class="box-footer">
	                    <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'simpan-setting']) ?>
	                </div>
	            </div>
	        <?php ActiveForm::end(); ?>
	    </div>

	</div>

</div>