<?php

use deka6pb\autoparser\models\Posts;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Posts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(
        Posts::getTypesAliases(),
        ['prompt'=>'']
    );?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'status')->dropDownList(
            Posts::getStatusAliases(),
            ['prompt'=>'']
        );?>
    <?php endif; ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true,]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
