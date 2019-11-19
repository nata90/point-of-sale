<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HeaderPembelian */

$this->title = Yii::t('app', 'Create Header Pembelian');
/*$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Header Pembelians'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
