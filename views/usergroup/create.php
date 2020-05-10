<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppUserGroup */

$this->title = Yii::t('app', 'Create App User Group');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'App User Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-user-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
