<?php
namespace deka6pb\autoparser\components\Abstraction;

interface IPostDataConsumer {
    function init();

    function SendPosts($data);
}