<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HeaderPembelian */

$this->title = Yii::t('app', 'Update Header Pembelian: {name}', [
    'name' => $model->id_pembelian,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Header Pembelians'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pembelian, 'url' => ['view', 'id' => $model->id_pembelian]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="header-pembelian-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
