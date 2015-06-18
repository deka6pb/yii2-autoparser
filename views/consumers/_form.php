<?php

use deka6pb\autoparser\models\Consumers;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consumers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelOptions, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'class')->dropDownList(
        Consumers::getConsumers()
    ); ?>

    <?= $form->field($modelOptions, 'APP_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'APP_SECRET')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'ACCESS_TOKEN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'CODE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'GROUP_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'ALBUM_ID')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'on')->checkbox(['value' => 1]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
