<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Barang');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Kelola Barang</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Semua</a></li>
                        <li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Stok Habis</a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <p>
                            <?= Html::a(Yii::t('app', 'Tambah Barang'), ['create'], ['class' => 'btn btn-success']) ?>
                        </p>

                        <?php Pjax::begin([
                            'id'=>'grid-barang',
                            'timeout'=>false,
                            'enablePushState'=>false,
                            'clientOptions'=>['method'=>'GET']
                        ]); ?>

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'id',
                                'kd_barang',
                                'nama_barang',
                                'lokasi',
                                'stok',
                                [
                                    'label'=>'Harga Beli',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return Utility::rupiah($model->harga_beli);
                                    },
                                ],
                                [
                                    'label'=>'Harga Jual',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return Utility::rupiah($model->harga_jual);
                                    },
                                ],
                                //'harga_jual',
                                //'aktif',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'=>'{update}&nbsp{delete}',
                                    //'buttons'  =>
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>
                    </div>
                    <div class="tab-pane" id="tab_2">
                        <?php Pjax::begin([
                            'id'=>'grid-barang-habis',
                            'timeout'=>false,
                            'enablePushState'=>false,
                            'clientOptions'=>['method'=>'GET']
                        ]); ?>

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider2,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'id',
                                'kd_barang',
                                'nama_barang',
                                'lokasi',
                                'stok',
                                [
                                    'label'=>'Harga Beli',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return Utility::rupiah($model->harga_beli);
                                    },
                                ],
                                [
                                    'label'=>'Harga Jual',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return Utility::rupiah($model->harga_jual);
                                    },
                                ],
                                //'harga_jual',
                                //'aktif',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'=>'{update}&nbsp{delete}',
                                    //'buttons'  =>
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>
                    </div>
                </div>
                
            </div>
        </div>
        

    </div>
</div>

