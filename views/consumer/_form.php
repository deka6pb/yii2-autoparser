<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consumer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'APP_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'APP_SECRET')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ACCESS_TOKEN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'GROUP_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ALBUM_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'on')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
