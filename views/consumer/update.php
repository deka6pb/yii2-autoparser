<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumer */

$this->title = 'Update Consumer: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Consumers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="consumer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
