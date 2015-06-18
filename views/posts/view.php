<?php

use deka6pb\autoparser\components\FileFileSystem;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model deka6pb\autoparser\models\Posts */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-view">
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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'text:ntext',
            'status',
            'tags',
            'sid',
            'provider',
            'created',
            'published',
            'url:url',
        ],
    ]) ?>

    <?php echo FileInput::widget([
        'name' => 'files',
        "disabled" => true,
        'options' => [
            'accept' => 'image/*',
            'multiple' => true,
        ],
        'pluginOptions' => [
            "allowedFileExtensions" => ["jpg", "png", "gif"],
            'initialPreview'=>$model->getHtmlImagesArray(),
            'overwriteInitial'=>false,
        ]
    ]) ?>


</div>
