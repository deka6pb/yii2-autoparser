<?php

namespace deka6pb\autoparser\controllers;

use deka6pb\autoparser\components\CollectorService\Concrete\CollectorService;
use deka6pb\autoparser\components\PostingService\Concrete\PostingService;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller {
    public $layout = 'main';

    public function actionIndex() {
        $this->redirect("/autoparser/posts");
    }

    public function actionRun() {
        new CollectorService();
        new PostingService();

        $this->redirect("index");
    }
}
