<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
use app\models\DtTransaksiSearch;
use app\models\SettingApp;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DtTransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Rekap Penjualan');
//$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('var ip_addr = "' . $setting->ip_address . '";');
$this->registerJs(<<<JS
    $(document).on("click", "#xls-rekap", function () {
        var url = $(this).attr('url');

        window.open(url);
    });

    $(document).on("click", "#send-email", function () {
        var url = $(this).attr('url');
        
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            success: function(v){
                var head = 'INFO';
                var msg = 'Laporan penjualan telah dikirim ke email '+v.email;

                //var socket = io.connect( 'http://'+ip_addr+':3000');
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
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Transaksi</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin([
                    'id'=>'grid-penjualan',
                    'timeout'=>false,
                    'enablePushState'=>false,
                    'clientOptions'=>['method'=>'GET']
                ]); ?>
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'showFooter' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id',
                        //'no_transaksi',
                        [
                            'label'=>'No Transaksi',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->no_transaksi;
                            },
                        ],
                        'kd_barang',
                        [
                            'label'=>'Tanggal Transaksi',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->header->tgl_bayar));
                            },
                        ],
                        [
                            'attribute'=>'nama_barang',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->barang->nama_barang;
                            },
                        ],
                        [
                            'attribute'=>'harga_satuan',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Utility::rupiah($model->harga_satuan);
                            },
                        ],
                        'qty',
                        [
                            'label'=>'Total',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Utility::rupiah($model->harga_satuan * $model->qty);
                            },
                            'footer' => '<strong>'.Utility::rupiah(DtTransaksiSearch::getTotal($dataProvider->models, 'harga_satuan','qty')).'</strong>',
                        ],
                        //'total_harga',
                        //'status_hapus',
                        //'tgl_hapus',

                        //['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>
