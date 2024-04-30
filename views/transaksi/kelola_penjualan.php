<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
use app\models\HdTransaksi;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kelola Penjualan');
$this->registerJs(<<<JS
    $(document).on("click", ".cetak-nota", function () {
        var link = $(this).attr('link');
        $.ajax({
            type: 'get',
            url: link,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
                $('#popup-namabarang').focus();
            },
        });
    });
JS
);
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Kelola Penjualan</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin([
                    'id'=>'grid-penjualan',
                    'timeout'=>false,
                    'enablePushState'=>false,
                    'clientOptions'=>['method'=>'GET']
                ]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => false,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'label'=>'',
                            'format'=>'raw',
                            'value'=>function($model){
                                return '<button type="submit" class="btn btn-success btn-sm cetak-nota" link="'.Url::to(['transaksi/cetaknota', 'id'=>$model->no_transaksi]).'">CETAK NOTA</button>';
                            },
                            'contentOptions' => ['style' => 'text-align: center;'],
                        ],
                        'no_transaksi',
                        [
                            'label'=>'Tanggal Bayar',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tgl_bayar));
                            },
                        ],
                        [
                            'label'=>'Item',
                            'format'=>'raw',
                            'value'=>function($model){
                                return HdTransaksi::getListObat($model->no_transaksi);
                            },
                        ],
                        [
                            'label'=>'Total',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Utility::rupiah($model->total);
                            },
                        ],
                        [
                            'label'=>'Total',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Utility::rupiah($model->jumlah_bayar);
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>