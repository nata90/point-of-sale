<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AppUserGroup */

$this->title = Yii::t('app', 'Update App User Group: {name}', [
    'name' => $model->id_group,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'App User Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_group, 'url' => ['view', 'id' => $model->id_group]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="app-user-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
