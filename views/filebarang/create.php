<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FileBarang */

$this->title = Yii::t('app', 'Create File Barang');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'File Barangs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
