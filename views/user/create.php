<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = Yii::t('app', 'Buat User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kelola User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'listData' => $listData ?? null,
    ]) ?>

</div>
