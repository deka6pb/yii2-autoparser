<?php
namespace deka6pb\autoparser\components;

use deka6pb\autoparser\components\Abstraction\IItemStatus;
use deka6pb\autoparser\components\Abstraction\IPostDataConsumer;
use deka6pb\autoparser\components\Abstraction\IPostingService;
use deka6pb\autoparser\components\TItemStatus;
use deka6pb\autoparser\models\Consumers;
use deka6pb\autoparser\models\Posts;
use Yii;

class PostingService implements IPostingService, IItemStatus {

    use TItemStatus;

    private $_consumers = [];
    private $_enabledConsumers = [];
    private $_postCollection = [];
    private $_count;

    public function __construct() {
        $this->setConsumers();
        $this->init();
    }

    function init() {
        $this->_count = (!empty(Yii::$app->controller->module->getMaxCountPosting())) ? Yii::$app->controller->module->getMaxCountPosting() : null;
        $this->initConsumers();
        $this->initPostCollection();
    }

    public function setCount($value) {
        $this->_count = $value;
    }

    function initConsumers() {
        foreach($this->_consumers AS $consumer) {
            $component = Yii::createObject($consumer);
            if(!($component instanceof IPostDataConsumer)) {
                throw new \yii\base\Exception('This provider does not belong to the interface IPostDataProvider', 400);
            }

            if((bool)$component->on != false) {
                $component->init();
                $this->_enabledConsumers[] = $component;
            }
        }
    }

    function initPostCollection() {
        $model = new Posts();
        $this->_postCollection = $model->getNewPosts($this->_count);
    }

    function run() {
        foreach($this->_enabledConsumers AS $consumer) {
            //TODO доработать приглашение юзеров
            //$consumer->SendInvites();
            if(!empty($this->_postCollection)) {
                $consumer->SendPosts($this->_postCollection);
            }
        }

        $this->afterRun();
    }

    function afterRun() {
        foreach($this->_postCollection AS $post) {
            $post->setPublished();
            foreach($post->files AS $file) {
                $file->deleteFile();
            }
        }
    }

    function getPostCollection() {
        return $this->_postCollection;
    }

    private function setConsumers() {
        $consumers = Consumers::find()->all();

        foreach($consumers AS $objConsumer) {
            $this->_consumers[] = $objConsumer->getOptionsToArray();
        }
    }
}