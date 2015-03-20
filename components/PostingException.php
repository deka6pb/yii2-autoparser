<?php
namespace deka6pb\autoparser\components;

use deka6pb\autoparser\models\Posts;

class PostingException extends \yii\base\ErrorException {
    public $errorMessage;
    public $errorCode;
    public $data;

    public function __construct($errorMessage, $errorCode, Posts $post) {
        $post->setScenario($post::SCENARIO_UPDATE);
        $post->status = Posts::STATUS_STOPPED;
        $post->update();
    }
}