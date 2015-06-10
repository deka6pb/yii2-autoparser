<?php

use deka6pb\autoparser\models\Posts;
use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel deka6pb\autoparser\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
                if($model->status == $model::STATUS_PUBLISHED) {
                    return ['class' => 'success'];
                } elseif($model->status == $model::STATUS_STOPPED) {
                    return ['class' => 'danger'];
                }
            },
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['width' => '30']
            ],
            [
                'attribute' => 'type',
                'options' => ['width' => '30']
            ],
            'text:ntext',
            [
                'attribute' => 'status',
                'format' => 'html',
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'status',
                        Posts::getStatusAliases(),
                        ['class' => 'form-control']
                    ),
                'options' => ['width' => '30'],
                'value' => function($model) {
                    return $model->getStatus();
                }
            ],
            [
                'attribute' => 'tags',
                'options' => ['width' => '35']
            ],
            [
                'attribute' => 'sid',
                'options' => ['width' => '35']
            ],
            [
                'attribute' => 'provider',
                'options' => ['width' => '35']
            ],
            [
                'attribute' => 'created',
                'format' =>  ['date', 'php:Y-m-d H:i'],
                'options' => ['width' => '200'],
                'filter' => DatePicker::widget([
                        'model' =>$searchModel,
                        'attribute' => 'created',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ])
            ],
            [
                'attribute' => 'published',
                'format' =>  ['date', 'php:Y-m-d H:i'],
                'options' => ['width' => '200'],
                'filter' => DatePicker::widget([
                        'model' =>$searchModel,
                        'attribute' => 'published',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ])
            ],
            [
                'attribute' => 'url',
                'format' =>  'raw',
                'options' => ['width' => '80'],
                'value' => function($model) {
                        return Html::a('Go Link', $model->url, ['title' => $model->url]);
                    }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
