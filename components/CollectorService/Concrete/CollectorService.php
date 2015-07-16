<?php
namespace deka6pb\autoparser\components\CollectorService\Concrete;

use deka6pb\autoparser\components\CollectorService\Abstraction\ACollectorService;
use deka6pb\autoparser\models\Posts;
use Yii;

class CollectorService extends  ACollectorService {
    public function __construct() {
        if (Posts::countNewPosts() < Yii::$app->controller->module->getMaxCountPosting()) {
            $this->setProviders();
            $this->init();
            $this->run();
        }
    }
}