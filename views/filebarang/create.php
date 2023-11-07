<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */

$this->title = Yii::t('app', 'Buat Barang Baru');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'File Barangs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('var url = "'.Url::to(['filebarang/getkodebarang']).'";');
$this->registerJs(<<<JS
    $('#filebarang-kd_barang').focus();

    $(document).on("click", ".generate-code", function () {
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            success: function(v){
                $('#filebarang-kd_barang').val(v.kode);
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
                $('#filebarang-nama_barang').focus();
            },
        });
    });
JS
);
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
