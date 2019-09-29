<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DtTransaksiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Rekap Penjualan');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Transaksi</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id',
                        //'no_transaksi',
                        'kd_barang',
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
