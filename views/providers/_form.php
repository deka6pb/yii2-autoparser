<?php

use deka6pb\autoparser\models\Providers;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Providers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="providers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelOptions, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'class')->dropDownList(
        Providers::getProviders()
    );?>

    <?= $form->field($modelOptions, 'OAUTH_TOKEN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'homepage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelOptions, 'on')->checkbox(['value' => 1]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
