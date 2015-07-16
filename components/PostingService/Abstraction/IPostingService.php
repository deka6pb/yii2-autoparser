<?php
namespace deka6pb\autoparser\components\PostingService\Abstraction;

interface IPostingService {

    public function init();

    public function setCount($value);

    public function initConsumers();

    public function initPostCollection();

    public function run();

    public function afterRun();

    public function getPostCollection();

    public function setConsumers();
}