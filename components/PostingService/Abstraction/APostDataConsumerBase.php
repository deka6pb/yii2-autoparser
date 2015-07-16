<?php
namespace deka6pb\autoparser\components\PostingService\Abstraction;

use deka6pb\autoparser\components\Abstraction\IItemType;
use deka6pb\autoparser\components\PostingException;
use deka6pb\autoparser\models\Posts;
use yii\base\ErrorException;

abstract class APostDataConsumerBase implements IPostDataConsumer, IItemType {

    public $on = true;

    abstract public function init();

    public function SendPosts($data) {
        try {
            foreach ($data AS $post) {
                if (!($post instanceof Posts)) {
                    throw new ErrorException('This post does not belong to the class Media', 400);
                }

                switch ($post->type) {
                    case self::TYPE_TEXT:
                        $this->SendText($post);
                        break;
                    case self::TYPE_GIF:
                        $this->SendGif($post);
                        break;
                    case self::TYPE_IMG:
                        $this->SendImg($post);
                        break;
                }
            }
        } catch (ErrorException $e) {
            throw new PostingException($e->getMessage(), $e->getCode(), $post);
        }
    }

    /**
     * Send text type post
     *
     * @param $post
     * @return mixed
     */
    abstract public function SendText($post);

    /**
     * Send Gif type post
     *
     * @param $post
     * @return mixed
     */
    abstract public function SendGif($post);

    /**
     * Send Img type post
     *
     * @param $post
     * @return mixed
     */
    abstract public function SendImg($post);
}