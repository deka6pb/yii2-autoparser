<?php
namespace deka6pb\autoparser\components;

use deka6pb\autoparser\components\Abstraction\ICollectorService;
use deka6pb\autoparser\components\Abstraction\IPostDataProvider;
use deka6pb\autoparser\models\Posts;
use Yii;

class CollectorService implements ICollectorService {

    private $_providers = [];
    private $_enabledProviders = [];
    private $_postCollection = [];

    public function __construct($providers) {
        $this->setProviders($providers);
        $this->init();
    }

    function init() {
        foreach($this->_providers AS $provider) {
            $component = Yii::createObject($provider);
            $component->init();

            if(!($component instanceof IPostDataProvider)) {
                throw new \yii\base\Exception('This provider does not belong to the interface IPostDataProvider', 400);
            }

            if($component->on != false)
                $this->_enabledProviders[] = $component;
        }
    }

    function run() {
        foreach($this->_enabledProviders AS $provider) {
            $postCount = 0;
            foreach($provider->GetPosts() AS $post) {
                if($postCount >= $provider->count)
                    break;
                if($post instanceof Posts)
                    if($post->save()) {
                        $this->_postCollection[] = $post;
                        $postCount++;
                    }
            }
        }
    }

    public function getPostCollection() {
        return $this->_postCollection;
    }

    private function setProviders($providers) {
        if(is_array($providers))
            $this->_providers = $providers;
    }
}