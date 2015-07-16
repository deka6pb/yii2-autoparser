<?php
namespace deka6pb\autoparser\components\CollectorService\Abstraction;

interface ICollectorService {

    public function init();

    public function run();

    public function getPostCollection();

    public function setProviders();
}