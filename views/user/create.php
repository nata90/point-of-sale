<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppUser */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'App Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'listData'=>$listData
    ]) ?>

</div>
