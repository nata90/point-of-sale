<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DtTransaksi */

$this->title = Yii::t('app', 'Create Dt Transaksi');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dt Transaksis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dt-transaksi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
