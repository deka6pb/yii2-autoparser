<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumer */

$this->title = 'Create Consumer';
$this->params['breadcrumbs'][] = ['label' => 'Consumers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
