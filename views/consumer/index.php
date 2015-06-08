<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel deka6pb\autoparser\models\Consumers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consumers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consumer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Consumer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        /*'filterModel' => $searchModel,*/
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'class',
            'APP_ID',
            'APP_SECRET',
            'ACCESS_TOKEN',
            // 'CODE',
            // 'GROUP_ID',
            // 'ALBUM_ID',
            // 'on',

            [
                'class' => \yii\grid\ActionColumn::className(),
                'urlCreator'=>function($action, $model){
                    return ($action=='view')?[$action,'id'=>$model['name']]
                        :[$action,'id'=>$model['name']];
                },
                'template'=>'{update}  {delete}',
            ]
        ],
    ]); ?>

</div>
