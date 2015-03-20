<?php

namespace deka6pb\autoparser;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'deka6pb\autoparser\controllers';
    public $tmpDir = "@runtime/tmp";
    public $postingCount = 1;
    public $collectionCount = 1;
    public $consumers = [];
    public $providers = [];

    public $collectorService;
    public $postingService;

    public function init()
    {
        parent::init();

        $handler = new components\ApiErrorHandler;
        \Yii::$app->set('errorHandler', $handler);
        $handler->register();
    }

    public function getMaxCountPosting() {
        return $this->postingCount;
    }

    public function getMaxCountCollecting() {
        return $this->collectionCount;
    }

    public function getConsumers() {
        return $this->consumers;
    }

    public function getProviders() {
        return $this->providers;
    }

    public function getTmpDir() {
        return $this->tmpDir;
    }
}
