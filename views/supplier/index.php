<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Supplier');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Supplier</h3>
            </div>
            <div class="box-body">
                <p>
                    <?= Html::a(Yii::t('app', 'Tambah Supplier'), ['create'], ['class' => 'btn btn-success']) ?>
                </p>

                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                       // 'id',
                        'nama_supplier',
                        'alamat_supplier',
                        'no_telp',
                        'cp',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update}&nbsp{delete}',
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
                
            </div>
        </div>
        

    </div>
</div>
