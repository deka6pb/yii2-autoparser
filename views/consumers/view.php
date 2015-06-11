<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Consumers */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Consumers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="consumers-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($modelOptions, 'name')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'class')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'APP_ID')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'APP_SECRET')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'ACCESS_TOKEN')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'CODE')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'GROUP_ID')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'ALBUM_ID')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?= $form->field($modelOptions, 'on')->checkbox(['value' => 1, 'readonly' => true]) ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
