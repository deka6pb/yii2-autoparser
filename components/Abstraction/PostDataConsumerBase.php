<?php
namespace deka6pb\autoparser\components\Abstraction;

abstract class PostDataConsumerBase implements IPostDataConsumer, IItemType {

    public $on = true;

    abstract function init();

    abstract function SendPosts($data);
}