<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\Utility;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Rekap Transaksi');
//$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(<<<JS

    $(document).on("click", "#modalButton", function () {
        var url = $(this).attr('url');

        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function(v){
                $('div.modal-header h4').html(v.header);
                $('#modal').modal('show').find('#modalContent').html(v.data);
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

                <?php Pjax::begin(); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'no_transaksi',
                        [
                            'attribute'=>'tgl_bayar',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tgl_bayar));
                            },
                        ],
                        [
                            'attribute'=>'total',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Utility::rupiah($model->total);
                            },
                        ],
                        [
                            'label'=>'Detail',
                            'format'=>'raw',
                            'value'=>function($model){
                                return '<a url="'.Url::to(['site/detailtransaksi', 'id'=>$model->id]).'" href="#" id="modalButton"><span class="label label-info">DETAIL</span></a>';
                            }
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>

