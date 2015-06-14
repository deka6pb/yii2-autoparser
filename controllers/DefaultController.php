<?php

namespace deka6pb\autoparser\controllers;

use deka6pb\autoparser\components\CollectorService;
use deka6pb\autoparser\components\PostingService;
use deka6pb\autoparser\models\Posts;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public $layout='main';

    public function actionIndex() {
        $this->redirect("/autoparser/posts");
    }

    public function actionRun() {
        if(Posts::countNewPosts() < $this->module->getMaxCountPosting()) {
            $collector = new CollectorService();
            $collector->run();
        }

        $posting = new PostingService();
        $posting->run();
        $this->redirect("index");
    }
}
