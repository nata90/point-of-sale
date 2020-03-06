<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
use app\models\HdTransaksi;
use app\models\HeaderPembelianSearch;
use app\models\HeaderPembelian;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kelola Pembelian');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Kelola Pembelian</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin([
                    'id'=>'grid-penjualan',
                    'timeout'=>false,
                    'enablePushState'=>false,
                    'clientOptions'=>['method'=>'GET']
                ]); ?>
                <?php echo $this->render('_search_pembelian', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => false,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'label'=>'Tanggal Pembelian',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tgl_pembelian));
                            },
                        ],
                        //'keterangan',
                        [
                            'label'=>'Supplier',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->supplier->nama_supplier;
                            },
                        ],
                        [
                            'label'=>'Item Pembelian',
                            'format'=>'raw',
                            'value'=>function($model){
                                return HeaderPembelian::getItemPembelian($model->id_pembelian);
                            },
                        ],
                                             

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{delete}',
                            'urlCreator' => function( $action, $model, $key, $index ){
                                if ($action == "delete") {
                                    return Url::to(['deletepembelian', 'id' => $key]);
                                }

                            }
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>