<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Providers */

$this->title = 'Create Providers';
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="providers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelOptions' => $modelOptions,
    ]) ?>

</div>
