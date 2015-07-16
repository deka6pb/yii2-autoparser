<?php

namespace deka6pb\autoparser\controllers;

use deka6pb\autoparser\models\Consumers;
use deka6pb\autoparser\models\OptionsConsumer;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\BaseJson;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ConsumersController implements the CRUD actions for Consumers model.
 */
class ConsumersController extends Controller {
    public $layout = 'main';
    public $homeUrl = '/autoparser';

    public function behaviors() {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }

    /**
     * Lists all Consumers models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Consumers::find()
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Consumers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $modelOptions = new OptionsConsumer();

        $modelOptions->attributes = $model->getOptionsToArray();

        return $this->render('view', [
            'model'        => $model,
            'modelOptions' => $modelOptions
        ]);
    }

    /**
     * Creates a new Consumers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Consumers();
        $modelOptions = new OptionsConsumer();

        if ($modelOptions->load(Yii::$app->request->post()) && $modelOptions->validate()) {
            $model->name = $modelOptions->name;
            $model->options = BaseJson::encode($modelOptions->attributes);
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model'        => $model,
                'modelOptions' => $modelOptions
            ]);
        }
    }

    /**
     * Updates an existing Consumers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $modelOptions = new OptionsConsumer();

        $modelOptions->attributes = $model->getOptionsToArray();

        if ($modelOptions->load(Yii::$app->request->post()) && $modelOptions->validate()) {
            $model->name = $modelOptions->name;
            $model->options = BaseJson::encode($modelOptions->attributes);
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model'        => $model,
                'modelOptions' => $modelOptions
            ]);
        }
    }

    /**
     * Deletes an existing Consumers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Consumers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Consumers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Consumers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
