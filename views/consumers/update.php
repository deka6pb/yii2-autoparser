<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumers */

$this->title = 'Update Consumers: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Consumers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="consumers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'        => $model,
        'modelOptions' => $modelOptions
    ]) ?>

</div>
