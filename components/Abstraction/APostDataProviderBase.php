<?php
namespace deka6pb\autoparser\components\Abstraction;

use deka6pb\autoparser\models\Files;
use deka6pb\autoparser\models\Gif;
use deka6pb\autoparser\models\Img;
use deka6pb\autoparser\models\Text;
use Yii;

abstract class APostDataProviderBase implements IPostDataProvider {

    public $_posts = [];

    public $postCount = 0;

    public $on = true;

    public $count = 10;

    public $homepage;

    public $providerName;

    public function __construct() {
        $this->count = (!empty(Yii::$app->controller->module->getMaxCountCollecting()))
            ? Yii::$app->controller->module->getMaxCountCollecting()
            : $this->count;
    }

    abstract function GetPosts();

    //region Types
    public function typeText($attributes) {
        $post = new Text();
        $post->setAttributes($attributes);
        $post->provider = $this->providerName;

        if (!$post->validate())
            return null;

        return $post;
    }

    public function typeImg($attributes, $urls) {
        $post = new Img();
        $post->setScenario($post::SCENARIO_INSERT);

        foreach ($urls AS $url) {
            $file = new Files();
            $file->url = $url;
            $file->name = basename($url);
            if ($file->validate())
                $post->uploadFiles[] = $file;
        }

        $post->setAttributes($attributes);
        $post->provider = $this->providerName;
        $post->status = Img::STATUS_NEW;
        $post->setAttribute('type', $post->type);

        if (!$post->validate()) {
            return null;
        }

        return $post;
    }

    public function typeGif($attributes, $urls) {
        $post = new Gif();

        $post->setScenario($post::SCENARIO_INSERT);

        foreach ($urls AS $url) {
            $file = new Files();
            $file->url = $url;
            $file->name = basename($url);
            if ($file->validate()) {
                $post->uploadFiles[] = $file;
            }
        }

        $post->setAttributes($attributes);
        $post->provider = $this->providerName;
        $post->status = Gif::STATUS_NEW;
        $post->setAttribute('type', $post->type);

        if (!$post->validate()) {
            return null;
        }

        return $post;
    }

    //endregion

    public static function getExtension($filename) {
        return substr(strrchr($filename, '.'), 1);
    }
}