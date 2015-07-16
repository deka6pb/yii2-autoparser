<?php
namespace deka6pb\autoparser\components\CollectorService\Abstraction;

interface IPostDataProvider {
    public function init();

    public function GetPosts();
}