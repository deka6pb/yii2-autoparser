<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\providers */

$this->title = 'Update Providers: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="providers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'        => $model,
        'modelOptions' => $modelOptions,
    ]) ?>

</div>
