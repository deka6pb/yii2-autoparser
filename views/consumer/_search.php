<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consumer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'class') ?>

    <?= $form->field($model, 'APP_ID') ?>

    <?= $form->field($model, 'APP_SECRET') ?>

    <?= $form->field($model, 'ACCESS_TOKEN') ?>

    <?php // echo $form->field($model, 'CODE') ?>

    <?php // echo $form->field($model, 'GROUP_ID') ?>

    <?php // echo $form->field($model, 'ALBUM_ID') ?>

    <?php // echo $form->field($model, 'on') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
