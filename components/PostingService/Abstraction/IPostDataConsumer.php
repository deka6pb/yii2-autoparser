<?php
namespace deka6pb\autoparser\components\PostingService\Abstraction;

interface IPostDataConsumer {
    public function init();

    public function SendPosts($data);

    public function SendText($post);

    public function SendGif($post);

    public function SendImg($post);
}