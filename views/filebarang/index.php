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

                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}&nbsp;{delete}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>

