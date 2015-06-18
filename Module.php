<?php

namespace deka6pb\autoparser;

class Module extends \yii\base\Module {
    public $controllerNamespace = 'deka6pb\autoparser\controllers';
    public $tmpDir = "@webroot/images";
    public $uploadedUrl = "@web/images";
    public $postingCount = 1;
    public $collectionCount = 1;
    private $_providers = [];
    private $_consumers = [];

    private $defaultConsumersClasses = [
        'deka6pb\autoparser\components\Concrete\Consumers\VkConsumer' => 'Vk'
    ];
    public $userConsumersClasses = [];

    private $defaultProvidersClasses = [];
    public $userProvidersClasses = [];

    public $collectorService;
    public $postingService;

    public function init() {
        parent::init();

        $this->_providers = $this->mergeProviders();
        $this->_consumers = $this->mergeConsumers();

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

    public function getProviders() {
        return $this->_providers;
    }

    public function getConsumers() {
        return $this->_consumers;
    }

    public function getTmpDir() {
        return $this->tmpDir;
    }


    public function getUploadedUrl() {
        return $this->uploadedUrl;
    }

    public function getUserConsumersClasses() {
        return $this->userConsumersClasses;
    }

    public function getDefaultConsumersClasses() {
        return $this->defaultConsumersClasses;
    }

    public function getUserProvidersClasses() {
        return $this->userProvidersClasses;
    }

    public function getDefaultProvidersClasses() {
        return $this->defaultProvidersClasses;
    }

    private function mergeProviders() {
        return array_merge($this->defaultProvidersClasses, $this->userProvidersClasses);
    }

    private function mergeConsumers() {
        return array_merge($this->defaultConsumersClasses, $this->userConsumersClasses);
    }
}
